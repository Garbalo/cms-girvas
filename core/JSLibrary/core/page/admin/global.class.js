/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../../interactive.class.js";
import {ElementButton} from "../../../interactive/form/elementButton.class.js";
import {ElementTextarea} from "../../../interactive/form/elementTextarea.class.js";
import {URLParser} from "../../../urlParser.class.js";
import {EntryComment} from "./../entry/comment.class.js";

/**
 * Глобально
 */
export class PageGlobal {
  /**
   * constructor
   * 
   * @param {*} params 
   */
  constructor(page, params = {}) {
    this.page = page;

    this.buttons = {checkVersion: null, toSite: null};
  }

  /**
   * Инициализация
   */
  init() {
    let searchParams = new URLParser(), locales;
    let globalButtonsContainerElement = document.querySelector('#SYSTEM_E3724126421');

    let navigationBurgerElement = document.querySelector('[role="mainNavigationBurger"]');
    if (navigationBurgerElement != null) {
      navigationBurgerElement.addEventListener('click', (event) => {
        navigationBurgerElement.classList.toggle('admin-navigation__burger_is-active');
      });
    }

    // Подгрузка локализаций
    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {
      let mainNavigationItemExitElement = document.querySelector('[role="mainNavigationExit"]');
      if (mainNavigationItemExitElement != null) {
        mainNavigationItemExitElement.addEventListener('click', (event) => {
          event.preventDefault();

          let formData = new FormData();

          let request = new Interactive('request', {
            method: 'POST',
            url: '/handler/client/session-end?level=2&localeMessage=' + window.CMSCore.locales.admin.name
          });

          request.target.data = formData;

          request.target.send().then((data) => {
            if (data.statusCode == 1 && data.outputData.hasOwnProperty('result')) {
              let result = data.outputData.result;

              if (result == true) {
                window.location.reload();
              }
            }
          });
        });
      }

      if (globalButtonsContainerElement != null) {
        // Кнопка "Проверить обновления"
        this.buttons.checkVersion = new Interactive('button');
        this.buttons.checkVersion.target.setLabel(localeData.BUTTON_CHECK_UPDATES);
        this.buttons.checkVersion.target.setCallback((event) => {
          event.preventDefault();

          window.CMSCore.getCMSVersion().then((data) => {
            let request = new Interactive('request', {
              method: 'GET',
              url: `https://repository.cms-girvas.ru/system-checker?currentVersion=${data}`
            });

            request.target.send().then((data1) => {
              if (data1.statusCode == 1 && data1.outputData.hasOwnProperty('needToUpdate')) {
                let needToUpdate = data1.outputData.needToUpdate;
                let lastVersion = data1.outputData.lastVersion;
  
                let interactiveNotificationLoading = new Interactive('notification');
                interactiveNotificationLoading.target.isPopup = true;

                if (needToUpdate) {
                  interactiveNotificationLoading.target.setStatusCode(1);
                  interactiveNotificationLoading.target.setContent(`${localeData.UPDATE_CHECKER_NEW_VERSION} [${data} => ${lastVersion}]`);
                } else {
                  interactiveNotificationLoading.target.setStatusCode(-1);
                  interactiveNotificationLoading.target.setContent(localeData.UPDATE_CHECKER_CURRENT_VERSION);
                }

                interactiveNotificationLoading.target.assembly();

                interactiveNotificationLoading.target.show();
              }
            });
          });
        });

        // Кнопка "Перейти на сайт"
        this.buttons.toSite = new Interactive('button');
        this.buttons.toSite.target.setLabel(localeData.BUTTON_GO_TO_SITE);
        this.buttons.toSite.target.setCallback((event) => {
          event.preventDefault();

          window.open('/', '_blank');
        });

        this.buttons.checkVersion.assembly();
        this.buttons.toSite.assembly();

        globalButtonsContainerElement.append(this.buttons.checkVersion.target.element);
        globalButtonsContainerElement.append(this.buttons.toSite.target.element);
      }
    });
  }
}