import { createContext, useContext, useEffect, useState } from 'react';
import { getJunctions, eventsImported, getEvents, prepareDataImportEvents, getAllProductsEventsImportedValidate } from './api';

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

  const _getJunctions = async () => {
    const { totalSize, records } = await getJunctions();
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
    console.log(res)
    setImportProducts(Object.values(res))
  }

  const _getAllProductsEventsImportedValidate = async () => {
    const res = await getAllProductsEventsImportedValidate();
    console.log(res);
    setProductsImported(res);
  }

  useEffect(() => {

    const dataInit = async () => {
      // _getEventsImported();
      // _getJunctions();
      // _getEvents();
      await _prepareDataImportEvents();
      _getAllProductsEventsImportedValidate();
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
  }

  return <SFEventContext.Provider value={ value }>
    { children }
  </SFEventContext.Provider>
}

const useSFEventContext = () => {
  return useContext(SFEventContext);
}

export { SFEventContext_Provider, useSFEventContext }