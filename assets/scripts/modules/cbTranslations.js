import IntlMessageFormat from 'intl-messageformat';

var LOCALE = document.getElementsByTagName("html")[0].getAttribute('data-locale');

var missingTranslations = {};
var existingTranslations = {};
var initialized = false;

function localStorageAvailable() {
  return 'localStorage' in global && global.localStorage;
}

function addMissingTranslation(key, domain) {
  if (!missingTranslations.hasOwnProperty(LOCALE + domain + key) && localStorageAvailable() && global.Cb && global.Cb.debug === true) {
    missingTranslations[LOCALE + domain + key] = {
      id: key,
      translation: key,
      locale: LOCALE,
      domain: domain
    };
    global.localStorage.setItem('missingTranslations', JSON.stringify(missingTranslations));
  }
}

function addExistingTranslation(key, domain, translation) {
  if (!existingTranslations.hasOwnProperty(LOCALE + domain + key) && localStorageAvailable() && global.Cb && global.Cb.debug === true) {
    existingTranslations[LOCALE + domain + key] = {
      id: key,
      translation: translation,
      locale: LOCALE,
      domain: domain
    };
    global.localStorage.setItem('existingTranslations', JSON.stringify(existingTranslations));
  }
}

function initialize() {
  if (localStorageAvailable() && global.Cb && global.Cb.debug === true) {
    global.localStorage.setItem('missingTranslations', '[]');
    global.localStorage.setItem('existingTranslations', '[]');
  } else {
    global.localStorage.removeItem('missingTranslations');
    global.localStorage.removeItem('existingTranslations');
  }
  initialized = true;
}

function translate(key, options) {
  if (!key) return '';
  key = key.toString();
  options = options || {};
  options.domain = options.domain || 'messages';

  if (!TRANSLATIONS[options.domain] || !TRANSLATIONS[options.domain][key] || !TRANSLATIONS[options.domain][key].length) {
    addMissingTranslation(key, options.domain);
    return key;
  }

  let msg = new IntlMessageFormat(
    TRANSLATIONS[options.domain][key],
    LOCALE
  );
  let result = msg.format(options.parameters || {});

  addExistingTranslation(key, options.domain, result);
  return result;
}

initialize();

export default {
  'translate' : translate
};
