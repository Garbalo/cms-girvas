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

export class PagePages {
  constructor(page, params = {}) {
    this.page = page;
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
      interactiveCreatePageButton.target.setLabel(localeData.BUTTON_NEW_PAGE_LABEL);
      interactiveCreatePageButton.target.setCallback(() => {
        window.location.href = `./page`;
      });
      interactiveCreatePageButton.assembly();
    
      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveCreatePageButton.target.element);

      let tableItemsPagesStatic = document.querySelectorAll('.table-pages-static__item');

      for (let tableItemPageStatic of tableItemsPagesStatic) {
        let pageStaticID = tableItemPageStatic.getAttribute('data-page-static-id');
        let pageStaticName = tableItemPageStatic.getAttribute('data-name');
        let buttons = tableItemPageStatic.querySelectorAll('button[role]');

        for (let button of buttons) {
          button.addEventListener('click', (event) => {
            if (button.getAttribute('role') == 'page-static-edit') {
              window.location.href = `./page/${pageStaticID}`;
            }
            
            if (button.getAttribute('role') == 'page-static-view') {
              window.open(`/page/${pageStaticName}`, '_blank');
            }

            if (button.getAttribute('role') == 'page-static-remove') {
              let interactiveModal = new Interactive('modal', {
                title: localeData.MODAL_PAGE_DELETE_TITLE,
                content: localeData.MODAL_PAGE_DELETE_DESCRIPTION
              });
              
              interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
                let formData = new FormData();
                formData.append('page_static_id', pageStaticID);

                let request = new Interactive('request', {
                  method: 'DELETE',
                  url: '/handler/pageStatic/' + pageStaticID + '?localeMessage=' + window.CMSCore.locales.admin.name
                });
      
                request.target.data = formData;
      
                request.target.send().then((data) => {
                  if (data.statusCode == 1) {
                    window.location.href = '/admin/pages';
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