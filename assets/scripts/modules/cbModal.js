import { Modal } from 'bootstrap';
import { cbAjax } from './cbAjax';
import { cbMessage } from './cbMessage';

export const name = 'cbModal';

/**
 * cloudbase | modal
 *
 * @param string uri
 * @param string body
 * @param boolean autohide
 */
const cbModal = (uri, options, autohide) => {
  const modalContainer = document.getElementById('modalContainer') || null;

  // options
  options = options ?? {
    method: 'GET',
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  };

  // ajax
  cbAjax(uri, options)
    .then(res => {
      modalContainer.innerHTML = res.html;
      const modal = Modal
        .getOrCreateInstance(
          modalContainer,
          {
            backdrop:'static'
          }
        )
        .show();

      // emit event if fully opened
      modalContainer.addEventListener(
        'shown.bs.modal',
        e => {
          window.dispatchEvent(
            new CustomEvent(
              'cb-modal.open',
              {
                detail: modal
              }
            )
          );
        }
      );
    })

    // catch
    .catch((err) => {
      cbMessage(
        err.severity || 9,
        null,
        err.message
      );
    });
}


/**
 * cloudbase | modal buttons
 *
 * @param object el
 */
const cbModalButton = (el) => {
  let initialized = el.dataset.initialized || false

  // initalized?
  if(initialized) {
    return;
  }

  // listener
  el.addEventListener(
    'click',
    e => {
      cbModal(el.dataset.url);
    }
  );

  // set init
  el.dataset.initialized = true;
}

// export
export { cbModal, cbModalButton }
