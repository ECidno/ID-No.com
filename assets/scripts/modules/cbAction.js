import jQuery from 'jquery';
import { cbAjax } from './cbAjax';
import { cbMessage } from './cbMessage';

export const name = 'cbAction';

/**
 * cloudbase | action
 *
 * @param object el
 */
const cbAction = (el) => {
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
      cbAjax(url, options)
        .then(res => {

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

// export
export { cbAction }
