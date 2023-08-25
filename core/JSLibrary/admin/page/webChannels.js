'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItems = document.querySelectorAll('.table-web-channels__item');
  for (let tableItem of tableItems) {
    let webChannelID = tableItem.getAttribute('data-web-channel-id');
    let buttons = tableItem.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'web-channel-edit') {
          window.location.href = `./webChannel/${webChannelID}`;
        }
      });
    }
  }
});