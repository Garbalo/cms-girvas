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

export class PageTemplates {
  constructor(page, params = {}) {
    this.page = page;
  }

  init() {
    let searchParams = new URLParser(), locales;

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {
      let listItems = document.querySelectorAll('.templates-list .list__item');
    
      for (let listItem of listItems) {
        let buttons = {more: null, delete: null, install: null};

        buttons.more = new Interactive('button');
        buttons.delete = new Interactive('button');
        buttons.install = new Interactive('button');

        buttons.more.target.setLabel(localeData.BUTTON_MORE_DETAILS_LABEL);
        buttons.delete.target.setLabel(localeData.BUTTON_DELETE_LABEL);
        buttons.install.target.setLabel(localeData.BUTTON_INSTALL_LABEL)

        let templateName = listItem.getAttribute('data-template-name');
        let templateCategory = listItem.getAttribute('data-template-category');
        let templateInstalledStatus = listItem.getAttribute('data-template-installed-status');
        let itemFooterContainer = listItem.querySelector('[role="item-footer-panel"]');

        buttons.more.target.setCallback((event) => {
          switch (searchParams.getPathPart(3)) {
            case 'repository': window.location.href = `/admin/templates/repository/${templateName}`; break;
            default: window.location.href = `/admin/template/${templateName}`;
          }
        });

        buttons.delete.target.setCallback((event) => {
          let interactiveModal = new Interactive('modal', {
            title: localeData.MODAL_TEMPLATE_DELETE_TITLE,
            content: localeData.MODAL_TEMPLATE_DELETE_DESCRIPTION
          });
          
          interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
            let formData = new FormData();
            formData.append('template_name', templateName);
            formData.append('template_category', templateCategory);

            fetch('/handler/template?localeMessage=' + window.CMSCore.locales.admin.name, {
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

        buttons.install.target.setCallback((event) => {
          let formData = new FormData();
          formData.append('template_name', templateName);
          formData.append('template_category', templateCategory);

          let notification_start = new PopupNotification(localeData.POPUP_SLIDE_INSTALL_TEMPLATE, document.body, true);
          notification_start.show();

          fetch('/handler/template/install?localeMessage=' + window.CMSCore.locales.admin.name, {
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

        buttons.more.assembly();
        buttons.delete.assembly();
        buttons.install.assembly();

        itemFooterContainer.appendChild(buttons.more.target.element);
        itemFooterContainer.appendChild(buttons.delete.target.element);
        itemFooterContainer.appendChild(buttons.install.target.element);

        buttons.install.target.element.style.display = (templateInstalledStatus == 'installed') ? 'none' : 'flex';
        buttons.delete.target.element.style.display = (templateInstalledStatus == 'installed') ? 'flex' : 'none';
      }
    });
  }
}