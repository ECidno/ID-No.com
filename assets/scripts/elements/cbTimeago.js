import { render, cancel, register } from 'timeago.js';
import de from 'timeago.js/lib/lang/de.js';

// cbTimeago
class cbTimeago extends HTMLElement {

  /*
   * constructor
   */
  constructor() {
    super();

    // attributes
    this.autoUpdate = this.getAttribute('auto-update') || 3;
    this.locale = this.getAttribute('locale') || 'en_US';
  }

  // connected callback
  connectedCallback() {
    this.init(this);
  }

  // disconnected callback
  disconnectedCallback() {
    cancel();
  }


  // init
  init(el) {
    register('de', de);
    render(el, this.locale, { minInterval:this.autoUpdate });
  }
}

window.customElements.define('cb-timeago', cbTimeago);
