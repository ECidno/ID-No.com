import { Offcanvas } from 'bootstrap';
import { cbAjax } from './cbAjax';
import { cbMessage } from './cbMessage';

export const name = 'cbOffcanvas';

/**
 * cloudbase | offcanvas
 *
 * @param string uri
 * @param string body
 * @param boolean autohide
 */
const cbOffcanvas = (uri, options, autohide) => {
  const offcanvasContainer = document.getElementById('offcanvasContainer') || null;

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
      offcanvasContainer.innerHTML = res.html;
      const offcanvas = Offcanvas
        .getOrCreateInstance(
          offcanvasContainer,
          {
            backdrop:'static',
            keyboard: true,
            scroll: true
          }
        )
        .show();

      // emit event if fully opened
      offcanvasContainer.addEventListener(
        'shown.bs.offcanvas',
        e => {
          window.dispatchEvent(
            new CustomEvent(
              'cb-modal.open',
              {
                detail: offcanvas
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
 * cloudbase | offcanvas buttons
 *
 * @param object el
 */
const cbOffcanvasButton = (el) => {
  let initialized = el.dataset.initialized || false

  // initalized?
  if(initialized) {
    return;
  }

  // listener
  el.addEventListener(
    'click',
    e => {
      cbOffcanvas(el.dataset.url);
    }
  );

  // set init
  el.dataset.initialized = true;
}

// export
export { cbOffcanvas, cbOffcanvasButton }
