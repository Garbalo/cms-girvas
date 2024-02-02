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

export class PagePages {
  constructor(params = {}) {
    
  }

  init() {
    let interactiveCreatePageButton = new Interactive('button');
    interactiveCreatePageButton.target.setLabel('Новая страница');
    interactiveCreatePageButton.target.setCallback(() => {
      window.location.href = `./page`;
    });
    interactiveCreatePageButton.assembly();
  
    let interactiveContainerElement = document.querySelector('#E8548530785');
    interactiveContainerElement.append(interactiveCreatePageButton.target.element);

    let tableItemsPagesStatic = document.querySelectorAll('.table-pages-static__item');

    for (let tableItemPageStatic of tableItemsPagesStatic) {
      let pageStaticID = tableItemPageStatic.getAttribute('data-page-static-id');
      let buttons = tableItemPageStatic.querySelectorAll('button[role]');

      for (let button of buttons) {
        button.addEventListener('click', (event) => {
          if (button.getAttribute('role') == 'page-static-edit') {
            window.location.href = `./page/${pageStaticID}`;
          }

          if (button.getAttribute('role') == 'page-static-remove') {
            let interactiveModal = new Interactive('modal', {title: "Удаление страницы", content: "Вы действительно хотите удалить страницу? Действие отменить будет нельзя."});
            interactiveModal.target.addButton('Удалить', () => {
              let formData = new FormData();
              formData.append('page_static_id', pageStaticID);

              fetch('/handler/pageStatic/' + pageStaticID, {
                method: 'DELETE',
                body: formData
              }).then((response) => {
                return response.json();
              }).then((data) => {
                if (data.statusCode == 1) {
                  window.location.href = '/admin/pages';
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