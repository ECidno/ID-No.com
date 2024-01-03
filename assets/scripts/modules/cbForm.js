import { Modal } from 'bootstrap';
import jQuery from 'jquery';
import { cbSuccessActions } from './cbAction';
import { cbAjax } from './cbAjax';
import { cbMessage } from './cbMessage';

export const name = 'cbForm';

/**
 * cloudbase | form
 *
 * @param object el
 */
const cbForm = (el) => {
  const modalContainer = document.getElementById('modalContainer') || false;
  const btnCollectionAdd = document.getElementsByClassName('add-collection-widget') || null;
  const btnCollectionRemove = document.getElementsByClassName('remove-collection-widget') || null;
  const ajaxValidate = document.getElementsByClassName('ajax-validate') || [];
  const initialized = el.dataset.initialized || false;

  // initalized?
  if(initialized) {
    return;
  }

  // button | collection add
  Array
    .from(btnCollectionAdd)
    .forEach((el) => {
      el.addEventListener(
        'click',
        e => {
          const form = el.closest('form');
          const list = document.getElementById(el.dataset.listSelector);
          var counter = list.dataset.widgetCounter || list.children().length;

          // grab the prototype template
          var newWidget = list.dataset.prototype;
          newWidget = newWidget.replace(/__name__/g, counter);
          counter++;

          // And store it, the length cannot be used if deleting widgets is allowed
          list.dataset.widgetCounter = counter;

          // create a new list element and add it to the list
          const item = jQuery(list.dataset.widgetTags)
            .html(newWidget);

          // add event to remove button
          item
            .find('button.remove-collection-widget')
            .get(0)
            .addEventListener(
              'click',
              (e) => {
                e.preventDefault();
                var counter = list.dataset.widgetCounter || list.children().length;

                // remove
                item.remove();
                counter--;
                list.dataset.widgetCounter = counter;

                // emit event
                if(form) {
                  form.dispatchEvent(
                    new CustomEvent(
                      'collection-remove-event',
                      {
                        bubbles: false,
                        cancelable: true,
                        detail: {
                          counter: counter
                        }
                      }
                    )
                  );
                }
            }
          );

          // apend
          item.appendTo(list);

          // emit event
          if(form) {
            form.dispatchEvent(
              new CustomEvent(
                'collection-add-event',
                {
                  bubbles: false,
                  cancelable: true,
                  detail: {
                    counter: counter
                  }
                }
              )
            );
          }
        }
      );
    });

  // button | collection remove
  Array
    .from(btnCollectionRemove)
    .forEach((el) => {
      el.addEventListener(
        'click',
        e => {
          const form = el.closest('form');
          const item = jQuery(el).closest('div.collection-item');
          const list = document.getElementById(el.dataset.listSelector);
          var counter = list.dataset.widgetCounter || list.children().length;

          // remove item
          if(item) {
            item.remove();
            counter--;
            list.dataset.widgetCounter = counter;
          }

          // emit event
          if(form) {
            form.dispatchEvent(
              new CustomEvent(
                'collection-remove-event',
                {
                  bubbles: false,
                  cancelable: true,
                  detail: {
                    counter: counter
                  }
                }
              )
            );
          }
        }
      );
    });

  // add listener for ajax check fields
  Array
    .from(ajaxValidate)
    .forEach((el) => {
      const initialized = el.dataset.initialized || false
      const url = el.dataset.url;
      const options = {
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
          const field = e.target;
          const form = field.closest('form');
          const id = parseInt(form.getAttribute('action').split('/').pop());
          const val = field.value.trim();
          const finalUrl = url + (isNaN(id) ? 0 : id) + '/' + encodeURIComponent(val);

          cbAjax(finalUrl, options)
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
                cbMessage(
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
              const error = err.message ?? err.errors ?? false;
              if(error) {
                showFieldError(field, error);
              }
            });
        }
      );

      // set init
      el.dataset.initialized = true;
    });


  // enable, disable action buttons
  const setActionButtonStatus = (el, disabled) => {
    const formButtons = el.querySelectorAll('button');
    Array
      .from(formButtons)
      .forEach((actionBtn) => {
        actionBtn.disabled = disabled;
      });
  }

  // show form field error
  const showFieldError = (field, message) => {
    const parent = field.parentNode;
    const errorContainer = document.createElement('div');

    Array
      .from(parent.getElementsByClassName('invalid-feedback') || [])
      .forEach((el) => {
        el.remove();
      });

    errorContainer.className = 'invalid-feedback';
    errorContainer.innerText = message;
    parent.append(errorContainer);
  }


  /*
   * event listener
   */
  el
    .addEventListener(
      'submit',
      e => {
        const modalInstance = Modal.getInstance(modalContainer) || false;
        const url = el.getAttribute('action');
        const options = {
          method: el.getAttribute('method') || 'POST',
          body: new FormData(el)
        };
        const table = el.dataset.table || null;

        // disable action buttons
        setActionButtonStatus(el, true);

        // ajax
        cbAjax(url, options)
          .then(res => {

            cbSuccessActions(res, el);

            // close modal if open/advised
            if(modalInstance) {
              modalInstance.hide();
            }
/*
            // message?
            if(res.message ?? false) {
              cbMessage(
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
              */

          })

          // catch
          .catch(err => {
            console.warn(err, err.message);

            /*
             * we might get field based errors as object
             * in err.cause
             *
             * If so, we try to determine the field and
             * display them.
             */
            if(err.cause && typeof err.cause === 'object') {
              Object.keys(err.cause).forEach((k, i) => {
                let fieldNamePart = k;
                let errors = err.cause[k];

                // if erros is object: key: filed name part, value: erorr array
                if(typeof errors === 'object') {
                  let key = Object.keys(errors)[0];
                  let field = el.querySelector('[id*="' + fieldNamePart + '_' + key + '"]');
                  let error = errors[key][0];

                  // show field error
                  if(field && error) {
                    field.classList.remove('is-valid');
                    field.classList.add('is-invalid');
                    showFieldError(field, error);
                  }
                }
              });
            }

            // message?
            if(err.message ?? false) {
              cbMessage(
                err.severity || 9,
                null,
                err.message
              );
            }

            // enable action buttons
            setActionButtonStatus(el, false);
          });

        // prevent default event
        e.preventDefault();

        // avoid submit
        return false;
      }
    );

  // set init
  el.dataset.initialized = true;
}

// export
export { cbForm }
