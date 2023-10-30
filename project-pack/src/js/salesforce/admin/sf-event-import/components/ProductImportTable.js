import { Fragment } from 'react';
import { useSFEventContext } from "../libs/context";
import { importProduct } from "../libs/actions";

const EventTableChild = ({ events, product }) => {

  const tableData = [
    {
      key: '108ba70f-20b3-48ab-9bf9-55fe470cb98b',
      label: 'Subject',
      field: (item) => {
        return <>
          ↳ <strong>{ item.Subject }</strong><br />
          #ID: { item.Id }
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
    // {
    //   key: '39c9f999-5510-41a3-aa42-a0c7dfbb19a2',
    //   label: 'Action',
    //   field: (item) => {
    //     return <button className="pp-button button-import" onClick={ async (e) => { 
    //       e.preventDefault(); } 
    //       }>
    //       Import 
    //       <svg fill="#FFFFFF" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
    //         <path d="M1574.513 138.515c-30.381-30.268-66.748-51.84-106.278-65.619v434.936h434.937c-13.78-39.529-35.238-75.896-65.62-106.164l-263.04-263.153Zm-219.219 482.19V56h-903.53v903.53H0v112.94h451.765v790.589H1920V620.706h-564.706ZM887.04 1425.3l-79.85-79.85 272.866-272.978h-515.35V959.529h515.35L807.191 686.664l79.849-79.85L1296.226 1016 887.04 1425.299Z" />{" "}
    //       </svg>
    //     </button>
    //   },
    // },
  ]

  return <table className="pp-table events-table">
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
        events.map((item) => {
          return <tr key={ item.Id }>
            {
              tableData.map(({ field, key }) => {
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
}

export default function ProductImportTable() {
  const { ImportProducts } = useSFEventContext();

  const tableData = [
    {
      key: '93a07b94-37b5-4556-9622-15c389eb46ae',
      label: () => {
        return <input className="pp-input pp-input__checkbox" type="checkbox" name="select-products-all" id="select-products-all" />
      },
      field: (item) => {
        return <input className="pp-input pp-input__checkbox" type="checkbox" name="product_ids_selected" value={ item.Id } />
      },
    },
    {
      key: '4b30bef2-f5a9-482c-9548-f4c025ecb520',
      label: 'Product Name',
      field: (item) => {
        return <>
          <strong>{ item.Name }</strong><br/>
          #ID: { item.Id }<br />
          { Object.keys(item.__events).length } Event(s)
        </>
      },
    },
    {
      key: '1d070b76-386d-4c0d-ac8e-eb428d16d4eb',
      label: 'Family',
      field: 'Family',
    },
    {
      key: '58f7de72-b343-48c1-ba73-8c07de075191',
      label: 'ProductCode',
      field: 'ProductCode',
    },
    {
      key: '3ae39b77-bd12-4f42-90fa-1ac78866d9b7',
      label: 'Description',
      field: (item) => {
        return <div dangerouslySetInnerHTML={{__html: item.Description}}></div>
      },
    },
    {
      key: '39c9f999-5510-41a3-aa42-a0c7dfbb19a2',
      label: 'Action',
      field: (item) => {
        return <button className="pp-button button-import" onClick={ async (e) => { 
          e.preventDefault();
          importProduct(item) } 
          }>
          Import 
          <svg fill="#FFFFFF" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
            <path d="M1574.513 138.515c-30.381-30.268-66.748-51.84-106.278-65.619v434.936h434.937c-13.78-39.529-35.238-75.896-65.62-106.164l-263.04-263.153Zm-219.219 482.19V56h-903.53v903.53H0v112.94h451.765v790.589H1920V620.706h-564.706ZM887.04 1425.3l-79.85-79.85 272.866-272.978h-515.35V959.529h515.35L807.191 686.664l79.849-79.85L1296.226 1016 887.04 1425.299Z" />{" "}
          </svg>
        </button>
      },
    },
  ]

  return <div className="product-import-table-container">
    <h4>Products Import Listing</h4>
    <p>Summary of object (Product2, Event, Junction_Workshop_Event__c) used in Salesforce to create the link between Workshop Events.</p>
    <table className="pp-table products-table">
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
          ImportProducts.map((item) => {
            return <Fragment key={ item.Id } >
              <tr>
                {
                  tableData.map(({ field, key }) => {
                    const rowSpan2 = ['93a07b94-37b5-4556-9622-15c389eb46ae', '39c9f999-5510-41a3-aa42-a0c7dfbb19a2'];
                    return <td rowSpan={ (rowSpan2.includes(key) ? 2 : 1) } key={ `__name-item-${ key }` }>
                      { (typeof field === 'function') ? field(item) : item[field] }
                    </td>
                  })
                }
              </tr>
              <tr>
                <td colSpan={ tableData.length - 2 } className="events-in-product">
                  <h4>↳ { Object.keys(item.__events).length } Event(s) of <u>{ item.Name }</u></h4>
                  <EventTableChild events={ Object.values(item.__events) } product={ item } />
                </td>
              </tr>
            </Fragment>
            
          })
        }
      </tbody>
    </table>
  </div>
}