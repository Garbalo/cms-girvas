/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

export class PageEntries {
  constructor(params = {}) {
    
  }

  init() {
    let interactiveCreatePageButton = new Interactive('button');
    interactiveCreatePageButton.target.setLabel('Новая запись');
    interactiveCreatePageButton.target.setCallback(() => {
      window.location.href = `./entry`;
    });
    interactiveCreatePageButton.assembly();
  
    let interactiveContainerElement = document.querySelector('#E8548530785');
    interactiveContainerElement.append(interactiveCreatePageButton.target.element);

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
            let interactiveModal = new Interactive('modal', {title: "Удаление записи", content: "Вы действительно хотите удалить запись? Действие отменить будет нельзя."});
            interactiveModal.target.addButton('Удалить', () => {
              let formData = new FormData();
              formData.append('entry_id', entryID);

              fetch('/handler/entry/' + entryID, {
                method: 'DELETE',
                body: formData
              }).then((response) => {
                return response.json();
              }).then((data) => {
                if (data.statusCode == 1) {
                  window.location.href = '/admin/entries';
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
  }
}