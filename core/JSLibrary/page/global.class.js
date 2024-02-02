/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../interactive.class.js";
import {ElementButton} from "../interactive/form/elementButton.class.js";
import {ElementTextarea} from "../interactive/form/elementTextarea.class.js";
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
    let systemGlobalElements = document.querySelectorAll('[id^=SYSTEM_GE_]');
    systemGlobalElements.forEach((element, elementIndex) => {
      if (element.id.includes('IMC_00000001')) {
        element.addEventListener('click', (event) => {
          event.preventDefault();

          /** @type {Interactive} */
          let authForm = new Interactive('form');
          authForm.target.init({
            method: 'POST',
            action: `/handler/auth`
          });

          /** @type {ElementInput} */
          let authFormInputLogin = authForm.target.createElementInput();
          authFormInputLogin.init({
            name: 'user_login',
            type: 'text'
          });
          authFormInputLogin.element.placeholder = 'Логин';

          /** @type {ElementInput} */
          let authFormInputPassword = authForm.target.createElementInput();
          authFormInputPassword.init({
            name: 'user_password',
            type: 'password'
          });
          authFormInputPassword.element.placeholder = 'Пароль';

          authForm.target.element.firstChild.append(authFormInputLogin.element);
          authForm.target.element.firstChild.append(authFormInputPassword.element);

          let interactiveModal = new Interactive('modal', {title: "Авторизация", content: "Для продолжения необходимо ввести свои авторизационные данные.", width: 300});
          interactiveModal.target.addButton('Авторизация', () => {
            authForm.target.send();
          });

          interactiveModal.target.addButton('Восстановление', () => {
            /** @type {Interactive} */
            let requestForm = new Interactive('form');
            requestForm.target.init({
              method: 'POST',
              action: `/handler/user/request-password-reset`
            });
            
            /** @type {Interactive} */
            let interactiveSubModal = new Interactive('modal', {title: "Восстановление пароля", content: "Для восстановления пароля необходимо указать логин или адрес электронной почты, который привязан к аккаунту. На почту придет письмо с ссылкой для восстановления.", width: 300});
            interactiveSubModal.target.addButton('Отправить письмо-запрос', () => {
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
            requestFormInput.element.placeholder = 'Логин или E-Mail';

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
  }
}