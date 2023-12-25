/**
 * API Script
 */

export const __Request = async (params, method = 'POST') => {
  return await window.jQuery.ajax({
    method,
    url: window.ajaxurl,
    data: params,
    error: (e) => {
      console.log(e)
    }
  })
}

export const getJunctions = async () => {
  return await __Request({
    action: 'pp_ajax_get_sf_junctions_object'
  });
}

export const getEvent = async () => {

}

export const getEvents = async () => {
  return await __Request({
    action: 'pp_ajax_get_events'
  });
}

export const eventsImported = async () => {
  return await __Request({
    action: 'ppwc_ajax_product_sfevent_validate_import'
  })
}

export const prepareDataImportEvents = async () => {
  return await __Request({
    action: 'pp_ajax_prepare_data_import_events',
  })
}

export const getAllProductsEventsImportedValidate = async () => {
  return await __Request({
    action: 'pp_ajax_ppsf_event_validate_products_import_exists',
  });
}

export const get_EventRelationByEventId = async (event_id) => {
  return await __Request({
    action: 'pp_ajax_get_EventRelation_by_event_Id',
    event_id,
  })
}

export const syncPricebook2 = async () => {
  return await __Request({
    action: 'pp_ajax_sync_Pricebook2'
  })
}

export const getWpPricebook2 = async () => {
  return await __Request({
    action: 'pp_ajax_get_wp_Pricebook2'
  });
}