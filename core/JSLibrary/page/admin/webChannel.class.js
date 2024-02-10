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

export class PageWebChannel {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null};
  }

  init() {
    let searchParams = new URLParser();
    let elementForm = document.querySelector('.form_webchannel');

    let locales, localeBaseSelected, localeAdminSelected;
    let interactiveLocalesChoices = new Interactive('choices');
    let interactiveChoicesEntriesCategories = new Interactive('choices');

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

      this.buttons.delete = new Interactive('button');
      this.buttons.delete.target.setLabel('Удалить');
      this.buttons.delete.target.setCallback((event) => {
        event.preventDefault();
        
        let interactiveModal = new Interactive('modal', {title: "Удаление веб-канала", content: "Вы действительно хотите удалить веб-канал? Действие отменить будет нельзя."});
        interactiveModal.target.addButton('Удалить', () => {
          let formData = new FormData();
          formData.append('user_id', searchParams.getPathPart(3));
  
          fetch('/handler/webChannel/' + searchParams.getPathPart(3), {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data1) => {
            if (data1.statusCode == 1) {
              window.location.href = '/admin/webChannels';
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

      this.buttons.save = new Interactive('button');
      this.buttons.save.target.setLabel('Сохранить');
      this.buttons.save.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData(elementForm);
  
        fetch('/handler/webChannel', {
          method: (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH',
          body: formData
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          console.log(data1);
          if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
            let webChannelData = data1.outputData.webChannel;
            window.location.href = '/admin/webChannel/' + webChannelData.id;
          }
  
          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.save.assembly();

      if (searchParams.getPathPart(3) != null) {
        let webChannelsTypes;
        let interactiveChoicesWebChannelsTypes = new Interactive('choices');

        fetch('/handler/webChannels/types', {
          method: 'GET'
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          webChannelsTypes = data1.outputData.webChannelsTypes;
          return fetch('/handler/webChannel/' + searchParams.getPathPart(3), {method: 'GET'});
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          webChannelsTypes.forEach((type, typeIndex) => {
            interactiveChoicesWebChannelsTypes.target.addItem(type.title, type.id);
          });

          webChannelsTypes.forEach((type, typeIndex) => {
            if (type.id == data1.outputData.webChannel.typeID) {
              interactiveChoicesWebChannelsTypes.target.setItemSelectedIndex(typeIndex);
            }
          });

          interactiveChoicesWebChannelsTypes.target.setName('web_channel_type_id');
          interactiveChoicesWebChannelsTypes.assembly();

          document.querySelector('#TC6474387201').append(interactiveChoicesWebChannelsTypes.target.element);
        });

        let entriesCategories;

        fetch('/handler/entries/categories?locale=' + localeAdminSelected.name, {
          method: 'GET'}
        ).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          entriesCategories = data1.outputData.entriesCategories;
          return fetch('/handler/webChannel/' + searchParams.getPathPart(3), {method: 'GET'});
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          entriesCategories.forEach((entryCategory, entryCategoryIndex) => {
            interactiveChoicesEntriesCategories.target.addItem(entryCategory.title, entryCategory.id);
          });

          entriesCategories.forEach((entryCategory, entryCategoryIndex) => {
            if (entryCategory.id == data1.outputData.webChannel.entriesCategoryID) {
              interactiveChoicesEntriesCategories.target.setItemSelectedIndex(entryCategoryIndex);
            }
          });

          interactiveChoicesEntriesCategories.target.setName('web_channel_entries_category_id');
          interactiveChoicesEntriesCategories.assembly();

          document.querySelector('#TC6474387200').append(interactiveChoicesEntriesCategories.target.element);
        });

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

          interactiveLocalesChoices.target.addItem(localeTemplate.innerHTML, localeName);
        });

        locales.forEach((locale, localeIndex) => {
          if (locale.name === localeBaseSelected.name) {
            interactiveLocalesChoices.target.setItemSelectedIndex(localeIndex);
          }
        });

        interactiveLocalesChoices.assembly();

        let interactiveContainerElement = document.querySelector('#E8548530785');
        interactiveContainerElement.append(interactiveLocalesChoices.target.element);

        let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
        interactiveChoicesSelectElement.addEventListener('change', (event) => {
          let webChannelDescriptionTextareaElement = document.querySelector('[role="webChannelDescription"]');
          let webChannelTitleInputElement = document.querySelector('[role="webChannelTitle"]');

          locales.forEach((locale, localeIndex) => {
            if (locale.name == event.target.value) {
              webChannelDescriptionTextareaElement.setAttribute('name', 'web_channel_description_' + locale.iso639_2);
              webChannelTitleInputElement.setAttribute('name', 'web_channel_title_' + locale.iso639_2);
              
              fetch('/handler/webChannel/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return (response.ok) ? response.json() : Promise.reject(response);
              }).then((data) => {
                webChannelDescriptionTextareaElement.value = data.outputData.webChannel.description;
                webChannelTitleInputElement.value = data.outputData.webChannel.title;
              });
            }
          });
        });
      } else {
        fetch('/handler/webChannels/types', {
          method: 'GET'
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          let webChannelsTypes = data1.outputData.webChannelsTypes;
          let interactiveChoicesWebChannelsTypes = new Interactive('choices');
  
          webChannelsTypes.forEach((type, typeIndex) => {
            interactiveChoicesWebChannelsTypes.target.addItem(type.title, type.id);
          });
  
          interactiveChoicesWebChannelsTypes.target.setName('web_channel_type_id');
          interactiveChoicesWebChannelsTypes.assembly();
  
          document.querySelector('#TC6474387201').append(interactiveChoicesWebChannelsTypes.target.element);
  
          return fetch('/handler/entries/categories', {method: 'GET'});
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          let entriesCategories = data1.outputData.entriesCategories;
          let interactiveChoicesEntriesCategories = new Interactive('choices');
  
          entriesCategories.forEach((entryCategory, entryCategoryIndex) => {
            interactiveChoicesEntriesCategories.target.addItem(entryCategory.title, entryCategory.id);
          });
  
          interactiveChoicesEntriesCategories.target.setName('web_channel_entries_category_id');
          interactiveChoicesEntriesCategories.assembly();
  
          document.querySelector('#TC6474387200').append(interactiveChoicesEntriesCategories.target.element);
        });
      }

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