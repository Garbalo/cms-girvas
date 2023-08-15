'use strict';

import {Interactive} from "../../interactive.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
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
            interactiveChoices.target.setItemSelectedIndex(localeIndex);
          }
        });

        interactiveChoices.assembly();

        let interactiveContainerElement = document.querySelector('#E8548530785');
        interactiveContainerElement.append(interactiveChoices.target.assembled)

        let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
        interactiveChoicesSelectElement.addEventListener('change', (event) => {
          let entryContentTextareaElement = document.querySelector('[role="entryContent"]');
          let entryDescriptionTextareaElement = document.querySelector('[role="entryDescription"]');
          let entryTitleInputElement = document.querySelector('[role="entryTitle"]');
          
          locales.forEach((locale, localeIndex) => {
            if (locale.name == event.target.value) {
              entryContentTextareaElement.setAttribute('name', 'entry_content_' + locale.iso639_2);
              entryDescriptionTextareaElement.setAttribute('name', 'entry_description_' + locale.iso639_2);
              entryTitleInputElement.setAttribute('name', 'entry_title_' + locale.iso639_2);
              
              fetch('/handler/entry/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return response.json();
              }).then((data) => {
                entryContentTextareaElement.value = data.outputData.entry.content;
                entryDescriptionTextareaElement.value = data.outputData.entry.description;
                entryTitleInputElement.value = data.outputData.entry.title;
              });
            }
          });
        });
      });
    }
  });

  let mediaItemButtonURLInit = (element, mediaFile) => {
    element.addEventListener('click', (event) => {
      if (element.getAttribute('role') == 'media-copy-url') {
        navigator.clipboard.writeText(`/uploads/media/${mediaFile}`);

        let notification = new PopupNotification('Относительная ссылка на файл скопирована.', document.body, true);
        notification.show();
      }
    });
  };

  let mediaListElement = document.body.querySelector('.media-list');
  let mediaFilesListElements = mediaListElement.querySelectorAll('.media-list__item');
  for (let mediaFileElement of mediaFilesListElements) {
    let mediaFile = mediaFileElement.getAttribute('data-media-file');
    let buttons = mediaFileElement.querySelectorAll('button[role]');
    for (let button of buttons) {
      mediaItemButtonURLInit(button, mediaFile);
    }
  }

  let mediaManagerPagesTotal = 1;
  let mediaManagerSelectedPageNumber = 0;
  let mediaManagerContainerElement = document.body.querySelector('.media-manager');
  let mediaManagerListElement = document.body.querySelector('.media-manager__media-list');
  let mediaManagerControllersButtonsElements = mediaManagerContainerElement.querySelectorAll('.media-manager__controller-button');

  fetch('/handler/media/totalPages', {
    method: 'GET'
  }).then((response) => {
    return response.json();
  }).then((data) => {
    mediaManagerPagesTotal = data.outputData.count;
  });

  let page_is_changed = false;

  for (let mediaManagerControllerButtonElement of mediaManagerControllersButtonsElements) {
    mediaManagerControllerButtonElement.addEventListener('click', (event) => {
      page_is_changed = false;

      if (mediaManagerControllerButtonElement.classList.contains('media-manager__controller-button_prev')) {
        if (mediaManagerSelectedPageNumber > 0) {
          mediaManagerSelectedPageNumber--;
          page_is_changed = true;
        }
      }
      
      if (mediaManagerControllerButtonElement.classList.contains('media-manager__controller-button_next')) {
        if (mediaManagerSelectedPageNumber < mediaManagerPagesTotal - 1) {
          mediaManagerSelectedPageNumber++;
          page_is_changed = true;
        }
      }

      console.log(mediaManagerPagesTotal, mediaManagerSelectedPageNumber);

      if (page_is_changed) {
        fetch('/handler/media/list?page=' + mediaManagerSelectedPageNumber, {
          method: 'GET'
        }).then((response) => {
          return response.json();
        }).then((data) => {
          mediaManagerListElement.innerHTML = '';

          for (let listItem of data.outputData.dom.listItems) {
            let listItemContainerElement = document.createElement('div');
            listItemContainerElement.innerHTML = listItem;

            let listItemElement = listItemContainerElement.firstChild;
            let mediaFile = listItemElement.getAttribute('data-media-file');
            let buttons = listItemElement.querySelectorAll('button[role]');
            for (let button of buttons) {
              mediaItemButtonURLInit(button, mediaFile);
            }

            mediaManagerListElement.append(listItemElement);
          }
        });
      }
    });
  }
});