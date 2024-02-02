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

export class PageUsersGroup {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null};
  }

  init() {
    let locales, localeBaseSelected, localeAdminSelected;
    let searchParams = new URLParser();
    let elementForm = document.querySelector('.form_user-group');

    fetch('/handler/locales', {
      method: 'GET'
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return fetch('/handler/locale/base', {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      localeBaseSelected = data.outputData.locale;
      return fetch('/handler/locale/admin', {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      localeAdminSelected = data.outputData.locale;

      let interactiveChoicesLocales = new Interactive('choices');

      locales.forEach((locale, localeIndex) => {
        let localeTitle = locale.title;
        let localeIconURL = locale.iconURL;
        let localeName = locale.name;

        let localeIconImageElement = document.createElement('img');
        localeIconImageElement.setAttribute('src', localeIconURL);
        localeIconImageElement.setAttribute('alt', localeTitle);

        let localeLabelElement = document.createElement('span');
        localeLabelElement.innerText = localeTitle;

        let localeTemplate = document.createElement('template');
        localeTemplate.innerHTML += localeIconImageElement.outerHTML;
        localeTemplate.innerHTML += localeLabelElement.outerHTML;

        interactiveChoicesLocales.target.addItem(localeTemplate.innerHTML, localeName);
      });

      locales.forEach((locale, localeIndex) => {
        if (locale.name === localeBaseSelected.name) {
          interactiveChoicesLocales.target.setItemSelectedIndex(localeIndex);
        }
      });

      interactiveChoicesLocales.assembly();

      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveChoicesLocales.target.element);

      let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
      interactiveChoicesSelectElement.addEventListener('change', (event) => {
        let usersGroupTitleInputElement = document.querySelector('[role="usersGroupTitle"]');
        locales.forEach((locale, localeIndex) => {
          if (locale.name === event.target.value) {
            usersGroupTitleInputElement.setAttribute('name', 'user_group_title_' + locale.iso639_2);

            if (searchParams.getPathPart(3) != null) {
              fetch('/handler/usersGroup/' + searchParams.getPathPart(3) + '?locale=' + event.target.value, {
                method: 'GET'
              }).then((response) => {
                return (response.ok) ? response.json() : Promise.reject(response);
              }).then((data1) => {
                let usersGroupData = data1.outputData.usersGroup;

                usersGroupTitleInputElement.value = usersGroupData.title;
              });
            }
          }
        });
      });
      
      this.buttons.save = new Interactive('button');
      this.buttons.save.target.setLabel('Сохранить');
      this.buttons.save.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData(elementForm);

        fetch('/handler/usersGroup', {
          method: (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH',
          body: formData
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
            let usersGroupData = data1.outputData.usersGroup;
            window.location.href = '/admin/userGroup/' + usersGroupData.id;
          }

          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.save.assembly();

      this.buttons.delete = new Interactive('button');
      this.buttons.delete.target.setLabel('Удалить');
      this.buttons.delete.target.setCallback((event) => {
        event.preventDefault();

        let interactiveModal = new Interactive('modal', {title: "Удаление группу пользователей", content: "Вы действительно хотите удалить группу пользователей? Действие отменить будет нельзя."});
        interactiveModal.target.addButton('Удалить', () => {
          let formData = new FormData();
          formData.append('user_group_id', searchParams.getPathPart(3));

          fetch('/handler/usersGroup/' + searchParams.getPathPart(3), {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data1) => {
            if (data1.statusCode == 1) {
              window.location.href = '/admin/usersGroups';
            }

            let notification = new PopupNotification(data1.message, document.body, true);
            notification.show();
          });
        });

        interactiveModal.target.addButton('Отмена', () => {
          interactiveModal.target.close();
        });

        interactiveModal.assembly();
        document.body.appendChild(interactiveModal.target.element);
        interactiveModal.target.show();
      });
      this.buttons.delete.assembly();

      if (searchParams.getPathPart(3) == null) {
        this.buttons.delete.target.element.style.display = 'none';
        this.buttons.save.target.element.style.display = 'flex';
      } else {
        this.buttons.delete.target.element.style.display = 'flex';
        this.buttons.save.target.element.style.display = 'flex';
      }
  
      let interactiveFormPanelContainer = document.querySelector('#SYSTEM_E3724126170');
      interactiveFormPanelContainer.append(this.buttons.delete.target.element);
      interactiveFormPanelContainer.append(this.buttons.save.target.element);
    });
  }
}