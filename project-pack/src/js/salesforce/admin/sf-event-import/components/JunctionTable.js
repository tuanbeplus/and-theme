import { useSFEventContext } from "../libs/context";
import PopoverBox from "./PopoverBox";
import { Popover, Trigger } from "@accessible/popover";
import EventListInfo from './EventListInfo';

export default function JunctionTable() {
  const { Junctions } = useSFEventContext();

  const isImportAvailable = (juncData) => {
    return (juncData?.parent_event_data?.Id && juncData?.child_event_data?.Id) ? true : false;
  }

  return <div className="junction-table-container">
    <h4>Junction Listing</h4>
    <p>This object used in Salesforce to create the link between Workshop Events.</p>
    <table className="pp-table junction-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          {/* <th>CreatedDate</th> */}
          <th>Parent Event</th>
          <th>Children Event</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        {
          Junctions.map((item, _name) => {
            return <tr className={ `__item-${ _name }` } key={ item.Id }>
              <td>{ item.Id }</td>
              <td>{ item.Name }</td>
              {/* <td>{ item.CreatedDate }</td> */}
              <td>
                <Popover>
                  {
                    item?.parent_event_data?.Id && 
                    <PopoverBox placement="bottomLeft" label={ `Subject: ${ item?.parent_event_data?.Subject }` }>
                      <EventListInfo event={ item?.parent_event_data } />
                    </PopoverBox>
                  }
                  <Trigger on="hover">
                    <div>{ item.Parent_Event__c }</div> 
                  </Trigger>
                </Popover>
              </td>
              <td>
                <Popover>
                  {
                    item?.child_event_data?.Id && 
                    <PopoverBox placement="bottomLeft" label={ `Subject: ${ item?.child_event_data?.Subject }` }>
                      <EventListInfo event={ item?.child_event_data } />
                    </PopoverBox>
                  }
                  <Trigger on="hover">
                    <div>{ item.Child_Event__c }</div> 
                  </Trigger>
                </Popover>
                
              </td>
              <td>
                <button className={ ['pp-button button-import', (isImportAvailable(item) ? '' : 'btn-disable')].join(' ') }>
                    Import 
                    <svg fill="#FFFFFF" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg"> <path d="M1574.513 138.515c-30.381-30.268-66.748-51.84-106.278-65.619v434.936h434.937c-13.78-39.529-35.238-75.896-65.62-106.164l-263.04-263.153Zm-219.219 482.19V56h-903.53v903.53H0v112.94h451.765v790.589H1920V620.706h-564.706ZM887.04 1425.3l-79.85-79.85 272.866-272.978h-515.35V959.529h515.35L807.191 686.664l79.849-79.85L1296.226 1016 887.04 1425.299Z"/> </svg>
                  </button>
              </td>
            </tr>
          })
        }
      </tbody>
    </table>
  </div>
}