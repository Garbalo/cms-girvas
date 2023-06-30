'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItemsPagesStatic = document.querySelectorAll('.table-pages-static__item');
  for (let tableItemPageStatic of tableItemsPagesStatic) {
    let pageStaticID = tableItemPageStatic.getAttribute('data-page-static-id');
    let buttons = tableItemPageStatic.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'page-static-edit') {
          window.location.href = `./page/${pageStaticID}`;
        }
      });
    }
  }
});