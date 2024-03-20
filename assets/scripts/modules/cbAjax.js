export const name = 'cbAjax';

/**
 * cloudbase | ajax
 *
 * @param string uri
 * @param object options
 *
 * @return Promise
 */
const cbAjax = async (uri, options) => {
  options = options ?? {
    method: 'GET',
  };

  // headers
  options.headers = options.headers ?? {
    'X-Requested-With': 'XMLHttpRequest'
  };

  // set X-Requested-With header
  if(!('X-Requested-With' in options.headers)) {
    options.headers['X-Requested-With'] = 'XMLHttpRequest';
  }

  // return promise
  const res = await fetch(
    uri,
    options
  );

  if (res.ok) {
    return res.json();
  }

  const err = await res.json();

  throw new Error(err.message ?? err.error, {
    cause: err.errors ?? {}
  });
}

// export
export { cbAjax }
