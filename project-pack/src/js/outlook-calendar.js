/**
 * MS Outlook calendar
 */
import qs from 'qs';

((w, $) => {
  'use strict';
  let { 
    ajax_url,
    __ms_graph_token, 
    __ms_graph_refresh_token,  
    ms_client_id,
    ms_scope,
    ms_client_secret,
    ms_return_url
  } = PP_DATA;
  const MS_ENDPOINT = 'https://login.microsoftonline.com/consumers/oauth2/v2.0';
  const AUTH_ENDPOINT = `${ MS_ENDPOINT }/authorize`;
  const TOKEN_ENDPOINT = `${ MS_ENDPOINT }/token`;

  const _Auth = async (callback) => {
    let params = { 
      client_id: ms_client_id,
      response_type: 'code',
      redirect_uri: ms_return_url,
      response_mode: 'query', 
      scope: ms_scope
    }

    const authUrl = `${ AUTH_ENDPOINT }?${ qs.stringify(params, { encode: false }) }`;
    let authBrowser = w.open(authUrl, '_blank', 'left=100,top=100,width=420,height=500');
    let authBrowserInterval = setInterval(async () => {
      try {
        const hrefReturn = authBrowser.location.href;
        if('about:blank' == hrefReturn) return;
        clearInterval(authBrowserInterval);

        const [siteUrl, queryString] = hrefReturn.split('?');
        const { code } = qs.parse(queryString);
        const __result = await $.get(`${ ms_return_url }?code=${ code }&__action=getmstoken`);
        const { access_token, refresh_token } = JSON.parse(__result);
        
        authBrowser.close();
        __ms_graph_token = w.__ms_access_token = access_token;
        __ms_graph_refresh_token = w.__ms_refresh_token = refresh_token;

        callback.call('', { access_token, refresh_token });
      } catch (e) {
        console.log(e);
      }
    }, 100)

    return;
  }

  const addCheduleOutlookCalendar = async (orderID, access_token) => {
    // console.log(orderID, access_token);
    const res = await $.ajax({
      type: 'POST',
      url: ajax_url,
      data: {
        action: 'pp_ajax_get_course_info_by_order',
        order_id: orderID
      }
    })

    if(res.success != true) {
      console.error('Error internal. Please try again!');
      return;
    }

    const result = await $.ajax({
      type: 'POST',
      url: 'https://graph.microsoft.com/v1.0/$batch',
      headers: {
        'Authorization': 'Bearer ' + access_token,
        'Content-Type': 'application/json',
        'ConsistencyLevel': 'eventual',
      },
      data: JSON.stringify({  
        "requests": res.content
      }  )
    });
    // console.log( result); return;
    return result;
  }

  const init = () => {
  
    $('body').on('click', '.woocommerce-orders-table.woocommerce-MyAccount-orders .button.outlook', async function(e) {
      e.preventDefault();
      let $btn = $(this);
      let [link, orderID] = this.href.split('#');

      $btn.addClass('btn-busy');

      if(!__ms_graph_token) {
        $btn.text('Authentication...');
        await _Auth( async ({ access_token, refresh_token }) => {
          $btn.text('Adding chedule...');
          const result = await addCheduleOutlookCalendar(orderID, access_token);
          $btn.removeClass('btn-busy');
          if([200, 201].includes(result.responses[0].status)) {
            $btn.text('Successfully 👍');
            // alert('Add the course to the Outlook calendar Successfully. 👍');
          } else {
            $btn.text('Fail ❌');
            // alert('❌ Internal Server Error, Please try again!');
          }
        });
      } else {
          $btn.text('Adding chedule...');
          const result = await addCheduleOutlookCalendar(orderID, __ms_graph_token);
          $btn.removeClass('btn-busy');
          if([200, 201].includes(result.responses[0].status)) {
            $btn.text('Successfully 👍');
            // alert('Add the course to the Outlook calendar Successfully. 👍');
          } else {
            $btn.text('Fail ❌');
            // alert('❌ Internal Server Error, Please try again!');
          }
      }
    })
  }

  /** DOM Ready */
  $(() => {
    init();
  })
})(window, jQuery)