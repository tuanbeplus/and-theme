import { createContext, useContext, useEffect, useState } from 'react';
import { getJunctions, eventsImported, getEvents, prepareDataImportEvents } from './api';

const SFEventContext = createContext();

const SFEventContext_Provider = ({ children }) => {
  const [Junctions, setJunctions] = useState([]);
  const [JunctionsSize, setJunctionsSize] = useState(0);
  const [dataEventsImported, setDataEventsImported] = useState([]);
  const [Events, setEvents] = useState([]);
  const [EventsSize, setEventsSize] = useState(0);
  const [ImportProducts, setImportProducts] = useState([]);

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
    setImportProducts(Object.values(res))
  }

  useEffect(() => {

    const dataInit = async () => {
      // _getEventsImported();
      // _getJunctions();
      // _getEvents();
      _prepareDataImportEvents();
    }

    dataInit();
  }, [])

  const value = {
    version: '1.0.0',
    Junctions, setJunctions,
    JunctionsSize, setJunctionsSize,
    dataEventsImported, setDataEventsImported,
    _getEventsImported,
    Events, setEvents,
    EventsSize, setEventsSize,
    ImportProducts, setImportProducts
  }

  return <SFEventContext.Provider value={ value }>
    { children }
  </SFEventContext.Provider>
}

const useSFEventContext = () => {
  return useContext(SFEventContext);
}

export { SFEventContext_Provider, useSFEventContext }