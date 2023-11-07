'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let locales = [], localeSelected = {};
  let searchParams = new URLParser();

  if (searchParams.getPathPart(3) != null) {
    let usersGroupID = searchParams.getPathPart(3);

    fetch('/handler/locales', {
      method: 'GET'
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return fetch('/handler/locale/base', {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      localeSelected = data.outputData.locale;

      let interactiveChoicesLocales = new Interactive('choices');
      locales.forEach((locale, localeIndex) => {
        let localeTitle = locale.title;
        let localeIconURL = locale.iconURL;
        let localeName = locale.name;

        let localeIconImageElement = document.createElement('img');
        localeIconImageElement.setAttribute('src', localeIconURL);
        localeIconImageElement.setAttribute('alt', localeTitle);

        let localeLabelElement = document.createElement('span');
        localeLabelElement.innerText = localeTitle;

        let localeTemplate = document.createElement('template');
        localeTemplate.innerHTML += localeIconImageElement.outerHTML;
        localeTemplate.innerHTML += localeLabelElement.outerHTML;

        interactiveChoicesLocales.target.addItem(localeTemplate.innerHTML, localeName);
      });

      locales.forEach((locale, localeIndex) => {
        if (locale.name === localeSelected.name) {
          interactiveChoicesLocales.target.setItemSelectedIndex(localeIndex);
        }
      });

      interactiveChoicesLocales.assembly();

      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveChoicesLocales.target.element);

      let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
      interactiveChoicesSelectElement.addEventListener('change', (event) => {
        let usersGroupTitleInputElement = document.querySelector('[role="usersGroupTitle"]');

        locales.forEach((locale, localeIndex) => {
          if (locale.name === event.target.value) {
            fetch('/handler/usersGroup/' + usersGroupID + '?locale=' + event.target.value, {
              method: 'GET'
            }).then((response) => {
              return (response.ok) ? response.json() : Promise.reject(response);
            }).then((data1) => {
              let usersGroupData = data1.outputData.usersGroup;

              usersGroupTitleInputElement.value = usersGroupData.title;

              usersGroupTitleInputElement.setAttribute('name', 'user_group_title_' + locale.iso639_2);
            });
          }
        });
      });
    });
  }
});