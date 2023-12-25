import { useState, useEffect } from "react";
import { useSFEventContext } from "../libs/context";
import ImportInfoWidget from "./ImportInfoWidget";
// import JunctionTable from "./JunctionTable";
import Tabs from "./Tabs";
// import EventsTable from "./EventsTable";
import ProductImportTable from "./ProductImportTable";
import Pricebook2Widget from "./Pricebook2Widget";

export default function ImportEventRoot() {
  const { Junctions, JunctionsSize, Loading, pricebook2Data } = useSFEventContext();
  const [tabActive, setTabActive] = useState('ProductImportTable')

  return <div>
    <div className="pp-container">
      <div className="pp-panel">
        <div className="pp-panel__content">
          
          <Tabs items={[
            {
              label: "Import", 
              name: 'ProductImportTable', 
              key: 'f412b1d8-6e17-4f08-a157-168b07b1d6c5',
              content: <ProductImportTable />
            },
            // {
            //   label: "Import by Junction", 
            //   name: 'JunctionTable', 
            //   key: '6561fbc7-4395-4b96-9e71-4c3674292bec',
            //   content: <JunctionTable />
            // },
            // {
            //   label: "Import by Single Event", 
            //   name: 'SingleEvent', 
            //   key: 'b02c03f8-8544-4d8b-814f-a00c4a0067b5',
            //   content: <EventsTable />
            // }
          ]} 
          active={ tabActive }
          onClick={ (name) => {
            setTabActive(name)
          } } />
        </div>
        <div className="pp-panel__sidebar">
          <ImportInfoWidget title="Import Informations" />
          <Pricebook2Widget title="Pricebook2" data={ pricebook2Data } />
        </div>
      </div>
    </div>
  </div>
}