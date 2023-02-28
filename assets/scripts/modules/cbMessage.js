import { Toast } from 'bootstrap';

export const name = 'cbMessage';

/**
 * cloudbase | message
 *
 * @param int severity
 * @param string header
 * @param string body
 * @param boolean autohide
 */
export const cbMessage = (severity, header, body, autohide) => {
  const toastContainer = document.getElementById('toastContainer') || null;
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
