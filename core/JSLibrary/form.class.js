/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

/**
 * Форма (устаревшее)
 */
class Form {
  /**
   * constructor
   * 
   * @param {HTMLFormElement} element
   */
  constructor(element) {
    this.modalParent = null;
    this.setFormElement(element);
  }
  /**
   * Назначить элемент формы
   * 
   * @param {HTMLFormElement} element 
   */
  setFormElement(element) {
    /** @type {HTMLFormElement} */
    this.element = element;
  }
  /**
   * Инициализация элемента формы
   */
  initFormElement() {
    this.element.addEventListener('submit', (event) => {
      event.preventDefault();

      let formMethod = (event.submitter.hasAttribute('formmethod')) ? event.submitter.getAttribute('formmethod') : '';
      this.send(event);
    });
  }
  getFormMethod(submitEvent) {
    if (submitEvent.submitter.hasAttribute('formmethod')) {
      if (submitEvent.submitter.getAttribute('formmethod') != '') {
        return submitEvent.submitter.getAttribute('formmethod');
      }
    }

    return (this.element.hasAttribute('method')) ? this.element.getAttribute('method') : 'POST';
  }
  getFormAction() {
    return (this.element.hasAttribute('action')) ? this.element.getAttribute('action') : '/handler';
  }
  send(submitEvent) {
    /** @type {FormData} */
    let formData = new FormData(this.element);
    let submitterName = submitEvent.submitter.hasAttribute('name') ? submitEvent.submitter.getAttribute('name') : 'submitter';
    formData.append(submitterName, true);

    let notificationLoading = new PopupNotification('Обработка запроса...', document.body, true);
    notificationLoading.show();

    // Отправка через Fetch API
    fetch(this.getFormAction(), {
      method: this.getFormMethod(submitEvent),
      body: formData
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      console.debug('Response by form: ' + data);
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
    });
  }
}