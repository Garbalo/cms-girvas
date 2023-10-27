'use strict';

import {Interactive} from "../../interactive.class.js";

export class ElementButton {
  constructor(element = null) {
    this.element = element;
    this.stringLabel = 'Click';
    this.iconLabel = '???';
    this.clickEvent = (event) => {
      event.preventDefault();
    };
  }

  setStringLabel(string) {
    this.stringLabel = string;
  }

  setClickEvent(callback) {
    this.clickEvent = callback;
  }

  init(attributes = {}) {
    let interactiveElement = new Interactive('button');
    interactiveElement.target.setLabel(this.stringLabel);
    interactiveElement.target.setCallback(this.clickEvent);
    interactiveElement.assembly();

    if (typeof attributes.role != 'undefined') {
      interactiveElement.target.element.setAttribute('role', attributes.role);
    }
    
    this.element = interactiveElement.target.element;
  }
}