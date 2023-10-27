'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let interactiveButton = new Interactive('button');
  interactiveButton.target.setLabel('Новая категория');
  interactiveButton.target.setCallback(() => {
    window.location.href = `./entriesCategory`;
  });
  interactiveButton.assembly();

  let interactiveContainerElement = document.querySelector('#E8548530785');
  interactiveContainerElement.append(interactiveButton.target.element);

  let tableItems = document.querySelectorAll('.table-entries-categories__item');
  for (let tableItem of tableItems) {
    let entriesCategoryID = tableItem.getAttribute('data-entries-category-id');
    let buttons = tableItem.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'entries-category-edit') {
          window.location.href = `./entriesCategory/${entriesCategoryID}`;
        }

        if (button.getAttribute('role') == 'entries-category-delete') {
          let interactiveModal = new Interactive('modal', {title: "Удаление категории записей", content: "Вы действительно хотите удалить категорию записей? Действие отменить будет нельзя."});
          interactiveModal.target.addButton('Удалить', () => {
            let formData = new FormData();
            formData.append('entries_category_id', entriesCategoryID);

            fetch('/handler/entry/category', {
              method: 'DELETE',
              body: formData
            }).then((response) => {
              return response.json();
            }).then((data) => {
              interactiveModal.target.close();
    
              if (data.statusCode == 1) {
                tableItem.remove();
                window.location.reload();
              }
    
              let notification = new PopupNotification(data.message, document.body, true);
              notification.show();
            });
          });

          interactiveModal.target.addButton('Отмена', () => {
            interactiveModal.target.close();
          });

          interactiveModal.assembly();
          document.body.appendChild(interactiveModal.target.element);
          interactiveModal.target.show();
        }
      });
    }
  }
});