/*
 * bootstrap table formatter
 */
window.formatter = {

  // general
  general: {

    // yesNo
    yesNo: (value, row, index, field) => {
      return '<span class="badge '
        + (
            value
              ? 'bg-success'
              : 'bg-danger'
          )
        + '">'
        + (
            value
              ? 'Ja'
              : 'Nein'
          )
        + '</span>'
    },

    // gender
    gender: (value, row, index, field) => {
      switch (value) {
        case 'm':
          return '<i class="bi bi-gender-male"></i>';
        case 'f':
          return '<i class="bi bi-gender-female"></i>';
        case 'x':
          return '<i class="bi bi-gender-ambiguous"></i>';
        default:
          return '<i class="bi bi-gender-trans"></i>';
      }
    },
  },

  // item
  item: {

    // idno
    idno: (value, row, index, field) => {
      return '<a href="/' + value + '" target="_blank">' + value + '</a>';
    },

    status: (value, row, index) => {
      const table = document.getElementById('tableItems') || null;
      return row.status === true
        ? '<span><i class="bi bi-check-circle text-success me-1"></i><span class="d-none d-md-inline">' + table.dataset.llActive + '</span></span>'
        : '<span><i class="bi bi-exclamation-triangle text-warning me-1"></i><span class="d-none d-md-inline">' + table.dataset.llInactive + '</span></span>';
    },
  }
}
