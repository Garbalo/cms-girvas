'use strict';

import {Interactive} from "../../interactive.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let interactiveChoicesSettingsTimezone = new Interactive('choices');

  let timezoneElement = document.createElement('span');
  timezoneElement.innerText = 'GMT+3 (Москва)';
  let timezoneElementTemplate = document.createElement('template');
  timezoneElementTemplate.innerHTML += timezoneElement.outerHTML;

  interactiveChoicesSettingsTimezone.target.addItem(timezoneElementTemplate.innerHTML, 'gmt+3');
  interactiveChoicesSettingsTimezone.target.setName('setting_base_timezone');
  interactiveChoicesSettingsTimezone.assembly();

  document.querySelector('#TC6474389679').prepend(interactiveChoicesSettingsTimezone.target.assembled);

  let interactiveChoicesSettingsEngineeringWorks = new Interactive('choices');

  let engineeringWorksElementOn = document.createElement('span');
  engineeringWorksElementOn.innerText = 'Включено';
  let engineeringWorksElementOnTemplate = document.createElement('template');
  engineeringWorksElementOnTemplate.innerHTML += engineeringWorksElementOn.outerHTML;

  let engineeringWorksElementOff = document.createElement('span');
  engineeringWorksElementOff.innerText = 'Отключено';
  let engineeringWorksElementOffTemplate = document.createElement('template');
  engineeringWorksElementOffTemplate.innerHTML += engineeringWorksElementOn.outerHTML;

  interactiveChoicesSettingsEngineeringWorks.target.addItem(engineeringWorksElementOn.innerHTML, 'on');
  interactiveChoicesSettingsEngineeringWorks.target.addItem(engineeringWorksElementOff.innerHTML, 'off');
  interactiveChoicesSettingsEngineeringWorks.target.setName('setting_base_engineering_works');
  interactiveChoicesSettingsEngineeringWorks.assembly();

  document.querySelector('#TC6474389682').prepend(interactiveChoicesSettingsEngineeringWorks.target.assembled);

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

        interactiveChoicesSettingsBaseLocale.target.addItem(localeTemplate.innerHTML, localeName);
        interactiveChoicesSettingsBaseLocale.target.setName('setting_base_locale')
      });

      locales.forEach((locale, localeIndex) => {
        if (locale.name === localeSelected.name) {
          interactiveChoicesSettingsBaseLocale.target.setItemSelectedIndex(localeIndex);
        }
      });

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
        interactiveChoicesSettingsAdminLocale.target.setName('setting_base_admin_locale');
      });

      locales.forEach((locale, localeIndex) => {
        if (locale.name === localeSelected.name) {
          interactiveChoicesSettingsAdminLocale.target.setItemSelectedIndex(localeIndex);
        }
      });

      interactiveChoicesSettingsAdminLocale.assembly();

      document.querySelector('#TC6474389681').append(interactiveChoicesSettingsAdminLocale.target.assembled);
    });
  });
});