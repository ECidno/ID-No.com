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
