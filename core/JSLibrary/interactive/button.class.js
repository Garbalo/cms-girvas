/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

export class Button {
  constructor() {
    this.element = null;
    this.label = null;
    this.iconUrl = null;
    this.callback = (event) => {
      event.preventDefault();
    };
    this.assembled = null;
  }

  show() {
    this.assembled.style.display = 'block';
  }

  hide() {
    this.assembled.style.display = 'none';
  }

  setCallback(callbackFunction) {
    this.callback = callbackFunction;
  }

  setLabel(value) {
    this.label = value;
  }

  setIconURL(value) {
    this.iconUrl = value;
  }

  assembly() {
    let element = document.createElement('div');

    let buttonElement = document.createElement('button');
    buttonElement.classList.add('interactive__button');
    buttonElement.addEventListener('click', this.callback);

    if (this.iconUrl != null) {
      let buttonIconElement = document.createElement('img');
      buttonIconElement.classList.add('interactive__button-icon');
      buttonIconElement.setAttribute('scr', this.iconUrl);
      buttonElement.append(buttonIconElement);
    }

    let buttonLabelElement = document.createElement('span');
    buttonLabelElement.classList.add('interactive__button-label');
    buttonLabelElement.innerHTML = (this.label != null) ? this.label : 'Button';
    buttonElement.append(buttonLabelElement);

    element.append(buttonElement);

    this.element = element;
  }
}