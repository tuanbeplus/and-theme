import { Fragment } from 'react';
import { useSFEventContext } from "../libs/context";
import { importProduct } from "../libs/actions";
import EventTableChild from "./EventTableChild";

const IconImport = () => {
  return <svg fill="#FFFFFF" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
    <path d="M1574.513 138.515c-30.381-30.268-66.748-51.84-106.278-65.619v434.936h434.937c-13.78-39.529-35.238-75.896-65.62-106.164l-263.04-263.153Zm-219.219 482.19V56h-903.53v903.53H0v112.94h451.765v790.589H1920V620.706h-564.706ZM887.04 1425.3l-79.85-79.85 272.866-272.978h-515.35V959.529h515.35L807.191 686.664l79.849-79.85L1296.226 1016 887.04 1425.299Z" />{" "}
  </svg>
}

export default function ProductImportTable() {
  const { 
    ImportProducts, 
    _getAllProductsEventsImportedValidate, 
    loadingItems, 
    setLoadingItems } = useSFEventContext();

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
          <strong>{ item.Name }</strong> 
          { 
            (item.__imported == true 
            ? <a className="open-url" target="_blank" href={ item.__product_edit_url }>
                <span className="dashicons dashicons-admin-links"></span>
              </a> 
            : '') 
          }<br/>
          #ID: { item.Id }<br />
          { Object.keys(item.__events).length } Event(s)
        </>
      },
    },
    {
      key: '1d070b76-386d-4c0d-ac8e-eb428d16d4eb',
      label: 'Family (Category)',
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
        const isLoading = loadingItems.includes(item.Id);
        return <button className={ ["pp-button button-import", (item.__imported == true ? '__imported' : ''), isLoading ? '__is-loading' : ''].join(' ') } onClick={ async (e) => { 
          e.preventDefault();
          const r = confirm('Are you sure you want to import?');
          if(!r) return;

          setLoadingItems([item.Id]);
          await importProduct(item); 
          await _getAllProductsEventsImportedValidate();
          setLoadingItems([]); }}>
          { item.__imported == true ? 'Imported' : 'Import' } <IconImport />
        </button>
      },
    },
  ]

  return <div className="product-import-table-container">
    <h4>Products Import Listing</h4>
    <p>Summary of object (Product2, Event, Junction_Workshop_Event__c) used in Salesforce to create the link between Workshop Events.</p>
    {/* <pre>{ JSON.stringify(ImportProducts) }</pre> */}
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
              <tr className={ item.__imported ? '__imported' : '' }>
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
                  <h4>↳ { Object.keys(item.__events).length } Event(s) in <u>{ item.Name }</u></h4>
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