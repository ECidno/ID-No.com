
// const's
const fldIdNo = document.getElementById('fldIdNo');

// input pattern on field idno
fldIdNo.addEventListener(
	'keyup',
	(e) => {
    if(e.key === 'Backspace' || e.key === 'Delete') {
			return;
		}

		// pattern xxxx-yyyy
		if(e.target.value.length === 4) {
        fldIdNo.value += '-';
    }
	}
);