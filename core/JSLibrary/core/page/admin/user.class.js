/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../../interactive.class.js";
import {URLParser} from "../../../urlParser.class.js";

export class PageUser {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null, block: null, unblock: null};
  }

  init() {
    let searchParams = new URLParser(), locales, userData, usersGroups;
    let elementForm = document.querySelector('.form_user');
    
    let interactiveChoicesUsersGroups = new Interactive('choices');

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return fetch('/handler/usersGroups' + '?locale=' + window.CMSCore.locales.admin.name, {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      usersGroups = data.outputData.usersGroups;

      usersGroups.forEach((usersGroup, usersGroupIndex) => {
        interactiveChoicesUsersGroups.target.addItem(usersGroup.title, usersGroup.id);
      });

      interactiveChoicesUsersGroups.target.setName('user_group_id');

      return fetch('/handler/user/' + searchParams.getPathPart(3), {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      userData = data.outputData.user;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {

      if (searchParams.getPathPart(3) != null) {
        usersGroups.forEach((usersGroup, usersGroupIndex) => {
          if (usersGroup.id == userData.groupID) {
            interactiveChoicesUsersGroups.target.setItemSelectedIndex(usersGroupIndex);
          }
        });
      }

      this.buttons.save = new Interactive('button');
      this.buttons.save.target.setLabel(localeData.BUTTON_SAVE_LABEL);
      this.buttons.save.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData(elementForm);
  
        fetch('/handler/user', {
          method: (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH',
          body: formData
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
            let userData = data1.outputData.user;
            window.location.href = '/admin/user/' + userData.id;
          }
  
          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.save.assembly();
  
      this.buttons.block = new Interactive('button');
      this.buttons.block.target.setLabel(localeData.BUTTON_BAN_LABEL);
      this.buttons.block.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData();
        formData.append('user_id', searchParams.getPathPart(3));
        formData.append('user_is_block', 1);
  
        fetch('/handler/user', {
          method: 'PATCH',
          body: formData
        }).then((response) => {
          return response.json();
        }).then((data1) => {
          if (data1.statusCode == 1) {
            this.buttons.unblock.target.element.style.display = 'flex';
            this.buttons.block.target.element.style.display = 'none';
          }
  
          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.block.assembly();
  
      this.buttons.unblock = new Interactive('button');
      this.buttons.unblock.target.setLabel(localeData.BUTTON_UNBAN_LABEL);
      this.buttons.unblock.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData();
        formData.append('user_id', searchParams.getPathPart(3));
        formData.append('user_is_block', 0);
  
        fetch('/handler/user', {
          method: 'PATCH',
          body: formData
        }).then((response) => {
          return response.json();
        }).then((data1) => {
          if (data1.statusCode == 1) {
            this.buttons.unblock.target.element.style.display = 'none';
            this.buttons.block.target.element.style.display = 'flex';
          }
  
          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.unblock.assembly();
  
      this.buttons.delete = new Interactive('button');
      this.buttons.delete.target.setLabel(localeData.BUTTON_DELETE_LABEL);
      this.buttons.delete.target.setCallback((event) => {
        event.preventDefault();
  
        let interactiveModal = new Interactive('modal', {title: localeData.MODAL_USER_DELETE_TITLE, content: localeData.MODAL_USER_DELETE_DESCRIPTION});
        interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
          let formData = new FormData();
          formData.append('user_id', searchParams.getPathPart(3));
  
          fetch('/handler/user/' + searchParams.getPathPart(3), {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data1) => {
            if (data1.statusCode == 1) {
              window.location.href = '/admin/users';
            }
  
            let notification = new PopupNotification(data1.message, document.body, true);
            notification.show();
          });
        });
  
        interactiveModal.target.addButton(localeData.BUTTON_CANCEL_LABEL, () => {
          interactiveModal.target.close();
        });
  
        interactiveModal.assembly();
        document.body.appendChild(interactiveModal.target.element);
        interactiveModal.target.show();
      });
      this.buttons.delete.assembly();
  
      if (searchParams.getPathPart(3) == null) {
        this.buttons.unblock.target.element.style.display = 'none';
        this.buttons.block.target.element.style.display = 'none';
        this.buttons.delete.target.element.style.display = 'none';
        this.buttons.save.target.element.style.display = 'flex';
      } else {
        this.buttons.unblock.target.element.style.display = (userData.isBlocked) ? 'flex' : 'none';
        this.buttons.block.target.element.style.display = (userData.isBlocked) ? 'none' : 'flex';
        this.buttons.delete.target.element.style.display = 'flex';
        this.buttons.save.target.element.style.display = 'flex';
      }

      interactiveChoicesUsersGroups.assembly();
  
      let interactiveFormPanelContainer = document.querySelector('#SYSTEM_E3724126170');
      interactiveFormPanelContainer.append(this.buttons.delete.target.element);
      interactiveFormPanelContainer.append(this.buttons.unblock.target.element);
      interactiveFormPanelContainer.append(this.buttons.block.target.element);
      interactiveFormPanelContainer.append(this.buttons.save.target.element);

      let interactiveChoicesUsersGroupsContainer = document.querySelector('#SYSTEM_E6372840180');
      interactiveChoicesUsersGroupsContainer.append(interactiveChoicesUsersGroups.target.element);
    });
  }
}