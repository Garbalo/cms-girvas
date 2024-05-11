/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
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
  constructor(params = {}) {
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
      mainNavigationItemExitElement.addEventListener('click', (event) => {
        event.preventDefault();

        fetch('/handler/client/session-end?level=2', {method: 'POST'}).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data) => {
          let result = data.outputData.result;

          let notification = new PopupNotification(data.message, document.body, true);
          notification.show();

          if (result == true) {
            window.location.reload();
          }
        });
      });

      // Кнопка "Проверить обновления"
      this.buttons.checkVersion = new Interactive('button');
      this.buttons.checkVersion.target.setLabel(localeData.BUTTON_CHECK_UPDATES);
      this.buttons.checkVersion.target.setCallback((event) => {
        event.preventDefault();

        //
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
    });
  }
}