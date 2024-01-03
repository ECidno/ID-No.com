import jQuery from 'jquery';
import { cbAjax } from './cbAjax';
import { cbMessage } from './cbMessage';
import { cbModalButton } from './cbModal';
import { cbOffcanvasButton } from './cbOffcanvas';

export const name = 'cbAction';

/**
 * cloudbase | action
 *
 * @param object el
 */
const cbAction = (el) => {
  let initialized = el.dataset.initialized || false
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
    'click',
    e => {
      cbAjax(url, options)
        .then(res => {
          cbSuccessActions(res, el);
        })

        // catch
        .catch(err => {
          console.warn(err);
        });
    }
  );

  // set init
  el.dataset.initialized = true;
}


/**
 * cloudbase | success actions
 *
 * @param object response
 * @param object el
 *
 * @return mixed
 */
const cbSuccessActions = (response, el) => {
  const dataset = el.dataset;
  const bsTable = el.dataset.bsTable ?? false;
  const table = dataset.table || false;
  const target = el.dataset.target || false;

  // message?
  if(response.message || false) {
    cbMessage(
      response.severity || 0,
      null,
      response.message
    );
  }

  // html?
  if((response.html || false) && (response.target || target)) {
    document.querySelector(response.target || target).innerHTML = response.html;
  }

  // redirect?
  if (response.redirect || false) {
    window.location.href = response.redirect;
  }

  // table refresh?
  if(table) {
    const tableEl = document.querySelector(table) ?? false;
    const tableUrl = tableEl
      ? tableEl.dataset.url
      : false;
    const options = {
      method: el.dataset.method || 'GET'
    };

    // url?
    if(tableUrl, options) {
      cbAjax(tableUrl)
        .then(res => {
          tableEl.innerHTML = res.html;

          // add listener for action buttons
          Array
            .from(document.getElementsByClassName('ajax-action') || [])
            .forEach((el) => {
              cbAction(el);
            });

          // add listener for modal buttons
          Array
            .from(document.getElementsByClassName('ajax-modal') || [])
            .forEach((el) => {
              if((el.dataset.renderMode || 'modal') === 'modal') {
                cbModalButton(el);
              } else {
                cbOffcanvasButton(el);
              }
            });

        })

        // catch
        .catch(err => {
          console.warn(err);
        });
    }
  }

  // bs table refresh?
  if(bsTable) {
    jQuery('#' + bsTable)
      .bootstrapTable(
        'refresh',
        {
          silent: true
        }
      );
    }
}


// export
export { cbAction, cbSuccessActions }
