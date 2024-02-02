/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Choices} from './interactive/choices.class.js';
import {Button} from './interactive/button.class.js';
import {Modal} from './interactive/modal.class.js';
import {Form} from './interactive/form.class.js';

export class Interactive {
  constructor(interactiveName, interactiveParams = {}) {
    switch (interactiveName) {
      case 'choices': this.target = new Choices(); break;
      case 'button': this.target = new Button(); break;
      case 'form': this.target = new Form(); break;
      case 'modal': this.target = new Modal(interactiveParams.title, interactiveParams.content, interactiveParams.width); break;
    }
  }

  generateRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);

    return Math.floor(Math.random() * (max - min) + min);
  }

  generateUniqueID() {
    let randomNumber = this.generateRandomInt(100000000000, 999999999999), resultID = 0;

    let interactiveRepetitiveElement = document.querySelector(`[cmsg-interactive-uid="${randomNumber.toString(16)}"]`);
    if (interactiveRepetitiveElement != null) {
      return interactiveRepetitiveElement.generateUniqueID();
    } else {
      resultID = randomNumber;
    }

    return resultID.toString(16).toUpperCase();
  }

  assembly() {
    this.target.assembly();
    this.target.element.setAttribute('cmsg-interactive-uid', this.generateUniqueID());

    this.target.element.classList.add(`interactive`);
    this.target.element.classList.add(`interactive_${this.target.constructor.name.toLocaleLowerCase()}`);
  }
}