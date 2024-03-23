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
import {EntryComment} from "./../entry/comment.class.js";

export class PageGlobal {
  constructor(params = {}) {
    // ...
  }

  /**
   * SYSTEM_GE_IMC_00000001 | Интерактивный элемент вызова окна авторизации пользователя
   * SYSTEM_GE_IMC_00000002 | 
   */

  init() {
    let navigationBurgerElement = document.querySelector('[role="navagation-burger"]');
    if (navigationBurgerElement != null) {
      navigationBurgerElement.addEventListener('click', (event) => {
        navigationBurgerElement.classList.toggle('admin-navigation__burger_is-active');
      });
    }
  }
}