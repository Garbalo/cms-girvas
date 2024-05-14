/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../../interactive.class.js";
import {URLParser} from "../../../urlParser.class.js";
import {Utils} from "../../../utils.class.js";

export class PageUsersGroup {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null};
  }

  init() {
    let searchParams = new URLParser(), locales;
    let elementForm = document.querySelector('.form_user-group');

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {
      let interactiveChoicesLocales = new Interactive('choices');
      let usersGroupTitleInputElement = document.querySelector('[role="usersGroupTitle"]');
      let urlInputElement = document.querySelector('[role="usersGroupName"]');

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

        if (locale.name === window.CMSCore.locales.admin.name) {
          interactiveChoicesLocales.target.setItemSelectedIndex(localeIndex);
        }

        if (locale.name === window.CMSCore.locales.admin.name) {
          usersGroupTitleInputElement.setAttribute('name', 'user_group_title_' + locale.iso639_2);

          if (searchParams.getPathPart(3) != null) {
            fetch('/handler/usersGroup/' + searchParams.getPathPart(3) + '?locale=' + locale.name + '&localeMessage=' + window.CMSCore.locales.admin.name, {
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

      interactiveChoicesLocales.assembly();

      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveChoicesLocales.target.element);

      urlInputElement.addEventListener('input', (event) => {
        /** @var {String} */
        let inputValue = event.target.value;

        /** @var {Utils} */
        let utils = new Utils();
        /** @var {UString} */
        let uString = utils.createString(inputValue);

        event.target.value = uString.translitToEN(true);
      });

      let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
      interactiveChoicesSelectElement.addEventListener('change', (event) => {
        locales.forEach((locale, localeIndex) => {
          if (locale.name === event.target.value) {
            usersGroupTitleInputElement.setAttribute('name', 'user_group_title_' + locale.iso639_2);

            if (searchParams.getPathPart(3) != null) {
              fetch('/handler/usersGroup/' + searchParams.getPathPart(3) + '?locale=' + event.target.value + '&localeMessage=' + window.CMSCore.locales.admin.name, {
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
      this.buttons.save.target.setLabel(localeData.BUTTON_SAVE_LABEL);
      this.buttons.save.target.setCallback((event) => {
        event.preventDefault();
        
        let form = new Interactive('form');
        form.target.replaceElement(elementForm);

        if (form.target.checkRequiredFields()) {
          let formData = new FormData(elementForm);

          fetch('/handler/usersGroup?localeMessage=' + window.CMSCore.locales.admin.name, {
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
        } else {
          let notification = new PopupNotification(localeData.FORM_REQUIRED_FIELDS_IS_EMPTY, document.body, true);
          notification.show();
        }
      });
      this.buttons.save.assembly();

      this.buttons.delete = new Interactive('button');
      this.buttons.delete.target.setLabel(localeData.BUTTON_DELETE_LABEL);
      this.buttons.delete.target.setCallback((event) => {
        event.preventDefault();

        let interactiveModal = new Interactive('modal', {
          title: localeData.MODAL_USERS_GROUP_DELETE_TITLE,
          content: localeData.MODAL_USERS_GROUP_DELETE_DESCRIPTION
        });
        
        interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
          let formData = new FormData();
          formData.append('user_group_id', searchParams.getPathPart(3));

          fetch('/handler/usersGroup/' + searchParams.getPathPart(3) + '?localeMessage=' + window.CMSCore.locales.admin.name, {
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

        interactiveModal.target.addButton(localeData.BUTTON_CANCEL_LABEL, () => {
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