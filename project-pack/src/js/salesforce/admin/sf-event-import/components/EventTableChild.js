import { useState, useEffect } from "react";
import EventRelation from "./EventRelation";

const __TABLE_DATA = [
  {
    key: '108ba70f-20b3-48ab-9bf9-55fe470cb98b',
    label: 'Subject',
    field: (item) => {
      return <>
        ↳ <strong>{ item.Subject } { (item?.__old ? '[OLD]' : '') }</strong> 
        { 
          (item.__imported == true 
          ? <a className="open-url" target="_blank" href={ item.__event_edit_url }>
              <span className="dashicons dashicons-admin-links"></span>
            </a> 
          : '') 
        }<br />
        #ID: { item.Id } <br />
        { item.__junctions_id ? <div>Junctions ID: <span className='junction-tag' style={{ background: item.__jcolor }}> { item.__junctions_id }</span><br /></div> : '' }
        { item.__event_type ? <>Type: { item.__event_type }<br /></> : '' }
        {
          item.__error_message ? <div className='pp-message __error'>Error: { item.__error_message }</div> : ''
        }
        {/* <pre>{ JSON.stringify(item) }</pre> */}
      </>
    },
  },
  {
    key: '2de8e129-8f1d-4e75-8c4f-92d9e8130b87',
    label: 'Total Seats / Remaining Seats',
    field: (item) => {
      return `${ item.Total_Number_of_Seats__c } / ${ item.Remaining_Seats__c }`
    },
  },
  {
    key: '5927c47c-67bb-4140-9004-ccac9b6aec3c',
    label: 'Workshop Date / Time',
    field: (item) => {
      return <>{ item.Workshop_Event_Date_Text__c }<br /> { item.Workshop_Times__c }</>
    },
  },
  {
    key: '8f8a4139-f227-4c01-9b9d-cac13a41aaaa',
    enable: false,
    label: 'EventRelation (Account)',
    field: (item) => {
      return <EventRelation eventId={ item.Id } />
    }
  }
]

export default function EventTableChild({ events, product }) {
  const [enableEventRelation, setEnableEventRelation] = useState(false);
  const [tableData, setTableData] = useState(__TABLE_DATA);

  useEffect(() => {
    let __tableData = [...tableData].map(item => {
      // EventRelation item 
      if(item.key == '8f8a4139-f227-4c01-9b9d-cac13a41aaaa') {
        item.enable = enableEventRelation;
      }
      return item;
    });

    setTableData(__tableData);
  }, [enableEventRelation])

  return <>
    <div className="display-tools">
      <div className="display-tools__item">
        <input type="checkbox" onChange={ e => {
          setEnableEventRelation(e.target.checked)
        } } /> Show EventRelation
      </div>
    </div>
    <table className="pp-table events-table">
      <thead>
        <tr>
          {
            tableData.filter( _i => (_i.enable != false) ).map(({ label, key }) => {
              return <th key={ `__name-item-${ key }` }>
                { (typeof label === 'function') ? label() : label }
              </th>
            })
          }
        </tr>
      </thead>
      <tbody>
        {
          events.map((item) => {
            let __style = {};
            // console.log(item?.__old);
            __style = (item?.__jcolor ? { ...__style, borderLeftColor: item.__jcolor, borderStyle: 'solid', borderWidth: '0 0 0 2px' } : __style);
            return <tr className={ [(item?.__old ? '__old' : '')].join(' ') } key={ item.Id } style={ __style }>
              {
                tableData.filter( _i => (_i.enable != false) ).map(({ field, key }) => {
                  return <td key={ `__name-item-${ key }` }>
                    { (typeof field === 'function') ? field(item) : item[field] }
                  </td>
                })
              }
            </tr>
          })
        }
      </tbody>
    </table>
  </>
}