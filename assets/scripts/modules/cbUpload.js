'use strict';

import { Alert } from 'bootstrap';
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
   * @param element el
   * @param function done
   * @param function fail
   * @param function always
   */
  constructor(file, el, done, fail, always) {
    this.file = file;
    this.el = el;

    this.done = done;
    this.fail = fail;
    this.always = always;

    this.progressContainer = document.getElementById(el.dataset.progressContainer) || false;
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


// progress
cbUpload.prototype.showProgressItem = function () {
  let progressItem = null;

  if(this.progressContainer) {
    const progressTemplate = this.progressContainer.querySelector('progressItem');
    progressItem = progressTemplate.cloneNode(true);

    const fileIcon = progressItem.querySelector('.fileicon');
    const fileName = this.getName();
    const fileExtension = fileName
      .slice((Math.max(0, fileName.lastIndexOf(".")) || Infinity) + 1)
      .toLowerCase()
      .replace('jpeg', 'jpg');
    const fileTypes = [
      'aac',
      'ai',
      'bmp',
      'cs',
      'css',
      'csv',
      'doc',
      'docx',
      'exe',
      'gif',
      'heic',
      'html',
      'java',
      'jpg',
      'js',
      'json',
      'jsx',
      'key',
      'm4p',
      'md',
      'mdx',
      'mov',
      'mp3',
      'mp4',
      'otf',
      'pdf',
      'php',
      'png',
      'ppt',
      'pptx',
      'psd',
      'py',
      'raw',
      'rb',
      'sass',
      'scss',
      'sh',
      'sql',
      'svg',
      'tiff',
      'tsx',
      'ttf',
    ];

    if(fileTypes.indexOf(fileExtension) > -1) {
      fileIcon.classList.remove('bi-file-earmark');
      fileIcon.classList.add('bi-filetype-' + fileExtension);
    }

    progressItem.querySelector('.filename').innerText = fileName;
    progressItem.classList.remove('d-none');
    progressItem.style.display = 'block';

    // append
    this.progressContainer.append(progressItem);
  }

  // return
  return progressItem;
};


// do upload
cbUpload.prototype.doUpload = function () {
  const self = this;
  const el = self.el;
  const field = el.querySelector('input[type="file"]');
  const done = self.done;
  const fail = self.fail;
  const always = self.always;
  const url = el.dataset.action || null;
  const formData = new FormData();
  const progressContainer = document.getElementById(el.dataset.progressContainer) || false;
  let progressItem = null;

  // add assoc key values, this will be posts values
  formData.append('file', self.file, this.getName());
  formData.append('upload_file', true);

  // add form fields
  Array
    .from(el.querySelectorAll('input:not(input[type="file"])'))
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

    // progress
    progressItem = self.showProgressItem();
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

      // progress
      if (progressItem) {
        self.progressHandling(
          {
            loaded: self.getSize(),
            total: self.getSize()
          },
          progressItem,
          false
        )
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

        // progress
        if (progressContainer) {
          self.progressHandling(
            {
              loaded: self.getSize(),
              total: self.getSize()
            },
            progressItem,
            error
          )
  //        progressContainer.style.display = 'none';
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
cbUpload.prototype.progressHandling = (ev, container, error) => {
  const item = container.querySelector('div.alert')
  const alert = Alert.getOrCreateInstance(item);
  const position = ev.loaded || ev.position;
  const total = ev.total;
  const bar = item.querySelector('.progress-bar') || null;
  const status = item.querySelector('.status') || null;
  const percent = Math.ceil(position / total * 100);

  // upload finished | success
  if(percent === 100 && bar && !error) {
    bar.classList.remove('progress-bar-striped', 'progress-bar-animated');
    window.setTimeout(() => { alert.close(); }, 2000);
  }

  // update progressbars classes so it fits your code
  if (bar) {
    bar.style.width = percent + '%';
  }
  if (status) {
    status.innerText = percent + '%';
  }

  // upload finished | error
  if(error) {
    item.classList.remove('border-success');
    item.classList.add('border-danger');

    bar.classList.remove('bg-success', 'progress-bar-striped', 'progress-bar-animated');
    bar.classList.add('bg-danger');
    status.innerText = error;
  }
};


// export
export { cbUpload };
