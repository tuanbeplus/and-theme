export const RandomColor = () => {
  return '#' + Math.round((0x1000000 + 0xffffff * Math.random())).toString(16).slice(1);
}

export const EventOrderBy = (events) => {
  let onlyParentEvents = events.filter(_e => {
    if(!_e.__junctions_id || _e.__junctions_id == '') {
      return true;
    }
    return _e.__event_type === '__PARENT__'
  })

  let onlyChildEvents = events.filter(_e => {
    return _e.__event_type === '__CHILDREN__'
  })

  // Sort by date
  onlyParentEvents.sort((a, b) => {
    let c = Date.parse(a.StartDateTime);
    let d = Date.parse(b.StartDateTime);
    // console.log(c, d, a.StartDateTime, b.StartDateTime);
    return d - c;
  })

  onlyChildEvents.map((_e) => {
    let child_junctions_id = _e.__junctions_id;
    
    let _parentIndex = onlyParentEvents.findIndex(_i => {
      return (_i.__junctions_id == child_junctions_id);
    });

    if(_parentIndex !== -1) {
      onlyParentEvents.splice(_parentIndex + 1, 0, _e);
    } 
    
    return _e;
  }) 

  // console.log(onlyParentEvents);

  return onlyParentEvents;
}