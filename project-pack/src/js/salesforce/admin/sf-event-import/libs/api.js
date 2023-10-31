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