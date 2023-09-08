'use strict';

import {Choices} from './interactive/choices.class.js';
import {Button} from './interactive/button.class.js';
import {Modal} from './interactive/modal.class.js';

export class Interactive {
  constructor(interactiveName, interactiveParams = {}) {
    switch (interactiveName) {
      case 'choices': this.target = new Choices(); break;
      case 'button': this.target = new Button(); break;
      case 'modal': this.target = new Modal(interactiveParams.title, interactiveParams.content, interactiveParams.width); break;
    }
  }

  assembly() {
    this.target.assembly();
  }
}