import { useState, useEffect } from "react";
import { get_EventRelationByEventId } from "../libs/api";

export default function EventRelation({ eventId }) {
  const [loading, setLoading] = useState(true);
  const [_EventRelation, _setEventRelation] = useState([]);

  useEffect(() => {
    const _get_EventRelationByEventId = async () => {
      const res = await get_EventRelationByEventId(eventId);
      _setEventRelation(res);
      setLoading(false)
    }

    _get_EventRelationByEventId();
  }, []);

  return <ul className="event-relation-list"> 
    {
      (loading ? 'loading...' : '')
    }  
    {
      _EventRelation.length > 0 && 
      _EventRelation.map(i => {
        return <li className="__item" key={ i.Id }>
          <span className={ ["__name", (i?.account_info?.Name ? '' : '__error')].join(' ') }>{ (i?.account_info?.Name ? i?.account_info?.Name : 'Error:') } (#{ i.RelationId })</span>
        </li>
      })
    }
  </ul>
}