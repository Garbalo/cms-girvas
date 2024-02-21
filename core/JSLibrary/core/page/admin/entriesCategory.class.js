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

export class PageEntriesCategory {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null};
  }

  init() {
    let searchParams = new URLParser();
    let elementForm = document.querySelector('.form_entries-category');

    let locales;
    let interactiveLocaleChoices = new Interactive('choices');
    let interactiveParentChoices = new Interactive('choices');

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {

      locales.forEach((locale, localeIndex) => {
        let localeTitle = locale.title;
        let localeIconURL = locale.iconURL;
        let localeName = locale.name;
        let localeISO639_2 = locale.iso639_2;

        let localeIconImageElement = document.createElement('img');
        localeIconImageElement.setAttribute('src', localeIconURL);
        localeIconImageElement.setAttribute('alt', localeTitle);

        let localeLabelElement = document.createElement('span');
        localeLabelElement.innerText = localeTitle;

        let localeTemplate = document.createElement('template');
        localeTemplate.innerHTML += localeIconImageElement.outerHTML;
        localeTemplate.innerHTML += localeLabelElement.outerHTML;

        interactiveLocaleChoices.target.addItem(localeTemplate.innerHTML, localeName);
      });

      locales.forEach((locale, localeIndex) => {
        if (locale.name === window.CMSCore.locales.base.name) {
          interactiveLocaleChoices.target.setItemSelectedIndex(localeIndex);
        }
      });

      interactiveLocaleChoices.assembly();

      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveLocaleChoices.target.element);
      
      let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
      interactiveChoicesSelectElement.addEventListener('change', (event) => {
        let entryDescriptionTextareaElement = document.querySelector('[role="entriesCategoryDescription"]');
        let entryTitleInputElement = document.querySelector('[role="entriesCategoryTitle"]');
        
        locales.forEach((locale, localeIndex) => {
          if (locale.name == event.target.value) {
            entryDescriptionTextareaElement.setAttribute('name', 'entries_category_description_' + locale.iso639_2);
            entryTitleInputElement.setAttribute('name', 'entries_category_title_' + locale.iso639_2);

            if (searchParams.getPathPart(3) != null) {
              fetch('/handler/entry/category/' + searchParams.getPathPart(3) + '?locale=' + locale.name + '&localeMessage=' + window.CMSCore.locales.admin.name, {
                method: 'GET'
              }).then((response) => {
                return (response.ok) ? response.json() : Promise.reject(response);
              }).then((data1) => {
                entryDescriptionTextareaElement.value = data1.outputData.entriesCategory.description;
                entryTitleInputElement.value = data1.outputData.entriesCategory.title;
              });
            }
          }
        });
      });

      let entriesCategoryData = {}, entriesCategoriesData = {};
      
      fetch('/handler/entry/category/' + searchParams.getPathPart(3) + '?locale=' + window.CMSCore.locales.admin.name + '&localeMessage=' + window.CMSCore.locales.admin.name, {
        method: 'GET'
      }).then((response) => {
        return (response.ok) ? response.json() : Promise.reject(response);
      }).then((data1) => {
        if (data1.statusCode == 1) {
          entriesCategoryData = data1.outputData.entriesCategory;
        }

        return fetch('/handler/entry/categories' + '?locale=' + window.CMSCore.locales.admin.name + '&localeMessage=' + window.CMSCore.locales.admin.name, {method: 'GET'});
      }).then((response) => {
        return (response.ok) ? response.json() : Promise.reject(response);
      }).then((data1) => {
        if (data1.statusCode == 1) {
          entriesCategoriesData = data1.outputData.entriesCategories;

          entriesCategoriesData.forEach((entriesCategory, entriesCategoryIndex) => {
            if (entriesCategoryData.id != entriesCategory.id) {
              interactiveParentChoices.target.addItem(entriesCategory.title, entriesCategory.id);
            }

            if (entriesCategory.id == entriesCategoryData.parentID) {
              interactiveParentChoices.target.setItemSelectedIndex(entriesCategoryIndex);
            }
          });
          interactiveParentChoices.target.setName('entries_category_parent_id');
          interactiveParentChoices.assembly();

          let interactiveContainer = document.querySelector('#TC6474389602');
          interactiveContainer.innerHTML = '';
          interactiveContainer.append(interactiveParentChoices.target.element);
        }
      });

      this.buttons.save = new Interactive('button');
      this.buttons.save.target.setLabel(localeData.BUTTON_SAVE_LABEL);
      this.buttons.save.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData(elementForm);
        let fetchLink = (searchParams.getPathPart(3) == null) ? '/handler/entry/category?localeMessage=' + window.CMSCore.locales.admin.name : '/handler/entry/category/' + searchParams.getPathPart(3) + '?localeMessage=' + window.CMSCore.locales.admin.name;
        let fetchMethod = (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH';

        fetch(fetchLink, {method: fetchMethod, body: formData}).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
            let entriesCategoryData = data1.outputData.entriesCategory;
            window.location.href = '/admin/entriesCategory/' + entriesCategoryData.id;
          }

          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.save.assembly();

      this.buttons.delete = new Interactive('button');
      this.buttons.delete.target.setLabel(localeData.BUTTON_DELETE_LABEL);
      this.buttons.delete.target.setCallback((event) => {
        event.preventDefault();

        let interactiveModal = new Interactive('modal', {title: localeData.MODAL_ENTRIES_CATEGORY_DELETE_TITLE, content: localeData.MODAL_ENTRIES_CATEGORY_DELETE_DESCRIPTION});
        interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
          let formData = new FormData();
          formData.append('entries_category_id', searchParams.getPathPart(3));

          fetch('/handler/entry/category/' + searchParams.getPathPart(3) + '?localeMessage=' + window.CMSCore.locales.admin.name, {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data1) => {
            if (data1.statusCode == 1) {
              window.location.href = '/admin/entriesCategories';
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

      let interactiveContainer = document.querySelector('#SYSTEM_E3724126170');
      interactiveContainer.append(this.buttons.delete.target.element);
      interactiveContainer.append(this.buttons.save.target.element);
    });
  }
}