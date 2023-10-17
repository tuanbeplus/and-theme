/**
 * 
 */

import React from 'react'
import { createRoot } from 'react-dom/client';
import { SFEventContext_Provider } from './libs/context';
import ImportEventRoot from './components/ImportEventRoot';

((w) => {
  'use strict';

  // Render your React component instead
  const rootEl = document.getElementById('PP_EVENT_IMPORT_PAGE_CONATAINER');
  
  if(rootEl) {
    const root = createRoot(rootEl);
    root.render(
    <SFEventContext_Provider>
      <ImportEventRoot />
    </SFEventContext_Provider>);
  }
  
})(window)

