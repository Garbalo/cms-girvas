'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItemsUsers = document.querySelectorAll('.table-users__item');
  for (let tableItemUser of tableItemsUsers) {
    let userID = tableItemUser.getAttribute('data-user-id');
    let buttons = tableItemUser.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'user-edit') {
          window.location.href = `./user/${userID}`;
        }
      });
    }
  }
});