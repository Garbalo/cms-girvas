'use strict';

import {Interactive} from "../../interactive.class.js";

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
    interactiveButtonMore.target.assembly();
    itemFooterContainer.appendChild(interactiveButtonMore.target.assembled)

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
              buttons.install.target.assembled.style.display = 'flex';
              buttons.delete.target.assembled.style.display = 'none';
            }
          }

          let notification = new PopupNotification(data.message, document.body, true);
          notification.show();
        });
      });
      interactiveModal.target.assembly();
      document.body.appendChild(interactiveModal.target.assembled);
      interactiveModal.target.show();
    });
    buttons.delete.target.assembly();
    itemFooterContainer.appendChild(buttons.delete.target.assembled);

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
          buttons.install.target.assembled.style.display = 'none';
          buttons.delete.target.assembled.style.display = 'flex';
        }

        let notification = new PopupNotification(data.message, document.body, true);
        notification.show();
      });
    });
    buttons.install.target.assembly();
    itemFooterContainer.appendChild(buttons.install.target.assembled);

    buttons.install.target.assembled.style.display = (moduleInstalledStatus == 'installed') ? 'none' : 'flex';
    buttons.delete.target.assembled.style.display = (moduleInstalledStatus == 'installed') ? 'flex' : 'none';
  }
});