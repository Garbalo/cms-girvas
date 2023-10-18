'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let interactiveButton = new Interactive('button');
  interactiveButton.target.setLabel('Новая запись');
  interactiveButton.target.setCallback(() => {
    window.location.href = `./entry`;
  });
  interactiveButton.target.assembly();

  let interactiveContainerElement = document.querySelector('#E8548530785');
  interactiveContainerElement.append(interactiveButton.target.assembled);

  let tableItemsEntries = document.querySelectorAll('.table-entries__item');
  for (let tableItemEntry of tableItemsEntries) {
    let entryID = tableItemEntry.getAttribute('data-entry-id');
    let buttons = tableItemEntry.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'entry-edit') {
          window.location.href = `./entry/${entryID}`;
        }
      });
    }
  }
});