import { createContext, useContext, useEffect, useState } from 'react';
import { getJunctions, eventsImported } from './api';

const SFEventContext = createContext();

const SFEventContext_Provider = ({ children }) => {
  const [Junctions, setJunctions] = useState([]);
  const [JunctionsSize, setJunctionsSize] = useState(0);
  const [dataEventsImported, setDataEventsImported] = useState([]);

  useEffect(() => {

    const dataInit = async () => {
      const _getJunctions = async () => {
        const { totalSize, records } = await getJunctions();
        setJunctions(records);
        setJunctionsSize(totalSize);
      }
  
      const _getEventsImported = async () => {
        const _eventImported = await eventsImported();
        setDataEventsImported(_eventImported);
      }
  
      _getEventsImported();
      _getJunctions();
    }

    dataInit();
  }, [])

  const value = {
    version: '1.0.0',
    Junctions, setJunctions,
    JunctionsSize, setJunctionsSize,
    dataEventsImported, setDataEventsImported
  }

  return <SFEventContext.Provider value={ value }>
    { children }
  </SFEventContext.Provider>
}

const useSFEventContext = () => {
  return useContext(SFEventContext);
}

export { SFEventContext_Provider, useSFEventContext }