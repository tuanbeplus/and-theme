"use strict";

/**
* Project pack javascript
* @author Beplus
*/
;

jQuery.curCSS = function (element, prop, val) {
    return jQuery(element).css(prop, val);
};

(function (w, $) {
    'use strict';

    //---Start Fix Tab Keyboard---//
    var is_tab_slide = false;
    var is_tab_keyboard = false;
    jQuery(document).on('keydown', function( e ) {
        // Get the focused element:
        e.preventDefault;
        is_tab_keyboard = true;

        if(e.shiftKey && e.keyCode == 9) {
            //console.log('prev!!!');
            var $focused_current = $(':focus');
            var $templ   = $focused_current.closest('.slides-ss');
            setTimeout(triggerPrevSlide,100);
            //Check prev tab
            if($templ.length){
              var $total_items = $templ.find('.owl-item').length;
              var $item_index = $templ.find('.owl-item.active').index();
              $item_index = !$item_index ? 1 : parseInt($item_index) + 1;
              //console.log($total_items,$item_index);
              if($item_index != 1) return false;
            }
        }else{
          if ( e.keyCode == 9 ) {
              //console.log('next!!!');
              var $focused_current = $(':focus');
              var $templ   = $focused_current.closest('.slides-ss');
              setTimeout(triggerNextSlide,100);
              //check next tab
              if($templ.length){
                var $total_items = $templ.find('.owl-item').length;
                var $item_index = $templ.find('.owl-item.active').index();
                $item_index = !$item_index ? 1 : parseInt($item_index) + 1;
                //console.log($total_items,$item_index);
                if($item_index < $total_items) return false;
              }
          }
        }
    });

    jQuery('.slide-content .cta').focus(function( e ) {

        if(!is_tab_keyboard){
            is_tab_slide = true;
        }

        if(e.keyCode != 9){
          is_tab_keyboard = false;
        }

    });

    function triggerNextSlide(){
      var $focused = $(':focus');
      var $templ   = $focused.closest('.slides-ss');
      if($templ.length > 0){
        if(is_tab_slide)
          $templ.find('.owl-next').click();
        //Focus button current
        setTimeout(function(){ $templ.find('.owl-item.active .cta').focus(); },100);
        is_tab_slide = true;
      }else{
        is_tab_slide = false;
      }
    }

    function triggerPrevSlide(){
      var $focused = $(':focus');
      var $templ   = $focused.closest('.slides-ss');
      if($templ.length > 0){
        if(is_tab_slide)
          $templ.find('.owl-prev').click();
        //Focus button current
        setTimeout(function(){ $templ.find('.owl-item.active .cta').focus(); },100);
        is_tab_slide = true;
      }else{
        is_tab_slide = false;
      }
    }

    //---End Fix Tab Keyboard---//

    $("#autocomplete_search").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/wp-json/wp/v2/all_posts/",
                contentType: 'jsonp',
                data: {
                    keysearch: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        }
    }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
            .append('<a href="' + item.url + '"> <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"> <title id="searchTitle">Search</title> <path d="M10.917 9.667h-.659l-.233-.225a5.393 5.393 0 001.308-3.525 5.416 5.416 0 10-5.416 5.416 5.393 5.393 0 003.525-1.308l.225.233v.659l4.166 4.158 1.242-1.242-4.158-4.166zm-5 0a3.745 3.745 0 01-3.75-3.75 3.745 3.745 0 013.75-3.75 3.745 3.745 0 013.75 3.75 3.745 3.745 0 01-3.75 3.75z" fill="#3D3D3D"/> </svg> <span>' + item.label + "</span></a>")
            .appendTo(ul);
    };

    $('.suggestions-search a.btn-suggestions').on('click', function () {
        var sg = $(this).data('search');
        $('.template-form-search .search-field').val(sg);
        $('.template-form-search .search-form').submit();
    });

    // Click to toggle search popup
    $('.cta-search').on('click', function (e) {
        e.stopPropagation();
        let $menuHeader = $('#menu-header');
        let cta_link = $menuHeader.find('.cta-search .nav-link');
        if (cta_link.attr('aria-expanded') == 'false') {
            cta_link.attr('aria-expanded', 'true')
        }
        else {
            cta_link.attr('aria-expanded', 'false')
        }
        $menuHeader.find('.dropdown-menu').removeClass('show')
        $('.template-form-search-genrenal').toggleClass('show')
    });

    $(document).on("click", '.hamburger--spring.is-active', function (e) {
        $('.template-form-search').removeClass('show')
    })

    $('#menu-header >li:not(.cta-search)').on('click', function (e) {
        $('.template-form-search-genrenal').removeClass('show')
        let $menuHeader = $('#menu-header');
        $menuHeader.find('.cta-search .nav-link').attr('aria-expanded', 'false')
    })

    // Click button to close search popup
    $('#btn-close-search-popup').on('click', function (e) {
        $(this).closest('.template-form-search-genrenal').removeClass('show')
        let $menuHeader = $('#menu-header');
        $menuHeader.find('.cta-search .nav-link').attr('aria-expanded', 'false')
    })

    // Close saerch popup by event focus out (blur)
    $('.suggestions-search .btn-suggestions.last-btn').on('blur', function (e) {
        $(this).closest('.template-form-search-genrenal').removeClass('show')
        let $menuHeader = $('#menu-header');
        $menuHeader.find('.cta-search .nav-link').attr('aria-expanded', 'false')
    })

    var cookie_user_id = (document.cookie.match(/^(?:.*;)?\s*userId\s*=\s*([^;]+)(?:.*)?$/) || [, null])[1]
    // console.log(document.cookie);

    $(document).on('click', 'body.how_we_can_help_you-template-default a.cta', function (e) {
        e.preventDefault();
        // console.log('true');

        if ($(this).attr('href') == 'https://andau.force.com/forms/s/andforms?formtype=mentor_application&programid=a0k9q000000AIwUAAW' || $(this).attr('href') == 'https://andau.force.com/forms/s/andforms?formtype=stepping_position&programid=a0k9q000000BkFbAAK') {
            if (getCookie('lgi') == "1") {
                window.location = $(this).attr('href');

            } else {
                if ($(this).attr('href') == 'https://andau.force.com/forms/s/andforms?formtype=stepping_position&programid=a0k9q000000BkFbAAK') {
                    createCookie('stepping', 'https://andau.force.com/forms/s/andforms?formtype=stepping_position&programid=a0k9q000000BkFbAAK');
                } else {
                    createCookie('mentor', 'https://andau.force.com/forms/s/andforms?formtype=mentor_application&programid=a0k9q000000AIwUAAW');
                }
                window.location = 'https://andau.force.com/forms/services/oauth2/authorize?client_id=3MVG9ZL0ppGP5UrC0vFxLOYduKmRO3i.o3N40ZZnh_xLf03DeigkGkE4iwIwcs4qRLp87WxO1Mc5Y5nZrj3qN&redirect_uri=https://www.and.org.au/sfcallback.php&response_type=code&prompt=login';
            }
        } else {
            if ($(this).attr('href') !== '' && $(this).attr('href') !== '#' && $(this).attr('href') !== undefined && $(this).attr('href') !== "undefined") {
                window.location = $(this).attr('href');
            }
        }
    });

    // Close Our team Dialog lightbox 
    $(document).on("click", '.lightbox .btn-close-lightbox', function (e) {
        let btn_close_top = $(this).closest('.lightbox').find('.is-close')
        btn_close_top.click()
    })

    // Click to Custom attr Our team Modal Dialog
    $(document).on("click", '.btn-open-lightbox[data-fancybox=dialog]', function (e) {
        // Call func on click
        custom_attr_our_team_modal_dialog();
    })

    // Custom Attributes Our team Modal Dialog
    function custom_attr_our_team_modal_dialog() {
        let fancybox_container =  $('.fancybox__container[role=dialog]')
        let dialog_content_id = fancybox_container.find('.fancybox__slide.is-selected .fancybox__content').attr('id')

        // Add attr
        fancybox_container.removeAttr('aria-label')
        fancybox_container.attr('aria-describedby', 'dialog-instruction')
        fancybox_container.attr('aria-labelledby', dialog_content_id)
        // Add instruction
        fancybox_container.prepend('<p id="dialog-instruction"> You can close this modal content with the ESC key. </p>')
    }
    // Call func on load
    custom_attr_our_team_modal_dialog();

    // Select all <table> elements and add role
    $('.single-column-content table').each(function() {
        $(this).attr('role', 'presentation');
    });

    // Smooth scrolling to anchor links
    $(document).on('click', 'a[href^="#"]', function(e) {
        let target = $(this.getAttribute('href'));
        if( target.length ) {
            e.preventDefault();
            let offset = 24; 
            let admin_bar = $('#wpadminbar');
            if (admin_bar.length > 0) {
                offset = offset + admin_bar.outerHeight();
            }
            $('html, body').stop().animate({
                scrollTop: target.offset().top - offset
            }, 300);
        }
    });

    // Find all target blank anchor tag and add External link Icon
    $('.text-content-block a[target=_blank]').each(function() {
        $(this).append('<span class="external-link-icon" role="img" aria-label="External"></span>')
    });

    // Stop propagation Nav dropdown menu
    $(document).on('click', '#main-nav ul li .dropdown-menu', function(e){
        e.stopPropagation();
    });
    // Click button Close to trigger Hide Dropdown menu
    $(document).on('click', '#main-nav .dropdown-menu .btn-close-dropdown-menu', function(e){
        e.preventDefault();
        let main_nav_item = $(this).closest('#main-nav ul li.menu-item-has-children.show')
        let btn_show_hide_dropdown = main_nav_item.find('a[data-toggle=dropdown][role=button]')
        btn_show_hide_dropdown.click()
    });

    // Press SPACE to trigger Nav menu item
    $('#main-nav ul li a[role=button]').on('keydown', function(event) {
        if (event.key === ' ') { // Check if the pressed key is "space"
            event.preventDefault(); // Prevent the default action
            $(this).click(); // Trigger the click event
        }
    });

})(window, jQuery);
