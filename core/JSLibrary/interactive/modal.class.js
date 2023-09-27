'use strict';

import {Interactive} from "../interactive.class.js";

export class Modal {
  constructor(modalTitle, modalContent, modalWidth) {
    this.title = modalTitle;
    this.content = modalContent;
    this.width = modalWidth;
    this.opacity = 0;
    this.assembled = null;
    this.buttons = [];
    this.onCloseCallbackFunction = () => {};
  }

  show() {
    let elementWrapper = this.assembled.querySelector('.interactive__modal-wrapper');
    let interval = setInterval(() => {
      if (this.opacity < 1) {
        this.opacity += 0.01;
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
    let elementWrapper = this.assembled.querySelector('.interactive__modal-wrapper');
    let interval = setInterval(() => {
      if (this.opacity > 0) {
        this.opacity -= 0.01;
        elementWrapper.style.opacity = this.opacity;
      } else {
        clearInterval(interval);
        this.assembled.remove();

        this.onCloseCallbackFunction();
      }
    }, 1);
  }

  addButton(buttonLabel, callbackFunction) {
    let interactiveButton = new Interactive('button');
    interactiveButton.target.setLabel(buttonLabel);
    interactiveButton.target.setCallback(callbackFunction);
    interactiveButton.target.assembly();

    let elementButton = interactiveButton.target.assembled.querySelector('.interactive__button');
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
    interactiveButtonClose.target.assembly();

    let elementButtonClose = interactiveButtonClose.target.assembled.querySelector('.interactive__button');
    elementButtonClose.classList.add('modal__header-button');

    elementHeader.append(elementHeaderLabel);
    elementHeader.append(interactiveButtonClose.target.assembled);

    return elementHeader;
  }

  assemblyBody(modalContent = 'Modal body content') {
    let elementBody = document.createElement('div', modalContent);
    elementBody.innerHTML = modalContent;
    elementBody.classList.add('modal__body-container');

    return elementBody;
  }

  assemblyFooter() {
    let elementFooter = document.createElement('div');
    elementFooter.classList.add('modal__footer-container');

    this.buttons.forEach((button, buttonIndex) => {
      elementFooter.appendChild(button.target.assembled);
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

    let elementInteractive = document.createElement('div');
    elementInteractive.classList.add('interactive');

    let elementModal = document.createElement('div');
    elementModal.classList.add('interactive__modal');
    elementModal.style.width = this.width;

    elementModal.appendChild(elementHeader);
    elementModal.appendChild(elementBody);
    elementModal.appendChild(elementFooter);
    elementWrapper.appendChild(elementModal);
    elementInteractive.appendChild(elementWrapper);

    this.assembled = elementInteractive;
  }
}