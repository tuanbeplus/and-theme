import { useSFEventContext } from "../libs/context";
import ImportInfoWidget from "./ImportInfoWidget";
import JunctionTable from "./JunctionTable";

export default function ImportEventRoot() {
  const { Junctions, JunctionsSize } = useSFEventContext();

  return <div>
    <div className="pp-container">
      <div className="pp-panel">
        <div className="pp-panel__content">
          <JunctionTable />
        </div>
        <div className="pp-panel__sidebar">
          <ImportInfoWidget title="Import Informations" />
        </div>
      </div>
    </div>
  </div>
}