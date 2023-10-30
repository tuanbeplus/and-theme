import { Fragment } from "react";
import { useSFEventContext } from "../libs/context";
import PopoverBox from "./PopoverBox";
import { Popover, Trigger } from "@accessible/popover";
import { importEvent } from "../libs/actions";

export default function EventsTable() {
  const { Events } = useSFEventContext();

  const doImportEvent = async (eventId) => {
    await importEvent(eventId)
  }

  const tableData = [
    {
      key: '93a07b94-37b5-4556-9622-15c389eb46ae',
      label: () => {
        return <input className="pp-input pp-input__checkbox" type="checkbox" name="select-events-all" id="select-events-all" />
      },
      field: (item) => {
        return <input className="pp-input pp-input__checkbox" type="checkbox" name="event_ids_selected" value={ item.Id } />
      },
    },
    {
      key: '97146021-647b-4e38-98e6-44198dd563eb',
      label: 'Subject',
      field: (item) => {
        return <>{ item.Subject }<br/> 
        <span title="Event ID">eID: { item.Id }</span><br/> 
        <span title="What ID">wID: { item.WhatId }</span>
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
      key: '39c9f999-5510-41a3-aa42-a0c7dfbb19a2',
      label: 'Action',
      field: (item) => {
        return <button className="pp-button button-import" onClick={ async (e) => { 
          e.preventDefault();
          doImportEvent(item.Id) } 
          }>
          Import 
          <svg fill="#FFFFFF" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
            <path d="M1574.513 138.515c-30.381-30.268-66.748-51.84-106.278-65.619v434.936h434.937c-13.78-39.529-35.238-75.896-65.62-106.164l-263.04-263.153Zm-219.219 482.19V56h-903.53v903.53H0v112.94h451.765v790.589H1920V620.706h-564.706ZM887.04 1425.3l-79.85-79.85 272.866-272.978h-515.35V959.529h515.35L807.191 686.664l79.849-79.85L1296.226 1016 887.04 1425.299Z" />{" "}
          </svg>
        </button>
      },
    },
  ]

  return <div className="events-table-container">
    <h4>Event Listing</h4>
    <p>This object used in Salesforce to create the link between Workshop Events.</p>
    <table className="pp-table events-table">
      <thead>
        <tr>
        {
          tableData.map(({ label, key }) => {
            return <th key={ `__name-item-${ key }` }>
              { (typeof label === 'function') ? label() : label }
            </th>
          })
        }
        </tr>
      </thead>
      <tbody>
        {
          Events.map(event => {
            return <tr key={ event.Id }>
              {
                tableData.map(({ field, key }) => {
                  return <td key={ `__value-item-${ key }` }>
                    { (typeof field === 'function') ? field(event) : event[field] }
                  </td>
                }) 
              }
            </tr>
          })
        }
      </tbody>
    </table>
  </div>
}