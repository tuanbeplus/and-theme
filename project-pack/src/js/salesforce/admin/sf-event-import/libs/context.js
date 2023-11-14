import { createContext, useContext, useEffect, useState } from 'react';
import { getJunctions, eventsImported, getEvents, prepareDataImportEvents, getAllProductsEventsImportedValidate } from './api';
import { RandomColor } from './helpers';

const SFEventContext = createContext();

const SFEventContext_Provider = ({ children }) => {
  const [Junctions, setJunctions] = useState([]);
  const [JunctionsSize, setJunctionsSize] = useState(0);
  const [dataEventsImported, setDataEventsImported] = useState([]);
  const [Events, setEvents] = useState([]);
  const [EventsSize, setEventsSize] = useState(0);
  const [ImportProducts, setImportProducts] = useState([]);
  const [ProductsImported, setProductsImported] = useState([]);
  const [loadingItems, setLoadingItems] = useState([]);
  const [Loading, setLoading] = useState(true);

  const _getJunctions = async () => {
    let { totalSize, records } = await getJunctions();
    records = [...records].map((__j) => {
      __j.__color = RandomColor();
      return __j;
    })

    setJunctions(records);
    setJunctionsSize(totalSize);
  }

  const _getEventsImported = async () => {
    const _eventImported = await eventsImported();
    setDataEventsImported(_eventImported);
  }

  const _getEvents = async () => {
    const { totalSize, records } = await getEvents();
    setEvents(records)
    setEventsSize(totalSize)
  }

  const _prepareDataImportEvents = async () => {
    const res = await prepareDataImportEvents();
    // console.log(res)
    setImportProducts(Object.values(res))
  }

  const _getAllProductsEventsImportedValidate = async () => {
    const res = await getAllProductsEventsImportedValidate();
    // console.log(res);
    setProductsImported(res);
  }

  useEffect(() => {

    const dataInit = async () => {
      // _getEventsImported();
      // _getEvents();
      await _prepareDataImportEvents();
      await _getJunctions();
      await _getAllProductsEventsImportedValidate();
      setLoading(false);
    }

    dataInit();
  }, [])

  const validateImportProducts = () => {
    const _ImportProducts = [...ImportProducts].map((pItem) => {
      const find = ProductsImported.find((_item) => {
        return _item.parent.product_sfid == pItem.Id
      });

      pItem.__imported = find ? true : false;

      if(pItem.__imported == true) {
        pItem.__product_edit_url = find.parent.product_edit_url;

        const __events = Object.values(pItem.__events).map((eItem) => {

          // Child event
          if(eItem.__event_type && eItem.__event_type == '__CHILDREN__') {
            const eFind = find.childrens.find((cItem) => {
              return cItem.event_child_sfid == eItem.Id
            })

            eItem.__imported = eFind ? true : false;
            eItem.__event_edit_url = eFind?.event_child_edit_url;
            return eItem;
          }
          // End child event

          const eFind = find.childrens.find((cItem) => {
            return cItem.event_sfid == eItem.Id
          })

          eItem.__imported = eFind ? true : false;
          eItem.__event_edit_url = eFind?.event_edit_url;
          return eItem;
        })

        pItem.__events = __events;
      }

      return pItem;
    })

    setImportProducts(_ImportProducts);
  }

  const mixJunctionsData = () => {
    const _ImportProducts = [...ImportProducts].map((pItem) => {
      // Junctions
      // console.log(Junctions);
      if(Junctions && Junctions.length > 0) {
        const __events = Object.values(pItem.__events).map((eItem) => {
          Junctions.forEach((jItem) => {
            const { Child_Event__c, Parent_Event__c } = jItem;
            if(jItem.Parent_Event__c == eItem.Id) {
              eItem.__event_type = '__PARENT__';
              eItem.__jcolor = jItem.__color;
              eItem.__junctions_id = jItem.Id;
              eItem.__junctions_data = { Child_Event__c, Parent_Event__c };
            }

            if(jItem.Child_Event__c == eItem.Id) {
              eItem.__event_type = '__CHILDREN__';
              eItem.__jcolor = jItem.__color;
              eItem.__junctions_id = jItem.Id;
              eItem.__junctions_data = { Child_Event__c, Parent_Event__c };
            }
          })

          return eItem;
        })

        // Sort couple of Junctions
        __events.sort((a ,b) => {    
          if(a.__junctions_id == b.__junctions_id) {
            if(a.__event_type == '__PARENT__') { return -1 }
            return 0;
          } else { return -1 }
        })

        // Valiadate of Junctions
        __events.map((eItem) => {
          eItem.__ready_import = true;

          if(eItem.__event_type == '__PARENT__') { 
            const found = __events.find(e => {
              return (eItem.__junctions_id == e.__junctions_id && e.__event_type == '__CHILDREN__');
            })
            
            if(!found) {
              eItem.__ready_import = false;
              eItem.__error_message = 'Could not find event __CHILDREN__!!! (Not ready to import)'
            }
          }

          if(eItem.__event_type == '__CHILDREN__') {
            const found = __events.find(e => {
              return (eItem.__junctions_id == e.__junctions_id && e.__event_type == '__PARENT__');
            })
            
            if(!found) {
              eItem.__ready_import = false;
              eItem.__error_message = 'Could not find event __PARENT__!!! (Not ready to import)'
            }
          }

          return eItem;
        })

        pItem.__events = __events;
      }

      return pItem;
    })

    setImportProducts(_ImportProducts);
  }

  useEffect(() => {
    // Mix Junctions Data
    mixJunctionsData();
  }, [Junctions])

  useEffect(() => {
    validateImportProducts();
  }, [ProductsImported])

  const value = {
    version: '1.0.0',
    Junctions, setJunctions,
    JunctionsSize, setJunctionsSize,
    dataEventsImported, setDataEventsImported,
    _getEventsImported,
    Events, setEvents,
    EventsSize, setEventsSize,
    ImportProducts, setImportProducts,
    ProductsImported, setProductsImported,
    _getAllProductsEventsImportedValidate,
    loadingItems, setLoadingItems,
    Loading, setLoading
  }

  return <SFEventContext.Provider value={ value }>
    { children }
  </SFEventContext.Provider>
}

const useSFEventContext = () => {
  return useContext(SFEventContext);
}

export { SFEventContext_Provider, useSFEventContext }