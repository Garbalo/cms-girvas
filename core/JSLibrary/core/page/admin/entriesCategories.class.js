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

export class PageEntriesCategories {
  constructor(params = {}) {
    
  }

  init() {
    let locales;

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {

      let interactiveCreatePageButton = new Interactive('button');
      interactiveCreatePageButton.target.setLabel(localeData.BUTTON_NEW_ENTRIES_CATEGORY_LABEL);
      interactiveCreatePageButton.target.setCallback(() => {
        window.location.href = `./entriesCategory`;
      });
      interactiveCreatePageButton.assembly();
    
      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveCreatePageButton.target.element);

      let tableItems = document.querySelectorAll('.table-entries-categories__item');

      for (let tableItem of tableItems) {
        let entriesCategoryID = tableItem.getAttribute('data-entries-category-id');
        let buttons = tableItem.querySelectorAll('button[role]');

        for (let button of buttons) {
          button.addEventListener('click', (event) => {
            if (button.getAttribute('role') == 'entries-category-edit') {
              window.location.href = `./entriesCategory/${entriesCategoryID}`;
            }

            if (button.getAttribute('role') == 'entries-category-delete') {
              let interactiveModal = new Interactive('modal', {title: localeData.MODAL_ENTRIES_CATEGORY_DELETE_TITLE, content: localeData.MODAL_ENTRIES_CATEGORY_DELETE_DESCRIPTION});
              interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
                let formData = new FormData();
                formData.append('entries_category_id', entriesCategoryID);

                fetch('/handler/entry/category/' + entriesCategoryID + '?localeMessage=' + window.CMSCore.locales.admin.name, {
                  method: 'DELETE',
                  body: formData
                }).then((response) => {
                  return response.json();
                }).then((data) => {
                  if (data.statusCode == 1) {
                    window.location.href = '/admin/entriesCategories';
                  }

                  let notification = new PopupNotification(data.message, document.body, true);
                  notification.show();
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