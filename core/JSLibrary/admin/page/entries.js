'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItemsEntries = document.querySelectorAll('.table-entries__item');
  for (let tableItemEntry of tableItemsEntries) {
    let entryID = tableItemEntry.getAttribute('data-entry-id');
    let buttons = tableItemEntry.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'entry-edit') {
          window.location.href = `./entry/${entryID}`;
        }

        if (button.getAttribute('role') == 'entry-remove') {
          console.log('Вызов модального окна');
        }
      });
    }
  }
});