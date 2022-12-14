'use strict';

import { Modal, Toast } from 'bootstrap';
import jQuery from 'jquery';
import bootstrapTable from 'bootstrap-table';
import bootstrapTableLocaleAll from 'bootstrap-table/dist/bootstrap-table-locale-all';
import 'bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js';

import { control, latLng, map, tileLayer, Browser } from 'leaflet';

// const's
const btnToTop = document.getElementById('toTop') || null;
const btnShowMap = document.getElementById('mapShow') || null;

const toastContainer = document.getElementById('toastContainer') || null;
const modalContainer = document.getElementById('modalContainer') || null;
const mapContainer = document.getElementById('mapContainer') || null;
const mapErrorContainer = document.getElementById('mapErrorContainer') || null;

const ajaxForms = document.getElementsByClassName('ajax-form') || [];
const ajaxModal = document.getElementsByClassName('ajax-modal') || [];
const ajaxAction = document.getElementsByClassName('ajax-action') || [];
const ajaxValidate = document.getElementsByClassName('ajax-validate') || [];
const fldIdNo = document.getElementsByClassName('idNo') || [];
const fldPassEnable = document.getElementById('fldPassEnable') || null;

var showModal;
var positionMap;
var positionLatLng;
var positionMarker;
var positionPopup;


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
  Object
    .keys(row.operations)
    .forEach((key, idx) => {
      let val = row.operations[key];
      operations.push(
        [
          '<button',
          'type="button"',
          'class="btn btn-sm btn-outline-dark' + (idx == 0 ? ' me-2 ' : ' ') + key + '"',
          'data-url="' + val.uri + '">',
          '<i class="' + val.icon + '"></i>',
          '</button>'
        ].join(' ')
      );
  });

  // return
  return operations.join('');
}

// item idNo formatter
window.itemidNoFormatter = (value, row, index) => {
  return '<a href="/' + value + '" target="_blank">' + value + '</a>';
}

// item status formatter
window.itemStatusFormatter = (value, row, index) => {
  var table = jQuery('#tableItems');
  return row.status === true
    ? '<span><i class="bi bi-check-circle text-success me-1"></i><span class="d-none d-md-inline">' + table.data('llActive') + '</span></span>'
    : '<span><i class="bi bi-exclamation-triangle text-warning me-1"></i><span class="d-none d-md-inline">' + table.data('llInactive') + '</span></span>';
}

// operate events
window.operateEvents = {

  // edit
  'click .edit': (e, value, row, index) => {
    showModal(row.operations.edit.uri);
  },

  // delete
  'click .delete': (e, value, row, index) => {
    showModal(row.operations.delete.uri);
  }
}


// show form filed error
window.showFieldError = (field, message) => {
  let parent = field.parentNode;
  let errorContainer =  document.createElement('div');

  Array
    .from(parent.getElementsByClassName('invalid-feedback') || [])
    .forEach((el) => {
      el.remove();
    });

  errorContainer.className = 'invalid-feedback';
  errorContainer.innerText = message;
  parent.append(errorContainer);
}


