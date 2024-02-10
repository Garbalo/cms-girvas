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

export class PageUsersGroups {
  constructor(params = {}) {
    // ..
  }

  init() {
    let interactiveCreatePageButton = new Interactive('button');
    interactiveCreatePageButton.target.setLabel('Новая группа');
    interactiveCreatePageButton.target.setCallback(() => {
      window.location.href = `./userGroup`;
    });
    interactiveCreatePageButton.assembly();
  
    let interactiveContainerElement = document.querySelector('#E8548530785');
    interactiveContainerElement.append(interactiveCreatePageButton.target.element);

    let tableItemsUsersGroups = document.querySelectorAll('.table-users-groups__item');

    for (let tableItemUserGroup of tableItemsUsersGroups) {
      let userGroupID = tableItemUserGroup.getAttribute('data-user-group-id');
      let buttons = tableItemUserGroup.querySelectorAll('button[role]');

      for (let button of buttons) {
        button.addEventListener('click', (event) => {
          if (button.getAttribute('role') == 'user-group-edit') {
            window.location.href = `./userGroup/${userGroupID}`;
          }

          if (button.getAttribute('role') == 'user-group-remove') {
            let interactiveModal = new Interactive('modal', {title: "Удаление группы пользователей", content: "Вы действительно хотите удалить группу пользователей? Действие отменить будет нельзя."});
            interactiveModal.target.addButton('Удалить', () => {
              let formData = new FormData();
              formData.append('user_group_id', userGroupID);

              fetch('/handler/usersGroup/' + userGroupID, {
                method: 'DELETE',
                body: formData
              }).then((response) => {
                return response.json();
              }).then((data) => {
                if (data.statusCode == 1) {
                  window.location.href = '/admin/usersGroups';
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