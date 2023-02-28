import { cbMessage } from '../modules/cbMessage';
import { cbModal } from '../modules/cbModal';
import { cbOffcanvas } from '../modules/cbOffcanvas';

/*
 * bootstrap table events
 */
window.table = {
  events: {

    // default
    default: {

      'click button': (e, value, row, index) => {
        let operation = e.delegateTarget.dataset.operation || null;
        let wrapper = e.delegateTarget.dataset.wrapper || 'modal';
        let uri = row.operations[operation].uri || null;

        if(uri) {
          if(wrapper === 'offcanvas') {
            cbOffcanvas(uri);
          } else {
            cbModal(uri);
          }

        // show message
        } else {
          cbMessage(
            2,
            'Error',
            'No url nor operation found!'
          );
        }
      }
    }
  }
}