// document DOM ready
document.addEventListener(
  'DOMContentLoaded',
  function() {

    /*
     * bootstrap tables
     */
    jQuery('table[data-toggle="table"]').on(
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
    );


    /**
     * ajax
     *
     * @param string uri
     * @param object options
     *
     * @return Promise
     */
    var ajax = (uri, options) => {

      // return promise
      return fetch(
        uri,
        options
      )
      .then(res => {
        if(res.ok) {
          return res.json();
        }

        // err
        return res
          .json()
          .then(err => {
            throw new Error(err.message ?? err.error)
          });
      });
    }


    /**
     * showMessage
     *
     * @param int severity
     * @param string header
     * @param string body
     * @param boolean autohide
     */
     var showMessage = (severity, header, body, autohide) => {
      let el = toastContainer.cloneNode(true);
      let headerEl = el.querySelector('div.toast-header');
      let headerText = headerEl.querySelector('span');
      let bodyEl = el.querySelector('div.toast-body');
      let type = severity === 0
        ? 'success'
        : (
          severity === 1
            ? 'warning'
            : 'danger'
        );

      // change content and styles
      headerEl.classList.add('bg-' + type);
      headerText.textContent = header || el.getAttribute('data-' + type + '-header');
      bodyEl.innerHTML = body;

      // add
      toastContainer.after(el);

      // show
      new Toast(
        el,
        {
          autohide: (typeof(autohide) === 'undefined'
            ? true
            : autohide)
        }
      )
      .show();
    }


    /**
     * showModal
     *
     * @param string uri
     * @param string body
     * @param boolean autohide
     */
    showModal = (uri, options) => {
      options = options ?? {
        method: 'GET'
      };

      // ajax
      ajax(uri, options)
        .then(res => {
          modalContainer.innerHTML = res.html;
          new Modal(
            modalContainer,
            {
              backdrop:'static'
            }
          )
          .show();

          // init ajax
          initAjax();
        })
        .catch((err) => {
          showMessage(
            err.severity || 9,
            null,
            err.message
          );
        });
    }

    // show map
    function showMap(lat, lon) {
      let latitude = lat || mapContainer.dataset.lat;
      let longitude = lon || mapContainer.dataset.lon;
      let geocodeUrl = [
        mapContainer.dataset.geocodeUrl,
        'lat=' + latitude,
        'lon=' + longitude,
        'apiKey=' + mapContainer.dataset.geocodeKey
      ].join('&');

      // move to position
      if(positionMap) {
        positionLatLng = L.latLng(
          latitude,
          longitude
        );

        // position marker
        positionMarker.setLatLng(positionLatLng);

        // position map
        positionMap.flyTo(
          positionLatLng,
          mapContainer.dataset.zoom
        );

      // create map
      } else {
        positionLatLng = L.latLng(
          latitude,
          longitude
        );

        // position map
        positionMap = L
          .map(mapContainer.id)
          .setView(
            [
              latitude,
              longitude
            ],
            mapContainer.dataset.zoom
          );

        // Retina displays require different mat tiles quality
        const isRetina = L.Browser.retina;
        const baseUrl = mapContainer.dataset.baseUrl;
        const retinaUrl = mapContainer.dataset.retinaUrl;

        // Add map tiles layer. Set 20 as the maximal zoom and provide map data attribution.
        L
          .tileLayer(
            isRetina
              ? retinaUrl
              : baseUrl,
            {
              attribution: 'Powered by <a href="https://www.geoapify.com/" target="_blank">Geoapify</a> | <a href="https://openmaptiles.org/" target="_blank">© OpenMapTiles</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap</a>',
              apiKey: mapContainer.dataset.apiKey,
              maxZoom: 20,
              id: mapContainer.dataset.type,
            }
          )
          .addTo(positionMap);

        // position marker
        positionMarker = L.marker(
          positionLatLng,
          {
            icon: L.divIcon({
              html: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#91B54D" class="bi bi-geo-alt-fill" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>',
              iconSize: [1, 1],
              iconAnchor: [16, 0],
            })
          }
        )
        .addTo(positionMap);

        // geocode
        let geocodeRes = fetch(
          geocodeUrl,
          {
            method: 'GET',
          }
        )
        .then(response => response.json())
        .then(result => {

          if(result.features[0].properties.formatted || false) {
            positionPopup = L
              .popup()
              .setContent('<p>' + result.features[0].properties.formatted + '</p>')

            positionMarker
              .bindPopup(positionPopup)
              .openPopup();
          }
        })
        .catch(error => console.log('error', error));
      }
    }

    // handle map location error
    function handleLocationError(error) {
      let errorStr;
      switch (error.code) {
        case error.PERMISSION_DENIED:
          errorStr = mapContainer.dataset.errorDenied;
          break;
        case error.POSITION_UNAVAILABLE:
          errorStr = mapContainer.dataset.errorUnavailable;
          break;
        case error.TIMEOUT:
          errorStr = mapContainer.dataset.errorTimeout;
          break;
        case error.UNKNOWN_ERROR:
          errorStr = mapContainer.dataset.errorUnknown;
          break;
        default:
          errorStr = mapContainer.dataset.errorUnknown;
      }
      console.error('Error occurred: ' + errorStr);

      // show error
      mapErrorContainer.innerHTML = errorStr;
      mapErrorContainer.classList.remove('d-none');
    }

    var url = window.location.href;
    if (url.includes("notfallpass")) {
      new Modal("#annotation_modal", {
        backdrop: 'static',
        keyboard: false
      }).show();
    }

    window.showNextModal = () => {
      jQuery(".modal-backdrop").css("display", "none");
      jQuery("#annotation_modal").addClass("modal is-hidden is-visuallyHidden").removeClass("fade in").css("display", "none");
      new Modal("#further_annotation_modal", {
        backdrop: 'static',
        keyboard: false
      }).show();
    }

    window.route2StartPage = (url) => {
      window.location = url;
    }

    // init ajax event listener
    var initAjax = () => {

      // add listener for forms
      Array
        .from(ajaxForms)
        .forEach((el) => {
          let initialized = el.dataset.initialized || false

          // initalized?
          if(initialized) {
            return;
          }

          // add listener
          el.addEventListener(
            'submit',
            e => {
              let url = el.getAttribute('action');
              let options = {
                method: el.getAttribute('method') || 'POST',
                body: new FormData(el)
              };
              let table = el.dataset.table || null;

              // ajax
              ajax(url, options)
                .then(res => {

                  // close modal if open/advised
                  let modalInstance = Modal.getInstance(modalContainer);
                  if(modalInstance) {
                    modalInstance.hide();
                  }

                  // message?
                  if(res.message ?? false) {
                    showMessage(
                      res.severity || 0,
                      null,
                      res.message
                    );
                  }

                  // table refresh?
                  if(table) {
                    jQuery('#' + table)
                      .bootstrapTable(
                        'refresh',
                        {
                          silent: true
                        }
                      );
                    }
                })

                // catch
                .catch(err => {
                  console.warn(err);
                });

              e.preventDefault();

              // avoid submit
              return false;
            }, {
              once: true
            }
          );

          // set init
          el.dataset.initialized = true;
        });


      // add listener for modal buttons
      Array
        .from(ajaxModal)
        .forEach((el) => {
          let initialized = el.dataset.initialized || false

          // initalized?
          if(initialized) {
            return;
          }

          // listener
          el.addEventListener(
            'click',
            e => {
              showModal(el.dataset.url);
            }
          );

          // set init
          el.dataset.initialized = true;
        });


        // add listener for action buttons
        Array
          .from(ajaxAction)
          .forEach((el) => {
            let initialized = el.dataset.initialized || false
            let url = el.dataset.url;
            let table = el.dataset.table ?? false;
            let options = {
              method: el.dataset.method || 'GET'
            };

            // initalized?
            if(initialized) {
              return;
            }

            // listener
            el.addEventListener(
              'click',
              e => {
                ajax(url, options)
                  .then(res => {

                    // message?
                    if(res.message ?? false) {
                      showMessage(
                        res.severity || 0,
                        null,
                        res.message
                      );
                    }

                    // table refresh?
                    if(table) {
                      jQuery('#' + table)
                        .bootstrapTable(
                          'refresh',
                          {
                            silent: true
                          }
                        );
                      }
                  })

                  // catch
                  .catch(err => {
                    console.warn(err);
                  });
              }
            );

            // set init
            el.dataset.initialized = true;
          });


        // add listener for ajax check fields
        Array
          .from(ajaxValidate)
          .forEach((el) => {
            let initialized = el.dataset.initialized || false
            let url = el.dataset.url;
            let options = {
              method: el.dataset.method || 'GET'
            };

            // initalized?
            if(initialized) {
              return;
            }

            // listener
            el.addEventListener(
              'blur',
              e => {
                let field = e.target;
                let form = field.closest('form');
                let val = field.value.trim();
                let finalUrl = url + encodeURIComponent(val);

                ajax(finalUrl, options)
                  .then(res => {

                    // valid
                    if(typeof res.valid !== 'undefined') {
                      if(res.valid === true) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');

                      // invalid
                      } else {
                        field.classList.remove('is-valid');
                        field.classList.add('is-invalid');
                      }
                    }

                    // message?
                    if(res.message ?? false) {
                      showMessage(
                        res.severity || 0,
                        null,
                        res.message
                      );
                    }
                  })

                  // catch
                  .catch(err => {
                    field.classList.remove('is-valid');
                    field.classList.add('is-invalid');

                    // error?
                    if(err.message ?? false) {
                      showFieldError(field, err.message);
                    }
                  });
              }
            );

            // set init
            el.dataset.initialized = true;
          });

    }


    // init ajax event listener
    initAjax();


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
                showMap(lat, lon);
              },
              handleLocationError,
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
    }

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
          ajax(url, options)
          .then(
            res => {

              // icon
              iconContainer.classList = el.checked
                ? el.dataset.iconOn
                : el.dataset.iconOff;

              // enable
              el.disabled = false;

              // show message
              showMessage(
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