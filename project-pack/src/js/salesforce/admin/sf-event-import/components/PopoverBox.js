import {
  Popover,
  Trigger,
  Target,
  usePlacement,
  useIsOpen,
  Close
} from "@accessible/popover";

export default function PopoverBox({ placement = "right", label, children }) {
  const isOpen = useIsOpen();

  return (
    <Target placement={ placement }>
      <div className={ ['popover-box', isOpen ? '__open' : ''].join(' ') } >
        {
          label ? <label className="__label">{ label }</label> : ''
        }
        <div className="__entry">{ children }</div>
      </div>
    </Target>
  );
}