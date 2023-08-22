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

    $('.cta-search').on('click', function (e) {
        e.stopPropagation();
        let $menuHeader = $('#menu-header');
        $menuHeader.find('.dropdown-menu').removeClass('show')
        $('.template-form-search-genrenal').toggleClass('show')
    });

    $(document).on("click", '.hamburger--spring.is-active', function (e) {
        $('.template-form-search').removeClass('show')
    })

    $('#menu-header >li:not(.cta-search)').on('click', function (e) {
        $('.template-form-search-genrenal').removeClass('show')
    })

    $('.template-form-search__close').on('click', function (e) {
        $('.template-form-search-genrenal').removeClass('show')
    })

    var cookie_user_id = (document.cookie.match(/^(?:.*;)?\s*userId\s*=\s*([^;]+)(?:.*)?$/) || [, null])[1]
    // console.log(document.cookie);
    if (cookie_user_id) {
        $('body .site-header .buttons').html("<a id='logout' href='/?force_logout' class='btn-text change logout'><img src='https://www.and.org.au/wp-content/themes/and/assets/imgs/user-icon.svg'><span>Logout</span></a><a id='dashboard' href='/dashboard' class='btn-text change'><span>Dashboard</span></a>");
    }
    else {
        $('body .site-header .buttons #login').css("visibility", "visible")
    }

    $("body .site-header #logout").on("click", function () {
        $.ajax({
            type: 'POST',
            url: elearn_ajax_params.ajax_url,
            data: {
                'action': 'and_remove_cookie',
            },
            beforeSend: function (xhr) {
                $("body").css('opacity', '0.8')
            },
            success: function (response) {
                window.location.href = '/';
            }
        });

        return false;
    });

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

    $(document).on("click", '.lightbox .btn-close-lightbox', function (e) {
        let btn_close_top = $(this).closest('.lightbox').find('.is-close')
        btn_close_top.click()
    })

})(window, jQuery);
