import { useSFEventContext } from "../libs/context";
import PopoverBox from "./PopoverBox";
import { Popover, Trigger } from "@accessible/popover";
import EventListInfo from "./EventListInfo";
import { useState } from "react";
import Checkbox from "./Checkbox";

const IconTicked = () => {
  return <i className="pp-icon-emoj">✅</i>
}

const IconLink = () => {
  return <span className="dashicons dashicons-admin-links pp-font-icon"></span>
}

export default function JunctionTable() {
  const [eventsDone, setEventsDone] = useState([]);
  const [eventsImported, setEventsImported] = useState([]);
  const [isCheckAll, setIsCheckAll] = useState(false);
  const [isCheck, setIsCheck] = useState([]);
  const [loading, setLoading] = useState(false);
  const { Junctions, dataEventsImported, _getEventsImported } = useSFEventContext();

  const isImportAvailable = (juncData) => {
    return juncData?.parent_event_data?.Id && juncData?.child_event_data?.Id ? true : false;
  };

  const handleSelectAll = e => {
    setIsCheckAll(!isCheckAll);
    setIsCheck(Junctions.map(item => item.Id));
    if (isCheckAll) {
      setIsCheck([]);
    }
  };

  const handleCheckboxClick = e => {
    const { id, checked } = e.target;
    setIsCheck([...isCheck, id]);
    if (!checked) {
      setIsCheck(isCheck.filter(item => item !== id));
    }
  };

  const handleImportEvents = async (eventIds) => {
    const eventIdsArr = eventIds.split(",");
    setEventsImported([...eventsImported, ...eventIdsArr]);
    setLoading(true);
    try {
      const response = await fetch(`${window.location.origin}/wp-json/wp/v2/salesforce-import-events/`, {
        method: "POST",
        headers: { "Content-Type": "application/json", },
        body: JSON.stringify( { eventIds } ),
      });
      const {importResult, result, failureCount} = await response.json();
      const importDoneList = [];
      importResult.map(event => {
        if (event.status == 'success') {
          importDoneList.push(event.id);
        }
      });
      setEventsDone([...eventsDone, ...importDoneList]);
      setIsCheck([]); 
      setIsCheckAll(false);

      _getEventsImported()
    } catch (e) {
      console.error("Error while fetching PWS API:", e);
    } finally {
      setLoading(false);
    }
  }

  const handleBulkImportEvents = async () => {
    // Generate string of checked items
    const checkedItems = isCheck.length
    ? isCheck.reduce((total, item) => {
        return total + "," + item;
      })
    : "";

    if ( checkedItems ) {
      console.log(checkedItems);
      handleImportEvents(checkedItems);
    }
  }

  const isJunctionImported = (jID) => {
    const found = dataEventsImported.find((item) => {
      return item.__sf_junction_id == jID;
    });

    return found ? true : false;
  }

  const isEventImported = (eID, name) => {
    const found = dataEventsImported.find((item) => {
      return item[name] == eID;
    });

    return found ? true : false;
  }

  const eventEditPostUrl = (eID, sfEventName, wpPostName) => {
    const found = dataEventsImported.find((item) => {
      return item[sfEventName] == eID;
    });

    return found[wpPostName];
  }

  return (
    <div className="junction-table-container">
      <h4>Junction Listing</h4>
      <p>This object used in Salesforce to create the link between Workshop Events.</p>

      <button 
        className={`pp-button import-all ${isCheck.length == 0 ? 'disable' : ''}`} 
        onClick={handleBulkImportEvents}>
        Import {isCheck.length > 0 ? isCheck.length : ''} events
      </button>

      <div className="junction-content">
        { loading && (
          <div className="and-loading-wrapper">
            <div className="and-spinner-loading"></div>
          </div>
        )}
        
        <table className="pp-table junction-table">
          <thead>
            <tr>
              <th>
                <Checkbox
                  type="checkbox"
                  name="selectAll"
                  id="selectAll"
                  handleClick={handleSelectAll}
                  isChecked={isCheckAll}
                />
              </th>
              <th>ID</th>
              <th>Name</th>
              <th>Parent Event</th>
              <th>Children Event</th>
              <th>Import</th>
              {/* <th>Status</th> */}
            </tr>
          </thead>
          <tbody>
            {Junctions.map((item, _name) => {
              let status = '';
              if ( !loading && eventsImported.length > 0 && eventsImported.includes(item.Id) ) {
                status = ( eventsDone.includes(item.Id) ) ? 'Done' : 'Failure';
              }
              return (
                <tr className={`__item-${_name}`} key={item.Id}>
                  <td>
                    <Checkbox
                      key={item.Id}
                      type="checkbox"
                      name={item.Id}
                      id={item.Id}
                      handleClick={handleCheckboxClick}
                      isChecked={isCheck.includes(item.Id)}
                    />
                  </td>
                  <td>
                    {item.Id} { (isJunctionImported(item.Id) ? <a href={ eventEditPostUrl(item.Id, '__sf_junction_id', '__product_edit_url') } target="_blank" title="Imported"><IconLink /></a> : '') }
                  </td>
                  <td>{item.Name}</td>
                  <td>
                    <Popover>
                      {item?.parent_event_data?.Id && (
                        <PopoverBox placement="bottomLeft" label={`Subject: ${item?.parent_event_data?.Subject}`}>
                          <EventListInfo event={item?.parent_event_data} />
                        </PopoverBox>
                      )}
                      <Trigger on="hover">
                        <div>
                          {item.Parent_Event__c} { (isEventImported(item?.parent_event_data?.Id, '__sf_event_parent_id') ? <a href={ eventEditPostUrl(item?.parent_event_data?.Id, '__sf_event_parent_id', '__wp_event_parent_admin_url') } target="_blank" title="Imported"><IconLink /></a> : '') }
                        </div>
                      </Trigger>
                    </Popover> 
                  </td>
                  <td>
                    <Popover>
                      {item?.child_event_data?.Id && (
                        <PopoverBox placement="bottomLeft" label={`Subject: ${item?.child_event_data?.Subject}`}>
                          <EventListInfo event={item?.child_event_data} />
                        </PopoverBox>
                      )}
                      <Trigger on="hover">
                        <div>
                          {item.Child_Event__c} { (isEventImported(item?.child_event_data?.Id, '__sf_event_child_id') ? <a href={ eventEditPostUrl(item?.child_event_data?.Id, '__sf_event_child_id', '__wp_event_child_admin_url') } target="_blank" title="Imported"><IconLink /></a> : '') }
                        </div>
                      </Trigger>
                    </Popover> 
                  </td>
                  <td>
                    <button
                      className={["pp-button button-import", status, isImportAvailable(item) ? "" : "btn-disable"].join(" ")}
                      onClick={()=> handleImportEvents(item.Id)}
                    >
                      Import
                      <svg fill="#FFFFFF" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1574.513 138.515c-30.381-30.268-66.748-51.84-106.278-65.619v434.936h434.937c-13.78-39.529-35.238-75.896-65.62-106.164l-263.04-263.153Zm-219.219 482.19V56h-903.53v903.53H0v112.94h451.765v790.589H1920V620.706h-564.706ZM887.04 1425.3l-79.85-79.85 272.866-272.978h-515.35V959.529h515.35L807.191 686.664l79.849-79.85L1296.226 1016 887.04 1425.299Z" />{" "}
                      </svg>
                    </button>                    
                  </td>
                  {/* <td>
                    <span className={`import-status ${status}`}>{status ? status : '-'}</span>
                  </td> */}
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    </div>
  );
}
