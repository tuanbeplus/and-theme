import { createContext, useContext, useEffect, useState } from 'react';
import { getJunctions } from './api';

const SFEventContext = createContext();

const SFEventContext_Provider = ({ children }) => {
  const [Junctions, setJunctions] = useState([]);
  const [JunctionsSize, setJunctionsSize] = useState(0);

  useEffect(() => {
    const _getJunctions = async () => {
      const { totalSize, records } = await getJunctions();
      setJunctions(records);
      setJunctionsSize(totalSize);
    }

    _getJunctions();
  }, [])

  const value = {
    version: '1.0.0',
    Junctions, setJunctions,
    JunctionsSize, setJunctionsSize
  }

  return <SFEventContext.Provider value={ value }>
    { children }
  </SFEventContext.Provider>
}

const useSFEventContext = () => {
  return useContext(SFEventContext);
}

export { SFEventContext_Provider, useSFEventContext }