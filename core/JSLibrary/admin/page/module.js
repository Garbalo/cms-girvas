'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();
  let buttons = {enable: null, disable: null, install: null, delete: null};

  let moduleBlock = document.querySelector('.module');
  let moduleName = moduleBlock.getAttribute('data-module-name');
  let moduleEnabledStatus = moduleBlock.getAttribute('data-module-enabled-status');
  let moduleInstalledStatus = moduleBlock.getAttribute('data-module-installed-status');
  let interactiveContainerElement = document.querySelector('#E8548530785');

  if (searchParams.getPathPart(2) != null) {
    buttons.enable = new Interactive('button');
    buttons.enable.target.setLabel('Включить');
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
          buttons.enable.target.assembled.style.display = 'none';
          buttons.disable.target.assembled.style.display = 'flex';

          moduleBlock.setAttribute('data-module-enabled-status', 'enabled');
        }

        let notification = new PopupNotification(data.message, document.body, true);
        notification.show();
      });
    });
    buttons.enable.target.assembly();

    buttons.disable = new Interactive('button');
    buttons.disable.target.setLabel('Выключить');
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
          buttons.enable.target.assembled.style.display = 'flex';
          buttons.disable.target.assembled.style.display = 'none';

          moduleBlock.setAttribute('data-module-enabled-status', 'disabled');
        }

        let notification = new PopupNotification(data.message, document.body, true);
        notification.show();
      });
    });
    buttons.disable.target.assembly();

    buttons.install = new Interactive('button');
    buttons.install.target.setLabel('Установить');
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
          buttons.install.target.assembled.style.display = 'none';
          buttons.delete.target.assembled.style.display = 'flex';
          buttons.enable.target.assembled.style.display = 'flex';
          buttons.disable.target.assembled.style.display = 'none';

          moduleBlock.setAttribute('data-module-enabled-status', 'disabled');
          moduleBlock.setAttribute('data-module-installed-status', 'installed');
        }

        let notification = new PopupNotification(data.message, document.body, true);
        notification.show();
      });
    });
    buttons.install.target.assembly();

    buttons.delete = new Interactive('button');
    buttons.delete.target.setLabel('Удалить');
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
          buttons.install.target.assembled.style.display = 'flex';
          buttons.delete.target.assembled.style.display = 'none';
          buttons.enable.target.assembled.style.display = 'none';
          buttons.disable.target.assembled.style.display = 'none';

          moduleBlock.setAttribute('data-module-enabled-status', 'disabled');
          moduleBlock.setAttribute('data-module-installed-status', 'not-installed');
        }

        let notification = new PopupNotification(data.message, document.body, true);
        notification.show();
      });
    });
    buttons.delete.target.assembly();

    if (moduleInstalledStatus == 'installed') {
      if (moduleEnabledStatus == 'enabled') {
        buttons.enable.target.assembled.style.display = 'none';
        buttons.disable.target.assembled.style.display = 'flex';
      }

      if (moduleEnabledStatus == 'disabled') {
        buttons.enable.target.assembled.style.display = 'flex';
        buttons.disable.target.assembled.style.display = 'none';
      }

      buttons.install.target.assembled.style.display = 'none';
      buttons.delete.target.assembled.style.display = 'flex';
    } else {
      buttons.install.target.assembled.style.display = 'flex';
      buttons.delete.target.assembled.style.display = 'none';
      buttons.enable.target.assembled.style.display = 'none';
      buttons.disable.target.assembled.style.display = 'none';
    }

    interactiveContainerElement.append(buttons.enable.target.assembled);
    interactiveContainerElement.append(buttons.disable.target.assembled);
    interactiveContainerElement.append(buttons.install.target.assembled);
    interactiveContainerElement.append(buttons.delete.target.assembled);
  }
});