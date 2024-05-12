/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../../interactive.class.js";
import {URLParser} from "../../../urlParser.class.js";

export class PageUsers {
  constructor(params = {}) {
    // ..
  }

  init() {
    let searchParams = new URLParser(), locales;
    
    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {
      let interactiveCreatePageButton = new Interactive('button');
      
      interactiveCreatePageButton.target.setLabel(localeData.BUTTON_NEW_USER_LABEL);
      interactiveCreatePageButton.target.setCallback(() => {
        window.location.href = `./user`;
      });
      interactiveCreatePageButton.assembly();
    
      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveCreatePageButton.target.element);

      let tableItemsUsers = document.querySelectorAll('.table-users__item');
      
      for (let tableItemUser of tableItemsUsers) {
        let userID = tableItemUser.getAttribute('data-user-id');
        let buttons = tableItemUser.querySelectorAll('button[role]');

        for (let button of buttons) {
          button.addEventListener('click', (event) => {
            if (button.getAttribute('role') == 'user-edit') {
              window.location.href = `./user/${userID}`;
            }

            if (button.getAttribute('role') == 'user-remove') {
              let interactiveModal = new Interactive('modal', {
                title: localeData.MODAL_USER_DELETE_TITLE,
                content: localeData.MODAL_USER_DELETE_DESCRIPTION
              });
              
              interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
                let formData = new FormData();
                formData.append('user_id', userID);

                fetch('/handler/user/' + userID + '?localeMessage=' + window.CMSCore.locales.admin.name, {
                  method: 'DELETE',
                  body: formData
                }).then((response) => {
                  return response.json();
                }).then((data) => {
                  if (data.statusCode == 1) {
                    window.location.href = '/admin/users';
                  }

                  let notification = new PopupNotification(data.message, document.body, true);
                  notification.show();
                });
              });

              interactiveModal.target.addButton(localeData.BUTTON_CANCEL_LABEL, () => {
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
  }
}