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

export class PageModule {
  constructor(params = {}) {
    
  }

  init() {
    let searchParams = new URLParser(), locales;
    let buttons = {enable: null, disable: null, install: null, delete: null};

    let moduleBlock = document.querySelector('.module');
    let moduleName = moduleBlock.getAttribute('data-module-name');
    let moduleEnabledStatus = moduleBlock.getAttribute('data-module-enabled-status');
    let moduleInstalledStatus = moduleBlock.getAttribute('data-module-installed-status');
    let interactiveContainerElement = document.querySelector('#E8548530785');

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {

      if (searchParams.getPathPart(2) != null) {
        buttons.enable = new Interactive('button');
        buttons.enable.target.setLabel(localeData.BUTTON_ENABLE_LABEL);
        buttons.enable.target.setCallback(() => {
          let formData = new FormData();
          formData.append('module_name', moduleName);
          formData.append('module_event', 'enable');
    
          fetch('/handler/module', {
            method: 'PATCH',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data) => {
            if (data.statusCode == 1) {
              buttons.enable.target.element.style.display = 'none';
              buttons.disable.target.element.style.display = 'flex';
    
              moduleBlock.setAttribute('data-module-enabled-status', 'enabled');
            }
    
            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          });
        });
        buttons.enable.assembly();
    
        buttons.disable = new Interactive('button');
        buttons.disable.target.setLabel(localeData.BUTTON_DISABLE_LABEL);
        buttons.disable.target.setCallback(() => {
          let formData = new FormData();
          formData.append('module_name', moduleName);
          formData.append('module_event', 'disable');
    
          fetch('/handler/module', {
            method: 'PATCH',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data) => {
            if (data.statusCode == 1) {
              buttons.enable.target.element.style.display = 'flex';
              buttons.disable.target.element.style.display = 'none';
    
              moduleBlock.setAttribute('data-module-enabled-status', 'disabled');
            }
    
            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          });
        });
        buttons.disable.assembly();
    
        buttons.install = new Interactive('button');
        buttons.install.target.setLabel(localeData.BUTTON_INSTALL_LABEL);
        buttons.install.target.setCallback(() => {
          let formData = new FormData();
          formData.append('module_name', moduleName);
          formData.append('module_event', 'install');
    
          fetch('/handler/module', {
            method: 'POST',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data) => {
            if (data.statusCode == 1) {
              buttons.install.target.element.style.display = 'none';
              buttons.delete.target.element.style.display = 'flex';
              buttons.enable.target.element.style.display = 'flex';
              buttons.disable.target.element.style.display = 'none';
    
              moduleBlock.setAttribute('data-module-enabled-status', 'disabled');
              moduleBlock.setAttribute('data-module-installed-status', 'installed');
            }
    
            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          });
        });
        buttons.install.assembly();
    
        buttons.delete = new Interactive('button');
        buttons.delete.target.setLabel(localeData.BUTTON_DELETE_LABEL);
        buttons.delete.target.setCallback(() => {
          let formData = new FormData();
          formData.append('module_name', moduleName);
    
          fetch('/handler/module', {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data) => {
            if (data.statusCode == 1) {
              buttons.install.target.element.style.display = 'flex';
              buttons.delete.target.element.style.display = 'none';
              buttons.enable.target.element.style.display = 'none';
              buttons.disable.target.element.style.display = 'none';
    
              moduleBlock.setAttribute('data-module-enabled-status', 'disabled');
              moduleBlock.setAttribute('data-module-installed-status', 'not-installed');
            }
    
            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          });
        });
        buttons.delete.assembly();
    
        if (moduleInstalledStatus == 'installed') {
          if (moduleEnabledStatus == 'enabled') {
            buttons.enable.target.element.style.display = 'none';
            buttons.disable.target.element.style.display = 'flex';
          }
    
          if (moduleEnabledStatus == 'disabled') {
            buttons.enable.target.element.style.display = 'flex';
            buttons.disable.target.element.style.display = 'none';
          }
    
          buttons.install.target.element.style.display = 'none';
          buttons.delete.target.element.style.display = 'flex';
        } else {
          buttons.install.target.element.style.display = 'flex';
          buttons.delete.target.element.style.display = 'none';
          buttons.enable.target.element.style.display = 'none';
          buttons.disable.target.element.style.display = 'none';
        }
    
        interactiveContainerElement.append(buttons.enable.target.element);
        interactiveContainerElement.append(buttons.disable.target.element);
        interactiveContainerElement.append(buttons.install.target.element);
        interactiveContainerElement.append(buttons.delete.target.element);
      }
    });
  }
}