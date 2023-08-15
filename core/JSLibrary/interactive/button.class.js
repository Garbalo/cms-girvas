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
    element.classList.add('interactive');

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
    buttonLabelElement.innerText = (this.label != null) ? this.label : 'Button';
    buttonElement.append(buttonLabelElement);

    element.append(buttonElement);

    this.assembled = element;
  }
}