/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

class PopupNotification {
  constructor(content, containerTarget, isPopup = false) {
    this.content = content;
    this.containerTarget = containerTarget;
    this.assembled = '';
    this.isPopup = isPopup;
    this.liveTimer = null;

    this.assemblyTemplate();
  }

  assemblyTemplate() {
    let notificationWrapper = document.createElement('div');
    let notification = document.createElement('div');
    let notificationContainer = document.createElement('div');

    notificationWrapper.classList.add('notification-wrapper');

    if (this.isPopup) {
      notificationWrapper.classList.add('notification-wrapper_is-popup');
    } else {
      notificationWrapper.classList.add('notification-wrapper_is-static');
    }

    notification.classList.add('notification');
    notificationContainer.classList.add('notification__container');
    
    notificationWrapper.appendChild(notification);
    notification.appendChild(notificationContainer);

    notificationContainer.innerHTML = this.content;

    this.assembled = notificationWrapper;
  }

  setContent(content) {
    this.content = content;
  }

  delete() {
    delete this;
  }

  startLiveTimer() {
    this.liveTimer = setTimeout(() => {
      this.hide();
    }, 10000);
  }

  show() {
    if (!this.isPopup) {
      this.containerTarget.innerHTML = '';
    }

    this.containerTarget.appendChild(this.assembled);

    if (this.isPopup) {
      setTimeout(() => {
        let notificationContainerElement = this.assembled.querySelector('.notification__container');
        this.assembled.classList.add('notification-wrapper_is-showed');
        notificationContainerElement.innerHTML = this.content;
        this.startLiveTimer();
      }, 10);
    }
  }

  hide() {
    setTimeout(() => {
      this.assembled.classList.remove('notification-wrapper_is-showed');
      setTimeout(() => {
        this.assembled.remove();
        this.delete();
      }, 200);
    }, 10);
  }
}