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

export class PageTemplates {
  constructor(params = {}) {
    
  }

  init() {
    let searchParams = new URLParser();

    let listItems = document.querySelectorAll('.templates-list .list__item');
    for (let listItem of listItems) {
      let listItemButtons = {more: null, delete: null, install: null};

      let templateName = listItem.getAttribute('data-template-name');
      let templateCategory = listItem.getAttribute('data-template-category');
      let templateInstalledStatus = listItem.getAttribute('data-template-installed-status');
      let itemFooterContainer = listItem.querySelector('[role="item-footer-panel"]');

      listItemButtons.more = new Interactive('button');
      listItemButtons.more.target.setLabel('Подробнее');
      listItemButtons.more.target.setCallback((event) => {
        window.location.href = (searchParams.getPathPart(3) == null) ? `./template/${templateName}` : `../template/${templateName}`;
      });
      listItemButtons.more.assembly();

      listItemButtons.delete = new Interactive('button');
      listItemButtons.delete.target.setLabel('Удалить');
      listItemButtons.delete.target.setCallback((event) => {
        let interactiveModal = new Interactive('modal', {title: "Удаление шаблона", content: "Вы действительно хотите удалить шаблон? Действие отменить будет нельзя."});
        interactiveModal.target.addButton('Удалить', () => {
          let formData = new FormData();
          formData.append('template_name', templateName);
          formData.append('template_category', templateCategory);

          fetch('/handler/template', {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data) => {
            interactiveModal.target.close();

            if (data.statusCode == 1) {
              if (searchParams.getPathPart(3) != 'repository') {
                listItem.remove();
              } else {
                buttons.install.target.element.style.display = 'flex';
                buttons.delete.target.element.style.display = 'none';
              }
            }

            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          });
        });
        interactiveModal.assembly();
        document.body.appendChild(interactiveModal.target.element);
        interactiveModal.target.show();
      });
      listItemButtons.delete.assembly();

      listItemButtons.install = new Interactive('button');
      listItemButtons.install.target.setLabel('Установить');
      listItemButtons.install.target.setCallback((event) => {
        let formData = new FormData();
        formData.append('template_name', templateName);
        formData.append('template_category', templateCategory);

        let notification_start = new PopupNotification('Загрузка шаблона...', document.body, true);
        notification_start.show();

        fetch('/handler/admin/templates/download', {
          method: 'POST',
          body: formData
        }).then((response) => {
          return response.json();
        }).then((data) => {
          notification_start.hide();

          if (data.statusCode == 1) {
            buttons.install.target.element.style.display = 'none';
            buttons.delete.target.element.style.display = 'flex';
          }

          let notification = new PopupNotification(data.message, document.body, true);
          notification.show();
        });
      });
      listItemButtons.install.assembly();

      itemFooterContainer.appendChild(listItemButtons.more.target.element);
      itemFooterContainer.appendChild(listItemButtons.delete.target.element);
      itemFooterContainer.appendChild(listItemButtons.install.target.element);

      listItemButtons.install.target.element.style.display = (templateInstalledStatus == 'installed') ? 'none' : 'flex';
      listItemButtons.delete.target.element.style.display = (templateInstalledStatus == 'installed') ? 'flex' : 'none';
    }
  }
}