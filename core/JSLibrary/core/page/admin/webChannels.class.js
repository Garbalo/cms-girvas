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

export class PageWebChannels {
  constructor(page, params = {}) {
    this.page = page;

    this.buttons = {save: null, delete: null};
  }

  init() {
    let searchParams = new URLParser(), locales;

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {

      let interactiveCreatePageButton = new Interactive('button');
      interactiveCreatePageButton.target.setLabel(localeData.BUTTON_NEW_WEB_CHANNEL_LABEL);
      interactiveCreatePageButton.target.setCallback(() => {
        window.location.href = `./webChannel`;
      });
      interactiveCreatePageButton.assembly();
    
      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveCreatePageButton.target.element);

      let tableItems = document.querySelectorAll('.table-web-channels__item');

      for (let tableItem of tableItems) {
        let webChannelID = tableItem.getAttribute('data-web-channel-id');
        let buttons = tableItem.querySelectorAll('button[role]');
        
        for (let button of buttons) {
          button.addEventListener('click', (event) => {
            if (button.getAttribute('role') == 'web-channel-edit') {
              window.location.href = `./webChannel/${webChannelID}`;
            }

            if (button.getAttribute('role') == 'web-channel-remove') {
              let interactiveModal = new Interactive('modal', {
                title: localeData.MODAL_WEB_CHANNEL_DELETE_TITLE,
                content: localeData.MODAL_WEB_CHANNEL_DELETE_DESCRIPTION
              });
              
              interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
                let formData = new FormData();
                formData.append('web_channel_id', webChannelID);

                let request = new Interactive('request', {
                  method: 'DELETE',
                  url: '/handler/webChannel/' + webChannelID + '?localeMessage=' + window.CMSCore.locales.admin.name
                });
      
                request.target.data = formData;
      
                request.target.send().then((data) => {
                  if (data.statusCode == 1) {
                    window.location.href = '/admin/webChannels';
                  }
                });
              });

              interactiveModal.target.addButton(localeData.BUTTON_CANCEL_LABEL, () => {
                interactiveModal.target.close();
              });

              interactiveModal.assembly();
              document.body.appendChild(interactiveModal.target.element);
              interactiveModal.target.show();
            }
          });
        }
      }
    });
  }
}