/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

//import {Interactive} from "../interactive.class.js";
import {ElementTextarea} from "./form/elementTextarea.class.js";
import {ElementInput} from "./form/elementInput.class.js";
import {ElementButton} from "./form/elementButton.class.js";

/**
 * Интерактивная форма (экспериментальный класс)
 */
export class Form {
  constructor(element = null) {
    this.element = element;
    this.successCallback = (data) => {};
    this.failCallback = (error) => {};
  }
  
  /**
   * Инициализация формы
   * 
   * @param {*} attributes атрибуты
   * @param {*} successCallback колбэк-функция при успешной отправке формы
   * @param {*} failCallback колбэк-функция при неуспешной отправке формы
   */
  init(attributes = {}, successCallback = () => {}, failCallback = () => {}) {
    let element = document.createElement('div');
    let elementForm = document.createElement('form');

    if (typeof attributes.id != 'undefined') {
      elementForm.setAttribute('id', attributes.id);
    }

    if (typeof attributes.enctype != 'undefined') {
      elementForm.setAttribute('id', attributes.enctype);
    }

    if (typeof attributes.method != 'undefined') {
      elementForm.setAttribute('method', attributes.method);
    }

    if (typeof attributes.action != 'undefined') {
      elementForm.setAttribute('action', attributes.action);
    }

    if (typeof attributes.role != 'undefined') {
      elementForm.setAttribute('role', attributes.role);
    }

    elementForm.classList.add('form');

    element.append(elementForm);

    this.element = element;
  }

  /**
   * Замена элемента формы
   * 
   * @param {*} element 
   */
  replaceElement(element) {
    this.element = element;
  }

  /**
   * Проверка обязательных полей формы
   * 
   * @returns {boolean}
   */
  checkRequiredFields() {
    let arrayElements = [];

    let arrayInputs = this.element.querySelectorAll('input[required]');
    let arrayTextareas = this.element.querySelectorAll('textarea[required]');
    let arraySelects = this.element.querySelectorAll('select[required]');

    let emptyFieldDetected = false;

    arrayInputs.forEach((element) => {
      arrayElements.push(element);
    });

    arrayTextareas.forEach((element) => {
      arrayElements.push(element);
    });

    arraySelects.forEach((element) => {
      arrayElements.push(element);
    });

    arrayElements.forEach((element) => {
      if (element.value.trim() == '') {
        emptyFieldDetected = true;
      }
    });

    return (emptyFieldDetected) ? false : true;
  }

  /**
   * Создание элемента Textarea
   * 
   * @param {*} attributes 
   * @returns {ElementTextarea}
   */
  createElementTextarea(attributes = {}) {
    let element = new ElementTextarea();
    element.init(attributes);
    return element;
  }

  /**
   * Создание элемента Input
   * 
   * @param {*} attributes 
   * @returns {ElementInput}
   */
  createElementInput(attributes = {}) {
    let element = new ElementInput();
    element.init(attributes);
    return element;
  }

  /**
   * Создание элемента Button
   * 
   * @param {*} attributes 
   * @returns {ElementButton}
   */
  createElementButton(attributes = {}) {
    let element = new ElementButton();
    element.init(attributes);
    return element;
  }

  /**
   * Отправка данных формы
   * 
   * @param {*} senderParams 
   */
  send(senderParams = {}) {
    let formData = new FormData(this.element.firstChild);

    let notificationLoading = new PopupNotification('Обработка запроса...', document.body, true);
    notificationLoading.show();

    let formAction = (typeof senderParams.action == 'undefined') ? this.element.firstChild.getAttribute('action') : senderParams.action;
    let formMethod = (typeof senderParams.method == 'undefined') ? this.element.firstChild.getAttribute('method') : senderParams.method;
    
    fetch(formAction, {
      method: formMethod,
      body: formData
    }).then((response) => {
      console.log('Interactive form sended...');
      return response.json();
    }).then((data) => {
      console.log('Interactive form getted data: ' + data);

      let notificationContainerTarget = document.body;
      
      // Переписать эту дичь
      if (typeof(data.outputData.reload) == 'undefined' && typeof(data.outputData.href) == 'undefined') {
        let notificationIsPopup = false;
        
        if (typeof(data.outputData.notificationContainerTargetID) == 'undefined') {
          notificationIsPopup = true;
        } else {
          notificationContainerTarget = document.querySelector('#' + data.outputData.notificationContainerTargetID);
          
          if (Object.is(notificationContainerTarget, null)) {
            notificationIsPopup = true;
          }
        }

        notificationLoading.hide();

        let notification = new PopupNotification(data.message, notificationContainerTarget, notificationIsPopup);
        notification.show();
      }

      if (typeof(data.outputData.modalClose) != 'undefined') {
        this.modalParent.remove();
      }

      if (typeof(data.outputData.reload) != 'undefined') {
        this.timeout = setTimeout(() => {
          window.location.reload();
        }, 10);
      }

      if (typeof(data.outputData.href) != 'undefined') {
        this.timeout = setTimeout(() => {
          window.location.href = data.outputData.href;
        }, 10);
      }

      this.successCallback(data);
    }).catch((error) => {
      let notification = new PopupNotification('Interactive form getted error: ' + error, document.body, true);
      notification.show();
      
      this.failCallback(error);
    });
  }

  assembly() {
    //
  }
}