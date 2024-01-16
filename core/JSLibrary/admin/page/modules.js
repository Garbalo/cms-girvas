'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();
  let listItems = document.querySelectorAll('.modules-list .list__item');
  for (let listItem of listItems) {
    let moduleName = listItem.getAttribute('data-module-name');
    let moduleInstalledStatus = listItem.getAttribute('data-module-installed-status');
    let itemFooterContainer = listItem.querySelector('[role="item-footer-panel"]');

    // Добавление интерактивных элементов
    // Кнопка "Подробнее"
    let interactiveButtonMore = new Interactive('button');
    interactiveButtonMore.target.setLabel('Подробнее');
    interactiveButtonMore.target.setCallback(() => {
      window.location.href = (searchParams.getPathPart(3) == null) ? `./module/${moduleName}` : `./repository/${moduleName}`;
    });
    interactiveButtonMore.assembly();
    itemFooterContainer.appendChild(interactiveButtonMore.target.element)

    let buttons = {delete: null, install: null};

    // Кнопка "Удалить"
    buttons.delete = new Interactive('button');
    buttons.delete.target.setLabel('Удалить');
    buttons.delete.target.setCallback(() => {
      let interactiveModal = new Interactive('modal', {title: "Удаление модуля", content: "Вы действительно хотите удалить модуль? Действие отменить будет нельзя."});
      interactiveModal.target.addButton('Удалить', () => {
        let formData = new FormData();
        formData.append('module_name', moduleName);

        fetch('/handler/module', {
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
    itemFooterContainer.appendChild(buttons.delete.target.element);

    // Кнопка "Установить"
    buttons.install = new Interactive('button');
    buttons.install.target.setLabel('Установить');
    buttons.install.target.setCallback(() => {
      let formData = new FormData();
      formData.append('module_name', moduleName);

      let notification_start = new PopupNotification('Загрузка модуля...', document.body, true);
      notification_start.show();

      fetch('/handler/admin/modules/download', {
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
    itemFooterContainer.appendChild(buttons.install.target.element);

    buttons.install.target.element.style.display = (moduleInstalledStatus == 'installed') ? 'none' : 'flex';
    buttons.delete.target.element.style.display = (moduleInstalledStatus == 'installed') ? 'flex' : 'none';
  }
});