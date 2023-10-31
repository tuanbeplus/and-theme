export const importProduct = async (productData) => {
  // console.log(productData);
  return await jQuery.ajax({
    type: 'POST',
    url: window.ajaxurl,
    data: {
      action: 'pp_ajax_ppsf_event_product_import',
      data: productData,
    },
    error: (err) => {
      console.log(err);
    }
  });
}