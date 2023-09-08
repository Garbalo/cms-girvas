'use strict';

import {Interactive} from "../../interactive.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();

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

  if (searchParams.getPathPart(3) != null) {
    let entryPreviewBlockElement = document.querySelector('#SYSTEM_E3754926184');
    let entryPreviewBlockContentContainerElement = entryPreviewBlockElement.querySelector('.page-aside__block-content');
    
    let entryPreviewFormElement = document.createElement('form');
    entryPreviewFormElement.setAttribute('formmethod', 'PATCH');
    entryPreviewFormElement.classList.add('form');
    entryPreviewFormElement.classList.add('form-entry-preview');

    let entryPreviewFormInputFileElement = document.createElement('input');
    entryPreviewFormInputFileElement.setAttribute('type', 'file');
    entryPreviewFormInputFileElement.setAttribute('name', 'entry_preview');
    entryPreviewFormInputFileElement.style.display = 'none';

    let interactiveButtonPreviewUpload = new Interactive('button');
    interactiveButtonPreviewUpload.target.setLabel('Загрузить');
    interactiveButtonPreviewUpload.target.setCallback((event) => {
      event.preventDefault();
      entryPreviewFormInputFileElement.click();
    });
    interactiveButtonPreviewUpload.target.assembly();

    let entryPreviewImageContainerElement = document.createElement('div');
    entryPreviewImageContainerElement.classList.add('form-entry-preview__container-image');

    fetch('/handler/entry/' + searchParams.getPathPart(3), {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      if (data.statusCode == 1) {
        if (data.outputData.entry.previewURL != '') {
          let imageElement = document.createElement('img');
          imageElement.setAttribute('src', data.outputData.entry.previewURL);
          imageElement.style.width = '100%';
          entryPreviewImageContainerElement.innerHTML = '';
          entryPreviewImageContainerElement.appendChild(imageElement);
        }
      }
    });

    entryPreviewFormInputFileElement.addEventListener('change', (event) => {
      event.preventDefault();
      
      let file = event.target.files[0];
      let fileReader = new FileReader();

      if (!fileReader) {
        console.error('FileReader не поддерживается, невозможно отобразить загружаемое изображение.');
        return;
      }

      if (event.target.files.length == 0) {
        console.error('Изображения не были загружены.');
        return;
      }

      fileReader.onload = (event) => {
        let imageElement = document.createElement('img');
        imageElement.setAttribute('src', fileReader.result);
        imageElement.style.width = '100%';
        entryPreviewImageContainerElement.innerHTML = '';
        entryPreviewImageContainerElement.appendChild(imageElement);

        let formData = new FormData();
        formData.append('entry_event_save', true);
        formData.append('entry_id', searchParams.getPathPart(3));
        formData.append('entry_preview', fileReader.result);

        fetch('/handler/entry', {
          method: 'PATCH',
          body: formData
        }).then((response) => {
          return response.json();
        }).then((data) => {
          let notification = new PopupNotification(data.message, document.body, true);
          notification.show();
        });
      };
      fileReader.onerror = (event) => {
        console.error(fileReader.result);
      };
      fileReader.readAsDataURL(file);
    });

    entryPreviewFormElement.appendChild(entryPreviewFormInputFileElement);
    entryPreviewFormElement.appendChild(interactiveButtonPreviewUpload.target.assembled);
    entryPreviewBlockContentContainerElement.appendChild(entryPreviewImageContainerElement);
    entryPreviewBlockContentContainerElement.appendChild(entryPreviewFormElement);
  }
});