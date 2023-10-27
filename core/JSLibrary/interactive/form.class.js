'use strict';

import {Interactive} from "../interactive.class.js";
import {ElementTextarea} from "./form/elementTextarea.class.js";
import {ElementInput} from "./form/elementInput.class.js";
import {ElementButton} from "./form/elementButton.class.js";

export class Form {
  constructor(element = null) {
    this.element = element;
    this.successCallback = (data) => {};
    this.failCallback = (error) => {};
  }

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

  createElementTextarea(attributes = {}) {
    let element = new ElementTextarea();
    element.init(attributes);
    return element;
  }

  createElementInput(attributes = {}) {
    let element = new ElementInput();
    element.init(attributes);
    return element;
  }

  createElementButton(attributes = {}) {
    let element = new ElementButton();
    element.init(attributes);
    return element;
  }

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

      // Переписать эту дичь
      if (typeof(data.outputData.reload) == 'undefined' && typeof(data.outputData.href) == 'undefined') {
        let notificationContainerTarget, notificationIsPopup = false;
        if (typeof(data.outputData.notificationContainerTargetID) == 'undefined') {
          notificationContainerTarget = document.body;
          notificationIsPopup = true;
        } else {
          notificationContainerTarget = document.querySelector('#' + data.outputData.notificationContainerTargetID);
          if (Object.is(notificationContainerTarget, null)) {
            notificationContainerTarget = document.body;
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
      console.error('Interactive form getted error: ' + error);
      this.failCallback(error);
    });
  }

  assembly() {
    //
  }
}