import { useSFEventContext } from "../libs/context";

export default function Pricebook2Widget({ title, data }) {
  const { syncPricebook2Data } = useSFEventContext();

  return <div className="pp-panel__widget pp-panel__widget-Pricebook2">
    <h4 className="pp-panel__widget-title">{ title }</h4>
    <ul>
      {
        data.length > 0 && 
        data.map(i => {
          return <li key={ i.Id }>{ i.Name }</li>
        })
      }
    </ul>
    <button className="pp-button" onClick={ e => { 
      e.preventDefault();
      syncPricebook2Data() } }
      >Sync Pricebook2</button>
  </div>
}