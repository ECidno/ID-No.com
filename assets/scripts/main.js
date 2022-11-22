'use strict';

import { Modal, Toast } from 'bootstrap';
import jQuery from 'jquery';
import bootstrapTable from 'bootstrap-table';
import bootstrapTableLocaleAll from 'bootstrap-table/dist/bootstrap-table-locale-all';

// const's
const btnToTop = document.getElementById('toTop') || null;

const toastContainer = document.getElementById('toastContainer') || null;
const modalContainer = document.getElementById('modalContainer') || null;

const ajaxForms = document.getElementsByClassName('ajax-form') || [];
const ajaxModal = document.getElementsByClassName('ajax-modal') || [];
const ajaxAction = document.getElementsByClassName('ajax-action') || [];
const ajaxValidate = document.getElementsByClassName('ajax-validate') || [];
const fldIdNo = document.getElementsByClassName('idNo') || [];
const fldPassEnable = document.getElementById('fldPassEnable') || null;

var showModal;


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
    .forEach((key) => {
      let val = row.operations[key];
      operations.push(
        [
          '<button',
          'type="button"',
          'class="btn btn-sm btn-outline-dark ms-2 ' + key + '"',
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
    ? '<span><i class="bi bi-check-circle text-success me-1"></i> ' + table.data('llActive') + '</span>'
    : '<span><i class="bi bi-exclamation-triangle text-warning me-1"></i>' + table.data('llInactive') + '</span>';
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
            throw new Error(err.message)
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
              let table = el.getAttribute('data-table') || null;

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
            let url = el.getAttribute('data-url');
            let table = el.getAttribute('data-table') ?? false;
            let options = {
              method: el.getAttribute('data-method') || 'GET'
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
            let url = el.getAttribute('data-url');
            let options = {
              method: el.getAttribute('data-method') || 'GET'
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
                let val = field.value.trim();
                let finalUrl = url + encodeURIComponent(val);

                ajax(finalUrl, options)
                  .then(res => {

                    // valid
                    if(typeof res.valid !== 'undefined') {
                      if(res.valid === true) {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
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
          let form = el.closest('form');
          let url = form.getAttribute('action');
          let options = {
            method: form.getAttribute('method') || 'POST',
            body: new FormData(form)
          };

          // ajax
          ajax(url, options)
          .then(
            res => {

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