/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

//import {Page} from "page.class.js";

document.addEventListener('DOMContentLoaded', () => {
  // Инициализация всех форм в документе
  let formsElementsArray = document.body.querySelectorAll('form');
  for (let formElement of formsElementsArray) {
    let form = new Form(formElement);
    form.initFormElement();
  }
});