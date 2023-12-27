/**
 * ACF Handle scripts
 */

((w, $) => {
  "use strict";

  /**
   * Ready
   */
  $(document).ready(function () {
    acf.addAction("acfe/fields/button/complete/key=field_658bd082f0b27", function (response, $el, data) {
      response = JSON.parse(response);
      // console.log(response);
      // console.log($el);
      // console.log(data);

      window.location.reload();
    });
  });
})(window, jQuery);
