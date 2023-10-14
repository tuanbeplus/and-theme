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

}