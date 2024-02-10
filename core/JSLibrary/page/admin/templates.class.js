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
      let buttons = {more: null, delete: null, install: null};

      let templateName = listItem.getAttribute('data-template-name');
      let templateCategory = listItem.getAttribute('data-template-category');
      let templateInstalledStatus = listItem.getAttribute('data-template-installed-status');
      let itemFooterContainer = listItem.querySelector('[role="item-footer-panel"]');

      buttons.more = new Interactive('button');
      buttons.more.target.setLabel('Подробнее');
      buttons.more.target.setCallback((event) => {
        window.location.href = (searchParams.getPathPart(3) == null) ? `./template/${templateName}` : `../template/${templateName}`;
      });
      buttons.more.assembly();

      buttons.delete = new Interactive('button');
      buttons.delete.target.setLabel('Удалить');
      buttons.delete.target.setCallback((event) => {
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
      buttons.delete.assembly();

      buttons.install = new Interactive('button');
      buttons.install.target.setLabel('Установить');
      buttons.install.target.setCallback((event) => {
        let formData = new FormData();
        formData.append('template_name', templateName);
        formData.append('template_category', templateCategory);

        let notification_start = new PopupNotification('Загрузка шаблона...', document.body, true);
        notification_start.show();

        fetch('/handler/template/install', {
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
      buttons.install.assembly();

      itemFooterContainer.appendChild(buttons.more.target.element);
      itemFooterContainer.appendChild(buttons.delete.target.element);
      itemFooterContainer.appendChild(buttons.install.target.element);

      buttons.install.target.element.style.display = (templateInstalledStatus == 'installed') ? 'none' : 'flex';
      buttons.delete.target.element.style.display = (templateInstalledStatus == 'installed') ? 'flex' : 'none';
    }
  }
}