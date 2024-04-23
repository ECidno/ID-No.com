export const name = 'cbCategorySelection';

const cbCategorySelection = (el) => {
  document.querySelectorAll('.add-item-widget')
    .forEach(btn => {
      btn.addEventListener("click", addItemToCollection);
    });
  document.querySelectorAll('button.remove-item-widget')
    .forEach(item => {
      item.addEventListener(
        'click',
        (e) => {
          e.preventDefault();
          item.closest('fieldset').remove();
        })
      });
  document.querySelectorAll('.condition-category-select')
    .forEach( sel => {
      initCategoryValues(sel);
    });
  document.querySelectorAll('.condition-category-select')
    .forEach(sel => {
      sel.addEventListener("change", displayCategoryValues);
    });

  /**
   * add item to notfallpass entry collection
   * 
   * @param event 
   */
  function addItemToCollection(event) {
    const collectionHolder = document.getElementById(event.currentTarget.dataset.listSelector);

    const item = document.createElement('li');
    item.innerHTML = collectionHolder
      .dataset
      .prototype
      .replace(
        /__name__/g,
        collectionHolder.dataset.index
      );
  
    // add event to remove button
    item
      .querySelector('button.remove-item-widget')
      .addEventListener(
        'click',
        (e) => {
          e.preventDefault();
          item.remove();
      }
    );

    // add event to condition select
    item.querySelectorAll('.condition-category-select')
      .forEach( sel => {
        initCategoryValues(sel);
      });
    item
      .querySelectorAll('.condition-category-select')
      .forEach(sel => {
          sel.addEventListener("change", displayCategoryValues)
      });


    collectionHolder.appendChild(item);
  
    collectionHolder.dataset.index++;
  }
  
  /**
   * notfallpass conditions: set displayed values when selecting category
   */
  function displayCategoryValues(event) {
    const categorySelect = event.target;
    const category = categorySelect.options[categorySelect.selectedIndex].dataset.category;
    const titleSelect = categorySelect.closest('fieldset').querySelector('.condition-title-select');
    if (category) {
      titleSelect.querySelectorAll('option').forEach((li) => {
        li.classList.add('d-none');
      });
      titleSelect.querySelectorAll('option.'+category).forEach((li) => {
        li.classList.remove('d-none');
      });
      titleSelect.querySelectorAll('option.all').forEach((li) => {
        li.classList.remove('d-none');
      });
      // select first element of selected category
      titleSelect.querySelector('option.'+category).selected = true;
    }
  }

  /**
   * notfallpass conditions: set displayed values on modal load
   */
  function initCategoryValues(element) {
    const category = element.options[element.selectedIndex].dataset.category;
    const titleSelect = element.closest('fieldset').querySelector('.condition-title-select');
    if (category) {
      titleSelect.querySelectorAll('option').forEach((li) => {
        li.classList.add('d-none');
      });
      titleSelect.querySelectorAll('option.'+category).forEach((li) => {
        li.classList.remove('d-none');
      });
    }
  }
}

// export
export { cbCategorySelection }