'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();
  
  // Требуется оптимизация
  // Promise.all([fetches...]);

  fetch('/handler/locales', {
    method: 'GET'
  }).then((response) => {
    return response.json();
  }).then((data) => {
    let searchParams = new URLParser();

    if (searchParams.getPathPart(3) != null) {
      fetch('/handler/locale/base', {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        let locales = data.outputData.locales;
        let localeSelected = data1.outputData.locale;
        let interactiveChoices = new Interactive('choices');
      
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

          interactiveChoices.target.addItem(localeTemplate.innerHTML, localeName);
        });

        locales.forEach((locale, localeIndex) => {
          if (locale.name === localeSelected.name) {
            fetch('/handler/entry/category/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
              method: 'GET'
            }).then((response) => {
              return response.json();
            }).then((data2) => {
              fetch('/handler/entry/categories' + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return response.json();
              }).then((data3) => {
                let entriesCategories = data3.outputData.entriesCategories;
                let interactiveChoicesParents = new Interactive('choices');
                interactiveChoicesParents.target.addItem('', 0);
                entriesCategories.forEach((entriesCategory, entriesCategoryIndex) => {
                  if (entriesCategory.id != data2.outputData.entryCategory.id) {
                    interactiveChoicesParents.target.addItem(entriesCategory.title, entriesCategory.id);
                  }

                  if (entriesCategory.id == data2.outputData.entryCategory.parentID) {
                    interactiveChoicesParents.target.setItemSelectedIndex(entriesCategoryIndex);
                  }
                });
                interactiveChoicesParents.target.setName('entries_category_parent_id');
                interactiveChoicesParents.assembly();

                let interactiveContainer = document.querySelector('#TC6474389602');
                interactiveContainer.innerHTML = '';
                interactiveContainer.append(interactiveChoicesParents.target.element);
              });
            });

            interactiveChoices.target.setItemSelectedIndex(localeIndex);
          }
        });

        interactiveChoices.assembly();

        let interactiveContainerElement = document.querySelector('#E8548530785');
        interactiveContainerElement.append(interactiveChoices.target.element);
        
        let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
        interactiveChoicesSelectElement.addEventListener('change', (event) => {
          let entryDescriptionTextareaElement = document.querySelector('[role="entriesCategoryDescription"]');
          let entryTitleInputElement = document.querySelector('[role="entriesCategoryTitle"]');
          
          locales.forEach((locale, localeIndex) => {
            if (locale.name == event.target.value) {
              entryDescriptionTextareaElement.setAttribute('name', 'entries_category_description_' + locale.iso639_2);
              entryTitleInputElement.setAttribute('name', 'entries_category_title_' + locale.iso639_2);
            }
          });
        });
      });
    }
  });
});