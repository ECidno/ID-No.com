'use strict';

import jQuery from 'jquery';
import bootstrapTable from 'bootstrap-table';
import bootstrapTableLocaleAll from 'bootstrap-table/dist/bootstrap-table-locale-all';
import 'bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js';

import { cbAction } from './modules/cbAction';
import { cbAjax } from './modules/cbAjax';
import { cbForm } from './modules/cbForm';
import { cbMap, cbMapLocationError } from './modules/cbMap';
import { cbMessage } from './modules/cbMessage';
import { cbModal, cbModalButton } from './modules/cbModal';
import { cbOffcanvas, cbOffcanvasButton } from './modules/cbOffcanvas';
import { cbUpload } from './modules/cbUpload';

// const's
const ajaxAction = document.getElementsByClassName('ajax-action') || [];
const ajaxForms = document.getElementsByClassName('ajax-form') || [];
const ajaxModal = document.getElementsByClassName('ajax-modal') || [];
const ajaxUpload = document.getElementsByClassName('ajax-upload') || [];

const btnToTop = document.getElementById('toTop') || null;
const btnShowMap = document.getElementById('mapShow') || null;
const fldIdNo = document.getElementsByClassName('idNo') || [];
const fldPassEnable = document.getElementById('fldPassEnable') || null;
const mapContainer = document.getElementById('mapContainer') || null;
const mapErrorContainer = document.getElementById('mapErrorContainer') || null;


/*
 * tables
 */

// loading template
window.loadingTemplate = (loadingMessage) => {
  return '<div class="ph-item"><div class="ph-picture"></div></div>';
}

// operate formatter
window.operateFormatter = (value, row, index) => {
  let operations = [];
  let btnCount = Object.keys(row.operations).length;

  Object
    .keys(row.operations)
    .forEach((key, idx) => {
      let operation = row.operations[key];
      let target = operation.target || '';
      let wrapper = operation.wrapper || 'modal';

      // dbl click
      if(key === 'dbl-click-row') {

      } else {

        // switch target
        switch (wrapper) {
          case 'page':
            if(typeof operation.hide === 'undefined' || operation.hide === false) {
              operations.push(
                [
                  '<a',
                  'class="btn btn-sm btn-outline-dark' + (idx < (btnCount - 1)? ' me-2 ' : ' ') + key + 'Page"',
                  (operation.title ? 'title="' + operation.title + '"' : ''),
                  (target ? 'target="' + operation.target + '"' : ''),
                  (operation.disabled === true ? 'disabled="disabled"' : ''),
                  'href="' + operation.uri + '">',
                  '<i class="' + operation.icon + '"></i>',
                  '</a>'
                ].join(' ')
              );
            }
            break;

          // modal
          case 'modal':
          case 'offcanvas':
              if(typeof operation.hide === 'undefined' || operation.hide === false) {
              operations.push(
                [
                  '<button',
                  'type="button"',
                  'class="btn btn-sm btn-outline-dark' + (idx < (btnCount - 1)? ' me-2 ' : ' ') + key + 'Operation"',
                  (operation.title ? 'title="' + operation.title + '"' : ''),
                  (operation.disabled === true ? 'disabled="disabled"' : ''),
                  'data-operation="' + key + '"',
                  'data-wrapper="' + wrapper + '"',
                  'data-url="' + operation.uri + '">',
                  '<i class="' + operation.icon + '"></i>',
                  '</button>'
                ].join(' ')
              );
            }
            break;
        }
      }
  });

  // return
  return operations.join('');
}


