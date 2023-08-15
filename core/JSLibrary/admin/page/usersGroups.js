'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItemsUsersGroups = document.querySelectorAll('.table-users-groups__item');
  for (let tableItemUserGroup of tableItemsUsersGroups) {
    let userGroupID = tableItemUserGroup.getAttribute('data-user-group-id');
    let buttons = tableItemUserGroup.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'user-group-edit') {
          window.location.href = `./userGroup/${userGroupID}`;
        }
      });
    }
  }
});