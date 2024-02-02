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

export class PageWebChannels {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null};
  }

  init() {
    let interactiveCreatePageButton = new Interactive('button');
    interactiveCreatePageButton.target.setLabel('Новый веб-канал');
    interactiveCreatePageButton.target.setCallback(() => {
      window.location.href = `./webChannel`;
    });
    interactiveCreatePageButton.assembly();
  
    let interactiveContainerElement = document.querySelector('#E8548530785');
    interactiveContainerElement.append(interactiveCreatePageButton.target.element);

    let tableItems = document.querySelectorAll('.table-web-channels__item');

    for (let tableItem of tableItems) {
      let webChannelID = tableItem.getAttribute('data-web-channel-id');
      let buttons = tableItem.querySelectorAll('button[role]');
      
      for (let button of buttons) {
        button.addEventListener('click', (event) => {
          if (button.getAttribute('role') == 'web-channel-edit') {
            window.location.href = `./webChannel/${webChannelID}`;
          }

          if (button.getAttribute('role') == 'web-channel-remove') {
            let interactiveModal = new Interactive('modal', {title: "Удаление группы веб-канал", content: "Вы действительно хотите удалить веб-канал? Действие отменить будет нельзя."});
            interactiveModal.target.addButton('Удалить', () => {
              let formData = new FormData();
              formData.append('web_channel_id', webChannelID);

              fetch('/handler/webChannel/' + webChannelID, {
                method: 'DELETE',
                body: formData
              }).then((response) => {
                return response.json();
              }).then((data) => {
                if (data.statusCode == 1) {
                  window.location.href = '/admin/webChannels';
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