/**
 * General scripts
 */
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

export const doTippyGlobal = () => {
  const Elems = document.querySelectorAll('*[title]:not(.tippy-added)');
  [...Elems].forEach(e => {
    tippy(e, {
      content: e.title,
      zIndex: 999999
    });
    e.classList.add('tippy-added');
  })
}

window.__doTippyGlobal = doTippyGlobal;

/**
 * 
 * @param {*} text 
 * @param {*} timelive // default 3s
 * 
 * @return void
 */
export const notificationGlobal = (text, timelive = 2) => {
  jQuery('.pp-notification-global').remove(); 

  const $_temp = jQuery(`<div class="pp-notification-global">
    <div class="__inner">${ text }</div>
  </div>`);

  jQuery(document.body).append($_temp);
  
  setTimeout(() => {
    $_temp.addClass('__show');
  }, 1)

  if(timelive == false) return $_temp;

  setTimeout(() => {
    $_temp.remove();
  }, timelive * 1000)
}

window.__notificationGlobal = notificationGlobal;

((w, $) => {
  'use strict';

  const doInit = () => {
    doTippyGlobal();
  }

  /**
   * Dom ready
   */
  $(doInit)

})(window, jQuery);