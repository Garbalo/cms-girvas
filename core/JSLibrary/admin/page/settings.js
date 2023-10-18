'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let urlp = new URLParser();

  if (urlp.getPathPart(3) == 'security') {
    let logicBlocks = document.querySelectorAll('[type="checkbox"]');
    logicBlocks.forEach((element, elementIndex) => {
      let logicBlockTargetElement;

      if (element.hasAttribute('data-logic-block')) {
        let logicBlock = element.getAttribute('data-logic-block');
        logicBlockTargetElement = document.getElementById(logicBlock);
        if (!element.checked) {
          logicBlockTargetElement.setAttribute('disabled', 'disabled');
        }
      }

      let statusBlock = element.getAttribute('data-status-block');
      let statusBlockTargetElement = document.getElementById(statusBlock);
      element.addEventListener('change', (event) => {
        if (!element.checked) {
          statusBlockTargetElement.value = 'off';
        } else {
          statusBlockTargetElement.value = 'on';
        }

        if (element.hasAttribute('data-logic-block')) {
          if (logicBlockTargetElement.hasAttribute('disabled')) {
            logicBlockTargetElement.removeAttribute('disabled');
          } else {
            logicBlockTargetElement.setAttribute('disabled', 'disabled');
          }
        }
      });
    });
  }

  if (urlp.getPathPart(3) == null || urlp.getPathPart(3) == 'base') {
    let logicBlocks = document.querySelectorAll('[type="checkbox"]');
    logicBlocks.forEach((element, elementIndex) => {
      let logicBlockTargetElement;

      if (element.hasAttribute('data-logic-block')) {
        let logicBlock = element.getAttribute('data-logic-block');
        logicBlockTargetElement = document.getElementById(logicBlock);
        if (!element.checked) {
          logicBlockTargetElement.setAttribute('disabled', 'disabled');
        }
      }

      let statusBlock = element.getAttribute('data-status-block');
      let statusBlockTargetElement = document.getElementById(statusBlock);
      element.addEventListener('change', (event) => {
        if (!element.checked) {
          statusBlockTargetElement.value = 'off';
        } else {
          statusBlockTargetElement.value = 'on';
        }

        if (element.hasAttribute('data-logic-block')) {
          if (logicBlockTargetElement.hasAttribute('disabled')) {
            logicBlockTargetElement.removeAttribute('disabled');
          } else {
            logicBlockTargetElement.setAttribute('disabled', 'disabled');
          }
        }
      });
    });

    fetch('/handler/timezones', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      fetch('/handler/timezone', {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        let timezones = data.outputData.timezones;
        let timezoneSelected = data1.outputData.timezone;

        let interactiveChoicesSettingsTimezone = new Interactive('choices');
        timezones.forEach((timezone, timezoneIndex) => {
          let timezoneElement = document.createElement('span');
          timezoneElement.innerText = `${timezone.name} (${timezone.utc})`;
          let timezoneElementTemplate = document.createElement('template');
          timezoneElementTemplate.innerHTML += timezoneElement.outerHTML;

          interactiveChoicesSettingsTimezone.target.addItem(timezoneElementTemplate.innerHTML, timezone.name);
        });

        timezones.forEach((timezone, timezoneIndex) => {
          if (timezone.name === timezoneSelected.name) {
            interactiveChoicesSettingsTimezone.target.setItemSelectedIndex(timezoneIndex);
          }
        });

        interactiveChoicesSettingsTimezone.target.setName('setting_base_timezone');
        interactiveChoicesSettingsTimezone.assembly();

        document.querySelector('#TC6474389679').prepend(interactiveChoicesSettingsTimezone.target.assembled);
      });
    });

    fetch('/handler/charsets', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      fetch('/handler/charset', {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        let charsets = data.outputData.charsets;
        let charsetSelected = data1.outputData.charset;
        let interactiveChoicesSettingsCharset = new Interactive('choices');

        charsets.forEach((charset, charsetIndex) => {
          interactiveChoicesSettingsCharset.target.addItem(charset, charset);
        });

        charsets.forEach((charset, charsetIndex) => {
          if (charset === charsetSelected) {
            interactiveChoicesSettingsCharset.target.setItemSelectedIndex(charsetIndex);
          }
        });

        interactiveChoicesSettingsCharset.target.setName('setting_base_site_charset');
        interactiveChoicesSettingsCharset.assembly();

        document.querySelector('#TC6474389682').append(interactiveChoicesSettingsCharset.target.assembled);
      });
    });

    fetch('/handler/ew-status', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      let engineeringWorksStatus = data.outputData.status;
      let engineeringWorksHiddenInput = document.getElementById('I1474308110');
      engineeringWorksHiddenInput.value = engineeringWorksStatus;
    });

    fetch('/handler/locales', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      fetch('/handler/locale/base', {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        let locales = data.outputData.locales;
        let localeSelected = data1.outputData.locale;
        let interactiveChoicesSettingsBaseLocale = new Interactive('choices');

        locales.forEach((locale, localeIndex) => {
          let localeTitle = locale.title;
          let localeIconURL = locale.iconURL;
          let localeName = locale.name;
          let localeISO639_2 = locale.iso639_2;

          let localeIconImageElement = document.createElement('img');
          localeIconImageElement.setAttribute('src', localeIconURL);
          localeIconImageElement.setAttribute('alt', localeTitle);

          let localeLabelElement = document.createElement('span');
          localeLabelElement.innerText = localeTitle;

          let localeTemplate = document.createElement('template');
          localeTemplate.innerHTML += localeIconImageElement.outerHTML;
          localeTemplate.innerHTML += localeLabelElement.outerHTML;

          interactiveChoicesSettingsBaseLocale.target.addItem(localeTemplate.innerHTML, localeName);
        });

        locales.forEach((locale, localeIndex) => {
          if (locale.name === localeSelected.name) {
            interactiveChoicesSettingsBaseLocale.target.setItemSelectedIndex(localeIndex);
          }
        });

        interactiveChoicesSettingsBaseLocale.target.setName('setting_base_locale')
        interactiveChoicesSettingsBaseLocale.assembly();

        document.querySelector('#TC6474389680').append(interactiveChoicesSettingsBaseLocale.target.assembled);
      });

      fetch('/handler/locale/admin', {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        let locales = data.outputData.locales;
        let localeSelected = data1.outputData.locale;
        let interactiveChoicesSettingsAdminLocale = new Interactive('choices');

        // locales.sort((a, b) => {
        //   return a.name === localeSelected.name ? -1 : b.name === localeSelected.name ? 1 : 0;
        // });

        console.log(locales);

        locales.forEach((locale, localeIndex) => {
          let localeTitle = locale.title;
          let localeIconURL = locale.iconURL;
          let localeName = locale.name;
          let localeISO639_2 = locale.iso639_2;

          let localeIconImageElement = document.createElement('img');
          localeIconImageElement.setAttribute('src', localeIconURL);
          localeIconImageElement.setAttribute('alt', localeTitle);

          let localeLabelElement = document.createElement('span');
          localeLabelElement.innerText = localeTitle;

          let localeTemplate = document.createElement('template');
          localeTemplate.innerHTML += localeIconImageElement.outerHTML;
          localeTemplate.innerHTML += localeLabelElement.outerHTML;

          interactiveChoicesSettingsAdminLocale.target.addItem(localeTemplate.innerHTML, localeName);
        });

        locales.forEach((locale, localeIndex) => {
          if (locale.name === localeSelected.name) {
            interactiveChoicesSettingsAdminLocale.target.setItemSelectedIndex(localeIndex);
          }
        });

        interactiveChoicesSettingsAdminLocale.target.setName('setting_base_admin_locale');
        interactiveChoicesSettingsAdminLocale.assembly();

        document.querySelector('#TC6474389681').append(interactiveChoicesSettingsAdminLocale.target.assembled);
      });
    });
  }
});