// document DOM ready
document.addEventListener(
  'DOMContentLoaded',
  function() {

    /*
     * bootstrap tables
     */
    jQuery.extend(
      jQuery.fn.bootstrapTable.defaults,
      {

        // response handler
        responseHandler: function (res) {
          return res;
        }
      }
    );

    // post header
    jQuery('table[data-toggle="table"]')
      .on(
        'post-header.bs.table',
        function() {
          let table = jQuery(this);
          let toolbar = jQuery(table.data('toolbar'));

          if (toolbar.hasClass('d-none')) {
            toolbar
              .hide()
              .removeClass('d-none')
              .fadeIn(400);
          }
          if (table.hasClass('d-none')) {
            table
              .hide()
              .removeClass('d-none')
              .fadeIn(500);
          }
        }
      )

      // post body
      .on(
        'post-body.bs.table',
        function() {

          // add listener for modal buttons
          Array
            .from(ajaxModal)
            .forEach((el) => {
              if((el.dataset.renderMode || 'modal') === 'modal') {
                cbModalButton(el);
              } else {
                cbOffcanvasButton(el);
              }
            });
        }
      )
      // dbl click row
      .on(
        'dbl-click-row.bs.table',
        function(e, row, $el, field) {
          if(row.operations['dbl-click-row']) {
            const btnSelector = 'button.' + row.operations['dbl-click-row'] + 'Operation';
            const aSelector = 'a.' + row.operations['dbl-click-row'] + 'Page';
            const btn = jQuery($el).find(btnSelector);
            const a = jQuery($el).find(aSelector);

            if(btn && btn.length > 0) {
              btn.trigger('click');
            }
            if(a && a.length > 0) {
              a.get(0).click();
            }
          }
        }
      );


    // add listener for modal buttons
    Array
      .from(ajaxModal)
      .forEach((el) => {
        if((el.dataset.renderMode || 'modal') === 'modal') {
          cbModalButton(el);
        } else {
          cbOffcanvasButton(el);
        }
      });

    // add listener for action buttons
    Array
      .from(ajaxAction)
      .forEach((el) => {
        cbAction(el);
      });

    // add listener for forms
    Array
      .from(ajaxForms)
      .forEach((el) => {
        cbForm(el);
      });

    // add listener for uploads
    Array
      .from(ajaxUpload)
      .forEach((el) => {
        el
          .addEventListener(
            'change',
            (e) => {
              const me = e.target;
              const file = me.files[0];
              const mimeTypes = [
                'image/gif',
                'image/jpg',
                'image/jpeg',
                'image/png',
              ];

              const myUpload = new cbUpload(
                file,
                me.closest('div.ajax-upload'),

                // done
                (res) => {
                  if(res.message ?? false) {
                    cbMessage(
                      res.severity || 0,
                      null,
                      res.message
                    );
                  }
                }
              );

              // check size | 2MB
              if(myUpload.getSize() > 2097152) {
                cbMessage(
                  1,
                  null,
                  'Die Datei darf nicht größer als 2MB sein.'
                );

              // check type
              } else if(mimeTypes.indexOf(myUpload.getType()) === -1) {
                cbMessage(
                  1,
                  null,
                  'Bitte wählen Sie eine Bilddatei (GIF, JPG oder PNG).'
                );

              // execute upload
              } else {
                myUpload.doUpload();
              }
            }
          );
      });


    /*
     * window event listener
     */
    window
      .addEventListener(
        'cb-modal.open',
        (e) => {

          // add listener for modal buttons
          Array
            .from(ajaxModal)
            .forEach((el) => {
              if((el.dataset.renderMode || 'modal') === 'modal') {
                cbModalButton(el);
              } else {
                cbOffcanvasButton(el);
              }
            });

          // add listener for forms
          Array
            .from(ajaxForms)
            .forEach((el) => {
              cbForm(el);
            });

          // add listener for uploads
          Array
            .from(ajaxUpload)
            .forEach((el) => {
              const initialized = el.dataset.init || false;

              if(initialized === false) {
                el
                  .addEventListener(
                    'change',
                    (e) => {
                      const me = e.target;
                      const file = me.files[0];
                      const mimeTypes = [
                        'image/gif',
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                      ];

                      const myUpload = new cbUpload(
                        file,
                        me.closest('div.ajax-upload'),

                        // done
                        (res) => {

                          // preview
                          const iconContainer = me
                            .closest('div.row')
                            .querySelector('div.userIconContainer');

                          iconContainer.innerHTML = '<div class="userIcon m-2" style="background-image:url(' + res.imageSrc + ');"></div>';

                          // message
                          if(res.message ?? false) {
                            cbMessage(
                              res.severity || 0,
                              null,
                              res.message
                            );
                          }
                        }
                      );

                      // check size | 2MB
                      if(myUpload.getSize() > 8388608) {
                        cbMessage(
                          1,
                          null,
                          'Die Datei darf nicht größer als 8MB sein.'
                        );

                      // check type
                      } else if(mimeTypes.indexOf(myUpload.getType()) === -1) {
                        cbMessage(
                          1,
                          null,
                          'Bitte wählen Sie eine Bilddatei (GIF, JPG oder PNG).'
                        );

                      // execute upload
                      } else {
                        myUpload.doUpload();
                      }
                    }
                  );
              }

              el.dataset.init = true;
            });

        },
        false
      );


    /*
     * page object event listener
     */

    // button | scroll top
    if(btnToTop) {
      window.addEventListener(
        'scroll',
        e => {
          if (
            /* hide at top */
            (
              document.body.scrollTop > 30 ||
              document.documentElement.scrollTop > 30
            ) &&
            /* hide at bottom */
            jQuery(window).scrollTop() + window.innerHeight !== jQuery(document).height()
          ) {
            jQuery(btnToTop).fadeIn();
          } else {
            jQuery(btnToTop).fadeOut();
          }
        }
      );

      // scroll
      btnToTop.addEventListener(
        'click',
        e => {
          document.body.scrollTop = 0; // For Safari
          document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }
      );
    }


    // button | show map
    if(btnShowMap && mapContainer) {
      let lat = mapContainer.dataset.lat;
      let lon = mapContainer.dataset.lon;

      mapContainer.style.display = 'block';
      cbMap(lat, lon);

      // event | click
      btnShowMap.addEventListener(
        'click',
        e => {

          // empty and hide error
          mapErrorContainer.classList.add('d-none');
          mapErrorContainer.innerHTML = '';

          // get position
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
              (pos) => {
                lat = pos.coords.latitude;
                lon = pos.coords.longitude;

                mapContainer.style.display = 'block';
                cbMap(lat, lon);
              },
              cbMapLocationError,
              {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
              }
            );

          // device does not support geolocation
          } else {
            console.error("Geolocation is not supported by this browser.");
          }
        }
      );
    };

    // fldIdNo | keyup | input pattern on field idno
    Array
      .from(fldIdNo)
      .forEach((el) => {
        el.addEventListener(
          'keyup',
          e => {
            if(e.key === 'Backspace' || e.key === 'Delete') {
              return;
            }

            // pattern xxxx-yyyy
            if(e.target.value.length === 4) {
              el.value += '-';
            }
          }
        );
      });


    // fldPassEnable | change
    if(fldPassEnable) {
      fldPassEnable.addEventListener(
        'change',
        e => {
          let el = e.target;
          let container = el.closest('div');
          let iconContainer = container.querySelector('span.icon i');
          let form = el.closest('form');
          let url = form.getAttribute('action');
          let options = {
            method: form.getAttribute('method') || 'POST',
            body: new FormData(form)
          };

          // disable
          el.disabled = true;

          // ajax
          cbAjax(url, options)
          .then(
            res => {

              // icon
              iconContainer.classList = el.checked
                ? el.dataset.iconOn
                : el.dataset.iconOff;

              // enable
              el.disabled = false;

              // show message
              cbMessage(
                res.severity || 0,
                null,
                res.message
              );
            }
          );
        }
      )
    }
  }
);
