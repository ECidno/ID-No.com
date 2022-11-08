'use strict';

import { Modal, Toast } from 'bootstrap';

// const's
const toastContainer = document.getElementById('toastContainer') || null;
const modalContainer = document.getElementById('modalContainer') || null;

const ajaxForms = document.getElementsByClassName('ajax-form') || [];
const ajaxBtn = document.querySelectorAll('button.ajax-modal') || [];
const fldIdNo = document.getElementById('fldIdNo') || null;
const fldPassEnable = document.getElementById('fldPassEnable') || null;


// document DOM ready
document.addEventListener(
  'DOMContentLoaded',
  function() {

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



    // init ajax event listener
    var initAjax = () => {

      // add listener for forms
      Array
        .from(ajaxForms)
        .forEach((el) => {
          let initialized = el.dataset.initialized || false
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

              ajax(url, options)
              .then(res => {
    console.log(res);

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

              })
              .catch(err => {

    console.warn(err);
              });

              e.preventDefault();

              // avoid
              return false;
            }, {
              once: true
            }
          );

          // set init
          el.dataset.initialized = true;
        });


      // add listener for buttons
      ajaxBtn.forEach((el) => {
        let initialized = el.dataset.initialized || false
        if(initialized) {
          return;
        }

        // listener
        el.addEventListener(
          'click',
          e => {
//            let url = el.getAttribute('data-url');
            let url = el.dataset.url;
            let options = {
              method: 'GET'
            };

            // ajax
            ajax(url, options)
              .then(res => {
                modalContainer.innerHTML = res.html;
                new Modal(
                  modalContainer,
                  {
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

    // fldIdNo | keyup | input pattern on field idno
    if(fldIdNo) {
      fldIdNo.addEventListener(
        'keyup',
        e => {
          if(e.key === 'Backspace' || e.key === 'Delete') {
            return;
          }

          // pattern xxxx-yyyy
          if(e.target.value.length === 4) {
            fldIdNo.value += '-';
          }
        }
      );
    }

    // fldPassEnable | change
    if(fldPassEnable) {
      fldPassEnable.addEventListener(
        'change',
        e => {
/*
          e.target
            .closest('form')
            .dispatchEvent(new Event('submit'));
            return false;
*/

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