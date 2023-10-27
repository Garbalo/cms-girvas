'use strict';

export class ElementInput {
  constructor(element = null) {
    this.element = element;
  }

  init(attributes = {}) {
    let element = document.createElement('input');

    if (typeof attributes.id != 'undefined') {
      element.setAttribute('id', attributes.id);
    }

    if (typeof attributes.name != 'undefined') {
      element.setAttribute('name', attributes.name);
    }

    if (typeof attributes.type != 'undefined') {
      element.setAttribute('type', attributes.type);
    }

    if (typeof attributes.placeholder != 'undefined') {
      element.setAttribute('placeholder', attributes.placeholder);
    }

    if (typeof attributes.role != 'undefined') {
      element.setAttribute('role', attributes.role);
    }

    element.classList.add('form__input');

    if (element.hasAttribute('type')) {
      let elementType = element.getAttribute('type');
      element.classList.add(`form__input_${elementType.toLowerCase()}`);
    }

    this.element = element;
  }
}