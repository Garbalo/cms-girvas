/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../interactive.class.js";

export class Modal {
  constructor(modalTitle, modalContent, modalWidth) {
    this.title = modalTitle;
    this.content = modalContent;
    this.width = modalWidth;
    this.opacity = 0;
    this.element = null;
    this.buttons = [];
    this.onCloseCallbackFunction = () => {};
  }

  show() {
    let elementWrapper = this.element.querySelector('.interactive__modal-wrapper');
    let interval = setInterval(() => {
      if (this.opacity < 1) {
        this.opacity += 0.01;
        elementWrapper.style.opacity = this.opacity;
      } else {
        clearInterval(interval);
      }
    }, 1);
  }

  hide() {
    let elementWrapper = this.element.querySelector('.interactive__modal-wrapper');
    let interval = setInterval(() => {
      if (this.opacity > 0) {
        this.opacity -= 0.01;
        elementWrapper.style.opacity = this.opacity;
      } else {
        clearInterval(interval);
      }
    }, 1);
  }

  onClose(callback) {
    this.onCloseCallbackFunction = callback;
  }

  close() {
    let elementWrapper = this.element.querySelector('.interactive__modal-wrapper');
    let interval = setInterval(() => {
      if (this.opacity > 0) {
        this.opacity -= 0.01;
        elementWrapper.style.opacity = this.opacity;
      } else {
        clearInterval(interval);
        this.element.remove();

        this.onCloseCallbackFunction();
      }
    }, 1);
  }

  addButton(buttonLabel, callbackFunction) {
    let interactiveButton = new Interactive('button');
    interactiveButton.target.setLabel(buttonLabel);
    interactiveButton.target.setCallback(callbackFunction);
    interactiveButton.assembly();

    let elementButton = interactiveButton.target.element.querySelector('.interactive__button');
    elementButton.classList.add('modal__footer-button');

    this.buttons.push(interactiveButton);
  }

  assemblyHeader(modalTitle = 'Modal window') {
    let elementHeader = document.createElement('div');
    elementHeader.classList.add('modal__header-container');

    let elementHeaderLabel = document.createElement('div');
    elementHeaderLabel.innerHTML = modalTitle;
    elementHeaderLabel.classList.add('modal__header-label');

    let interactiveButtonClose = new Interactive('button');
    interactiveButtonClose.target.setLabel('X');
    interactiveButtonClose.target.setCallback(() => {
      this.close();
    });
    interactiveButtonClose.assembly();

    let elementButtonClose = interactiveButtonClose.target.element.querySelector('.interactive__button');
    elementButtonClose.classList.add('modal__header-button');

    elementHeader.append(elementHeaderLabel);
    elementHeader.append(interactiveButtonClose.target.element);

    return elementHeader;
  }

  assemblyBody(modalContent = 'Modal body content') {
    let elementBody = document.createElement('div');
    elementBody.append(modalContent);
    elementBody.classList.add('modal__body-container');

    return elementBody;
  }

  assemblyFooter() {
    let elementFooter = document.createElement('div');
    elementFooter.classList.add('modal__footer-container');

    this.buttons.forEach((button, buttonIndex) => {
      elementFooter.appendChild(button.target.element);
    });

    return elementFooter;
  }

  assemblyWrapper() {
    let elementWrapper = document.createElement('div');
    elementWrapper.classList.add('interactive__modal-wrapper');
    elementWrapper.style.opacity = 0;

    return elementWrapper;
  }

  assembly() {
    let elementHeader = this.assemblyHeader(this.title);
    let elementBody = this.assemblyBody(this.content);
    let elementFooter = this.assemblyFooter();
    let elementWrapper = this.assemblyWrapper();
    let element = document.createElement('div');

    let elementModal = document.createElement('div');
    elementModal.classList.add('interactive__modal');
    elementModal.style.width = this.width + 'px';

    elementModal.appendChild(elementHeader);
    elementModal.appendChild(elementBody);
    elementModal.appendChild(elementFooter);
    elementWrapper.appendChild(elementModal);
    element.appendChild(elementWrapper);

    this.element = element;
  }
}