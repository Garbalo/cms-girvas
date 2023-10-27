'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let interactiveButton = new Interactive('button');
  interactiveButton.target.setLabel('Премиальные шаблоны');
  interactiveButton.target.setCallback(() => {
    window.location.href = `https://galior-market.ru/users/Drelagas#templates`;
  });
  interactiveButton.assembly();

  let interactiveContainerElement = document.querySelector('#E8548530785');
  interactiveContainerElement.append(interactiveButton.target.element)

  let searchParams = new URLParser();
  let listItems = document.querySelectorAll('.templates-list .list__item');
  for (let listItem of listItems) {
    let templateName = listItem.getAttribute('data-template-name');
    let templateCategory = listItem.getAttribute('data-template-category');
    let templateInstalledStatus = listItem.getAttribute('data-template-installed-status');
    let itemFooterContainer = listItem.querySelector('[role="item-footer-panel"]');

    // Добавление интерактивных элементов
    // Кнопка "Подробнее"
    let interactiveButtonMore = new Interactive('button');
    interactiveButtonMore.target.setLabel('Подробнее');
    interactiveButtonMore.target.setCallback(() => {
      window.location.href = (searchParams.getPathPart(3) == null) ? `./template/${templateName}` : `../template/${templateName}`;
    });
    interactiveButtonMore.assembly();
    itemFooterContainer.appendChild(interactiveButtonMore.target.element)

    let buttons = {delete: null, install: null};

    // Кнопка "Удалить"
    buttons.delete = new Interactive('button');
    buttons.delete.target.setLabel('Удалить');
    buttons.delete.target.setCallback(() => {
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
    itemFooterContainer.appendChild(buttons.delete.target.element);

    // Кнопка "Установить"
    buttons.install = new Interactive('button');
    buttons.install.target.setLabel('Установить');
    buttons.install.target.setCallback(() => {
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
    buttons.install.assembly();
    itemFooterContainer.appendChild(buttons.install.target.element);

    buttons.install.target.element.style.display = (templateInstalledStatus == 'installed') ? 'none' : 'flex';
    buttons.delete.target.element.style.display = (templateInstalledStatus == 'installed') ? 'flex' : 'none';
  }
});