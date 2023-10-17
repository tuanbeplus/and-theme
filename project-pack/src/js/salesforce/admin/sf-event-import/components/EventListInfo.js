export default function EventListInfo({ event }) {
  return <ul>
    <li>ID: { event?.Id }</li>
    <li>Date: { event?.Workshop_Event_Date__c ||'null' }</li>
    <li>Times: { event?.Workshop_Times__c ||'null' }</li>
    <li>Total Seats: { event?.Total_Number_of_Seats__c || 'null' }</li>
    <li>Remaining Seats: { event?.Remaining_Seats__c || 'null' }</li>
  </ul>
}