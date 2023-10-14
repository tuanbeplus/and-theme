import { useSFEventContext } from "../libs/context";

export default function ImportInfoWidget({ title }) {
  const { JunctionsSize } = useSFEventContext();

  const list  = [
    {
      key: '6d651df9-f0b9-4142-bf65-b4fdd884efb3',
      name: `Junctions available`,
      value: `${ JunctionsSize } Item(s)`,
    },
    {
      key: '1c30bad1-036c-48ab-8922-2c6a3a9998a9',
      name: 'Last updated',
      value: 'Null'
    },
  ]

  return <div className="pp-panel__widget">
    <h4 className="pp-panel__widget-title widget-import-info">{ title }</h4>
    <ul>
      {
        list.map((item, _index) => {
          return <li key={ item.key }>
            <label>{ item.name }:</label> { item.value }
          </li>
        })
      }
    </ul>
  </div>
}