'use strict';

import { cbAjax } from './cbAjax';
import { cbMessage } from './cbMessage';

export const name = 'cbUpload';

/**
 * cloudbase | upload
 */
class cbUpload {

  /**
   * cconstructor
   *
   * @param object file
   * @param element form
   * @param function done
   * @param function fail
   * @param function always
   */
  constructor(file, form, done, fail, always) {
    this.file = file;
    this.form = form;

    this.done = done;
    this.fail = fail;
    this.always = always;
  }
};

// type
cbUpload.prototype.getType = function () {
  return this.file.type;
};

// size
cbUpload.prototype.getSize = function () {
  return this.file.size;
};

// name
cbUpload.prototype.getName = function () {
  return this.file.name;
};

// do upload
cbUpload.prototype.doUpload = function () {
  const self = this;
  const form = self.form;
  const field = form.querySelector('input[type="file"]');
  const done = self.done;
  const fail = self.fail;
  const always = self.always;
  const url = form.getAttribute('action');
  const formData = new FormData();
  const progressContainer = document.getElementById(form.dataset.progressContainer) || false;

  // add assoc key values, this will be posts values
  formData.append('file', self.file, this.getName());
  formData.append('upload_file', true);

  // add form fields
  Array
    .from(form.querySelectorAll('input:not(input[type="file"])'))
    .forEach((el) => {

      // append
      formData.append(
        el.getAttribute('name'),
        el.value
      );
    });

  // disable file field
  field.disabled = true;

  // progress
  if(progressContainer) {
    self.progressHandling(
      {
        loaded: 0,
        total: 1
      },
      progressContainer
    );
    progressContainer.style.display = 'block';
  }


  // ajax setup
  const ajaxOptions = {
    method: 'POST',
    url: url,
    body: formData,
    xhr: () => {
      let myXhr = new window.XMLHttpRequest();

      if (myXhr.upload && progressContainer) {
        myXhr.upload
          .addEventListener(
            'progress',
            function(ev) {
              self.progressHandling(ev, progressContainer);
            },
            false
          );
      }
      return myXhr;
    }
  };

  // ajax call
  cbAjax(url, ajaxOptions)
    .then(res => {

      // done
      done(res);

      // hide progress
      if (progressContainer) {
        progressContainer.style.display = 'none';
      }

      // enable file field
      field.disabled = false;

      // callback
      if (always && typeof (always) === 'function') {
        always(res);
      }
    })

    // catch
    .catch(err => {
      let error = err.message ?? err.errors ?? false;
      if (fail && typeof (fail) === 'function') {
        fail(error);

      // default
      } else {

        // hide progress
        if (progressContainer) {
          progressContainer.style.display = 'none';
        }

        // enable file field
        field.disabled = false;

        // message?
        if(error ?? false) {
          cbMessage(
            9,
            null,
            error
          );
        }
      }
    });
};

// process handling
cbUpload.prototype.progressHandling = (ev, container) => {
  let percent = 0;
  let position = ev.loaded || ev.position;
  let total = ev.total;
  let bar = container.querySelector('.progress-bar') || null;
  let status = container.querySelector('.status') || null;

  if (ev.lengthComputable) {
    percent = Math.ceil(position / total * 100);
  }

  // update progressbars classes so it fits your code
  if (bar) {
    bar.style.width = percent + '%';
  }
  if (status) {
    status.style.innerText = percent + '%';
  }
};


// export
export { cbUpload };
