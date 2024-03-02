/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../interactive.class.js";
import {ElementButton} from "../../interactive/form/elementButton.class.js";
import {ElementTextarea} from "../../interactive/form/elementTextarea.class.js";
import {EntryComment} from "./entry/comment.class.js";

export class PageGlobal {
  constructor(params = {}) {
    // ...
  }

  /**
   * SYSTEM_GE_IMC_00000001 | Интерактивный элемент вызова окна авторизации пользователя
   * SYSTEM_GE_IMC_00000002 | 
   */

  init() {
    let locales;

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;

      let footerLocalesListContainerElement = document.querySelector('[role="footer-locales-list"]');
      let footerLocalesListElement = document.createElement('ul');

      footerLocalesListElement.classList.add('locales-list');

      locales.forEach((element, elementIndex) => {
        let footerLocalesListItemElement = document.createElement('li');
        let localeImageElement = document.createElement('img');

        footerLocalesListItemElement.classList.add('locales-list__item');
        footerLocalesListItemElement.classList.add('item');
        localeImageElement.classList.add('item__image');

        localeImageElement.setAttribute('src', element.iconURL);
        localeImageElement.setAttribute('alt', element.title);
        
        footerLocalesListItemElement.addEventListener('click', (event) => {
          event.preventDefault();

          document.cookie = `locale=${element.name}; max-age=max-age-in-seconds; path=/`;
          window.location.reload();
        });

        footerLocalesListItemElement.append(localeImageElement);
        footerLocalesListElement.append(footerLocalesListItemElement);
      });

      footerLocalesListContainerElement.append(footerLocalesListElement);

      return window.CMSCore.locales.base.getData();
    }).then((localeData) => {

      let systemGlobalElements = document.querySelectorAll('[id^=SYSTEM_GE_]');
      systemGlobalElements.forEach((element, elementIndex) => {
        if (element.id.includes('IMC_00000001')) {
          element.addEventListener('click', (event) => {
            event.preventDefault();

            /** @type {Interactive} */
            let authForm = new Interactive('form');
            authForm.target.init({
              method: 'POST',
              action: `/handler/utils/authorization?method=base`
            });

            /** @type {ElementInput} */
            let authFormInputLogin = authForm.target.createElementInput();
            authFormInputLogin.init({
              name: 'user_login',
              type: 'text'
            });
            authFormInputLogin.element.placeholder = localeData.MODAL_AUTHORIZATION_INPUT_LOGIN_PLACEHOLDER;

            /** @type {ElementInput} */
            let authFormInputPassword = authForm.target.createElementInput();
            authFormInputPassword.init({
              name: 'user_password',
              type: 'password'
            });
            authFormInputPassword.element.placeholder = localeData.MODAL_AUTHORIZATION_INPUT_PASSWORD_PLACEHOLDER;

            authForm.target.element.firstChild.append(authFormInputLogin.element);
            authForm.target.element.firstChild.append(authFormInputPassword.element);

            let interactiveModal = new Interactive('modal', {title: localeData.MODAL_AUTHORIZATION_IN_SYSTEM_TITLE, content: localeData.MODAL_AUTHORIZATION_IN_SYSTEM_DESCRIPTION, width: 300});
            interactiveModal.target.addButton(localeData.BUTTON_AUTHORIZATION_LABEL, () => {
              authForm.target.send();
            });

            interactiveModal.target.addButton(localeData.BUTTON_RECOVERY_LABEL, () => {
              /** @type {Interactive} */
              let requestForm = new Interactive('form');
              requestForm.target.init({
                method: 'POST',
                action: `/handler/user/request-password-reset`
              });
              
              /** @type {Interactive} */
              let interactiveSubModal = new Interactive('modal', {title: localeData.MODAL_AUTHORIZATION_RECOVERY_TITLE, content: localeData.MODAL_AUTHORIZATION_RECOVERY_DESCRIPTION, width: 300});
              interactiveSubModal.target.addButton(localeData.BUTTON_SEND_LABEL, () => {
                requestForm.target.send();

                interactiveSubModal.target.close();
                interactiveModal.target.show();
              });

              requestForm.target.successCallback = (data) => {

              };

              /** @type {ElementInput} */
              let requestFormInput = requestForm.target.createElementInput();
              requestFormInput.init({
                name: 'user_login_or_email',
                type: 'text'
              });
              requestFormInput.element.placeholder = localeData.MODAL_AUTHORIZATION_INPUT_PASSWORD_OR_LOGIN_PLACEHOLDER;

              requestForm.target.element.firstChild.append(requestFormInput.element);

              interactiveModal.target.hide();

              interactiveSubModal.assembly();
              let interactiveSubModalBody = interactiveSubModal.target.element.querySelector('.modal__body-container');
              interactiveSubModalBody.append(requestForm.target.element);

              document.body.appendChild(interactiveSubModal.target.element);
              interactiveSubModal.target.show();
            });

            interactiveModal.assembly();
            let interactiveModalBody = interactiveModal.target.element.querySelector('.modal__body-container');
            interactiveModalBody.append(authForm.target.element);

            document.body.appendChild(interactiveModal.target.element);
            interactiveModal.target.show();
          });
        }
      });
    });
  }
}