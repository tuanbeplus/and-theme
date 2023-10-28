export default function Tabs({ items, onClick, active }) {
  return <div className="pp-tabs-container">
    <div className="pp-tabs__heading">
      {
        items.map(({ label, name, key }) => {
          const show = name == active ? 'tab-show' : '';
          return <div 
            className={ `pp-tab__heading-item __tab-${ name } ${ show }` } 
            key={ `tab-key-${ key }` }
            onClick={ (e) => { onClick.call(e, name) } }>{ label }</div>
        })
      }
    </div>
    <div className="pp-tabs__content">
      {
        items.map(({ content, name, key }) => {
          const show = name == active ? 'tab-show' : '';
          return <div className={ `pp-tab__content-item __tab-${ name } ${ show }` } key={ `tab-key-${ key }` }>{ content }</div>
        })
      }
    </div>
  </div>
}