/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../interactive.class.js";
import {ElementButton} from "../../interactive/form/elementButton.class.js";
import {ElementTextarea} from "../../interactive/form/elementTextarea.class.js";

export class PageProfile {
  constructor(params = {}) {

  }

  init() {
    let locales;

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.base.getData();
    }).then((localeData) => {
      this.localeBaseData = localeData;

      let profileAvatarElement = document.querySelector('[role="profile-avatar"]');
      let profileFormElement = document.querySelector('#SYSTEM_F0648538312');
      if (profileAvatarElement != null && profileFormElement != null) {
        let profileAvatarInput = document.createElement('input');
        profileAvatarInput.setAttribute('type', 'file');
        profileAvatarInput.setAttribute('name', 'user_avatar');

        profileAvatarInput.style.display = 'none';

        profileFormElement.append(profileAvatarInput);
        profileAvatarInput.addEventListener('change', (event) => {
          let formInputUserID = profileFormElement.querySelector('input[name="user_id"]');
          if (profileAvatarInput.files.length > 0 && formInputUserID != null) {
            let formData = new FormData();
            formData.append('user_id', formInputUserID.getAttribute('value'));
            formData.append('avatarFile', profileAvatarInput.files[0]);

            fetch('/handler/user/avatar?localeMessage=' + window.CMSCore.locales.base.name, {
              method: 'POST',
              body: formData
            }).then((response) => {
              return (response.ok) ? response.json() : Promise.reject(response);
            }).then((data) => {
              let notification = new PopupNotification(data.message, document.body, true);
              notification.show();

              if (Object.hasOwn(data.outputData, 'file')) {
                let fileName, fileURL;

                fileName = data.outputData.file.fullname;
                fileURL = data.outputData.file.url;

                profileAvatarElement.style.backgroundImage = `url('${fileURL}')`;
                profileAvatarInput.remove();
              }
            });
          }
        });

        profileAvatarElement.addEventListener('click', (event) => {
          profileAvatarInput.click();
        });
      }
    });
  }
}