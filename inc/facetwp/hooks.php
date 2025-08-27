<?php

/**
 * Disable auto-refresh.
 */
add_action('facetwp_scripts', function () {
  ?>
  <script>
    (function ($) {
      $(function () {
        if ('undefined' !== typeof FWP) {
          FWP.auto_refresh = false;
        }
      });


    })(fUtil);
  </script>
  <?php
}, 100);
add_action('facetwp_scripts', function () {
  ?>
  <script>
    (function ($) {
      $(document).on('change', '.facetwp-type-sort select', function () {
        FWP.refresh();
      });
      $(document).on('click', '.facetwp-facet-resource_media_members_only .facetwp-checkbox', function () {
        FWP.refresh();
      });
      $(document).on('click', '.facetwp-type-radio .facetwp-radio', function () {
        FWP.refresh();
      });
    })(fUtil);

  </script>
  <?php
}, 100);

// Set default value for the members only facet.
//add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
//  if ( 'resources' == FWP()->helper->get_uri() ) { // Replace 'demo/cars' with the URI of your page (everything after the domain name, excluding any slashes at the beginning and end)
//    if ( empty( $url_vars['resource_media_members_only'] ) ) { // Replace 'make' with the name of your facet
//      $url_vars['resource_media_members_only'] = [1]; // Replace 'audi' with the facet choice that needs to be pre-selected. Use the technical name/slug as it appears in the URL when filtering
//    }
//  }
//  return $url_vars;
//} );

// Add a label to the selections.
add_action('wp_head', function () {
  ?>
  <script>
    (function ($) {
      $(function () {
        FWP.hooks.addAction('facetwp/loaded', function () {
          if ($('.selections-label').length === 0 && $('.facetwp-selections ul li').length > 0) {
            $('<div class="selections-label">Filters applied: </div>').insertBefore('.facetwp-selections ul');
          }
        }, 100);
      });
      $(document).ready(function () {
        // Add the label on page refresh, if it doesn't exist and there are selections.
        if ($('.selections-label').length === 0 && $('.facetwp-selections ul li').length > 0) {
          $('<div class="selections-label">Filters applied: </div>').insertBefore('.facetwp-selections');
        }

        // Remove the selections label if there are no selections.
        $(document).on('click', '.facetwp-selections .facetwp-selection-value', function () {
          if ($('.selections-label').length === 1 && $('.facetwp-selections ul li').length === 1) {
            $('.selections-label').remove();
          }
        });

      });
    })(jQuery);


    /**
  * Fix for fSelect dropdowns without modifying the original library
  */

    (function () {

      document.addEventListener('DOMContentLoaded', function () {

        if (typeof window.fSelect !== 'function') {
          console.error('fSelect library not found');
          return;
        }

        const originalFSelect = window.fSelect;

        window.fSelect = function (selector, options) {
          const instance = originalFSelect(selector, options);

          let nodes = [];
          if ('string' === typeof selector) {
            nodes = Array.from(document.querySelectorAll(selector));
          } else if (selector instanceof Node) {
            nodes = [selector];
          } else if (Array.isArray(selector)) {
            nodes = selector;
          }
          // Add direct click handlers to each dropdown
          nodes.forEach(function (input) {
            if (!input._rel) return;

            const wrap = input._rel;
            const labelWrap = wrap.querySelector('.fs-label-wrap');

            if (labelWrap) {

              const newLabelWrap = labelWrap.cloneNode(true);
              labelWrap.parentNode.replaceChild(newLabelWrap, labelWrap);

              // Add new direct click handler
              newLabelWrap.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (wrap.classList.contains('fs-open')) {
                  input.fselect.close();
                } else {
                  document.querySelectorAll('.fs-wrap.fs-open').forEach(function (openWrap) {
                    if (openWrap !== wrap && openWrap._rel) {
                      openWrap._rel.fselect.close();
                    }
                  });

                  input.fselect.open();
                }
              });
            }

            // Add click handlers to prevent dropdown from closing when clicking on options
            const dropdown = wrap.querySelector('.fs-dropdown');
            if (dropdown) {

              dropdown.addEventListener('click', function (e) {
                e.stopPropagation();
              });

              const options = dropdown.querySelectorAll('.fs-option');
              options.forEach(function (option) {
                option.addEventListener('click', function (e) {
                  e.stopPropagation();

                  // If it's a multiple select, don't close the dropdown
                  if (wrap.classList.contains('multiple')) {
                    const idx = parseInt(this.getAttribute('data-idx'));
                    input.options[idx].selected = !this.classList.contains('selected');
                    this.classList.toggle('selected');

                    // Update the label
                    const label = input.fselect.getDropdownLabel();
                    wrap.querySelector('.fs-label').innerHTML = label;

                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    e.preventDefault();
                  }

                });
              });
            }
          });

          document.addEventListener('click', function (e) {
            if (!e.target.closest('.fs-wrap') && window.fSelectInit && window.fSelectInit.activeEl) {
              window.fSelectInit.activeEl._rel.fselect.close();
            }
          });

          return instance;
        };

        // Re-initialize any existing fSelect elements
        document.querySelectorAll('.fs-wrap').forEach(function (wrap) {
          if (wrap._rel && wrap._rel.fselect) {
            wrap._rel.fselect.reload();
          }
        });
      });
    })();

  </script>
  <?php
}, 100);
// Remove default sort label.
add_filter('facetwp_facet_sort_options', function ($options, $params) {
  unset($options['newest']);
  return $options;
}, 10, 2);


/**
 * Add fSelect fix script using FacetWP hooks
 */
function add_fselect_fix_to_facetwp($scripts)
{
  // Add our fix script to the list of scripts
  $scripts['fselect-fix'] = [
    'src' => get_template_directory_uri() . '/assets/js/fselect-fix.js',
    'deps' => ['facetwp'],
    'ver' => '1.0.0'
  ];
  return $scripts;
}
add_filter('facetwp_assets', 'add_fselect_fix_to_facetwp');

?>