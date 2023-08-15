'use strict';

import {Choices} from './interactive/choices.class.js';
import {Button} from './interactive/button.class.js';

export class Interactive {
  constructor(interactiveName) {
    switch (interactiveName) {
      case 'choices': this.target = new Choices(); break;
      case 'button': this.target = new Button(); break;
    }
  }

  assembly() {
    this.target.assembly();
  }
}