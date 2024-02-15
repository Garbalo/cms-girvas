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

export class PagePageStatic {
  constructor(params = {}) {
    this.buttons = {save: null, delete: null, publish: null, unpublish: null};
  }

  init() {
    let searchParams = new URLParser(), locales;

    let elementForm = document.querySelector('.form_page-static');
    let interactiveLocaleChoices = new Interactive('choices');
    
    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {

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

        interactiveLocaleChoices.target.addItem(localeTemplate.innerHTML, localeName);
      });

      locales.forEach((locale, localeIndex) => {
        if (locale.name === window.CMSCore.locales.base.name) {
          interactiveLocaleChoices.target.setItemSelectedIndex(localeIndex);
        }
      });

      interactiveLocaleChoices.assembly();

      let interactiveContainerElement = document.querySelector('#E8548530785');
      interactiveContainerElement.append(interactiveLocaleChoices.target.element);

      let interactiveChoicesSelectElement = interactiveContainerElement.querySelector('select');
      interactiveChoicesSelectElement.addEventListener('change', (event) => {
        let contentTextareaElement = document.querySelector('[role="pageStaticContent"]');
        let descriptionTextareaElement = document.querySelector('[role="pageStaticDescription"]');
        let titleInputElement = document.querySelector('[role="pageStaticTitle"]');
        let keywordsInputElement = document.querySelector('[role="pageStaticKeywords"]');
        
        locales.forEach((locale, localeIndex) => {
          if (locale.name == event.target.value) {
            contentTextareaElement.setAttribute('name', 'page_static_content_' + locale.iso639_2);
            descriptionTextareaElement.setAttribute('name', 'page_static_description_' + locale.iso639_2);
            titleInputElement.setAttribute('name', 'page_static_title_' + locale.iso639_2);
            keywordsInputElement.setAttribute('name', 'page_static_keywords_' + locale.iso639_2);
            
            if (searchParams.getPathPart(3) != null) {
              fetch('/handler/pageStatic/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return (response.ok) ? response.json() : Promise.reject(response);
              }).then((data1) => {
                contentTextareaElement.value = data1.outputData.pageStatic.content;
                descriptionTextareaElement.value = data1.outputData.pageStatic.description;
                titleInputElement.value = data1.outputData.pageStatic.title;
                keywordsInputElement.value = data1.outputData.pageStatic.keywords.join(', ');
              });
            }
          }
        });
      });

      this.buttons.save = new Interactive('button');
      this.buttons.save.target.setLabel('Сохранить');
      this.buttons.save.target.setCallback((event) => {
        event.preventDefault();
        
        let formData = new FormData(elementForm);

        fetch('/handler/pageStatic', {
          method: (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH',
          body: formData
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
            let pageData = data1.outputData.pageStatic;
            window.location.href = '/admin/page/' + pageData.id;
          }

          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.save.assembly();

      this.buttons.delete = new Interactive('button');
      this.buttons.delete.target.setLabel(localeData.BUTTON_DELETE_LABEL);
      this.buttons.delete.target.setCallback((event) => {
        event.preventDefault();

        let interactiveModal = new Interactive('modal', {title: localeData.MODAL_PAGE_DELETE_TITLE, content: localeData.MODAL_PAGE_DELETE_DESCRIPTION});
        interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
          let formData = new FormData();
          formData.append('page_static_id', searchParams.getPathPart(3));

          fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
            method: 'DELETE',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data1) => {
            if (data1.statusCode == 1) {
              window.location.href = '/admin/pages';
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

      this.buttons.publish = new Interactive('button');
      this.buttons.publish.target.setLabel(localeData.BUTTON_PUBLISH_LABEL);
      this.buttons.publish.target.setCallback((event) => {
        event.preventDefault();

        let formData = new FormData();
        formData.append('page_static_id', searchParams.getPathPart(3));
        formData.append('page_static_is_published', 1);

        fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
          method: 'PATCH',
          body: formData
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1) {
            this.buttons.unpublish.target.element.style.display = 'flex';
            this.buttons.publish.target.element.style.display = 'none';
          }

          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.publish.assembly();

      this.buttons.unpublish = new Interactive('button');
      this.buttons.unpublish.target.setLabel(localeData.BUTTON_UNPUBLISH_LABEL);
      this.buttons.unpublish.target.setCallback((event) => {
        event.preventDefault();

        let formData = new FormData();
        formData.append('page_static_id', searchParams.getPathPart(3));
        formData.append('page_static_is_published', 0);

        fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
          method: 'PATCH',
          body: formData
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1) {
            this.buttons.unpublish.target.element.style.display = 'none';
            this.buttons.publish.target.element.style.display = 'flex';
          }

          let notification = new PopupNotification(data1.message, document.body, true);
          notification.show();
        });
      });
      this.buttons.unpublish.assembly();

      if (searchParams.getPathPart(3) == null) {
        this.buttons.unpublish.target.element.style.display = 'none';
        this.buttons.publish.target.element.style.display = 'none';
        this.buttons.delete.target.element.style.display = 'none';
        this.buttons.save.target.element.style.display = 'flex';
      } else {
        let interactiveButtonPreviewUpload = new Interactive('button');

        let previewBlockElement = document.querySelector('#SYSTEM_E3754926184');
        let previewBlockContentContainerElement = previewBlockElement.querySelector('.page-aside__block-content');
        
        let previewFormElement = document.createElement('form');
        previewFormElement.setAttribute('formmethod', 'PATCH');
        previewFormElement.classList.add('form');
        previewFormElement.classList.add('form-page-static-preview');

        let previewFormInputFileElement = document.createElement('input');
        previewFormInputFileElement.setAttribute('type', 'file');
        previewFormInputFileElement.setAttribute('name', 'page_static_preview');
        previewFormInputFileElement.style.display = 'none';

        previewFormInputFileElement.addEventListener('change', (event) => {
          event.preventDefault();

          let file = event.target.files[0], fileReader = new FileReader();

          if (!fileReader) {
            console.error(localeData.REPORT_JS_CMSCORE_ERROR_FILEREADER_IS_NOT_SUPPORTED);
            return;
          }

          if (event.target.files.length == 0) {
            console.error(localeData.REPORT_JS_CMSCORE_ERROR_IMAGES_WHERE_NOT_LOADED);
            return;
          }

          fileReader.onload = (event) => {
            let imageElement = document.createElement('img');
            imageElement.setAttribute('src', fileReader.result);
            imageElement.style.width = '100%';
            previewImageContainerElement.innerHTML = '';
            previewImageContainerElement.appendChild(imageElement);
    
            let formData = new FormData();
            formData.append('page_static_event_save', true);
            formData.append('page_static_id', searchParams.getPathPart(3));
            formData.append('page_static_preview', fileReader.result);
    
            fetch('/handler/pageStatic', {
              method: 'PATCH',
              body: formData
            }).then((response) => {
              return (response.ok) ? response.json() : Promise.reject(response);
            }).then((data1) => {
              let notification = new PopupNotification(data1.message, document.body, true);
              notification.show();
            });
          };

          fileReader.onerror = (event) => {
            console.error(fileReader.result);
          };

          fileReader.readAsDataURL(file);
        });

        interactiveButtonPreviewUpload.target.setLabel(localeData.BUTTON_UPLOAD_COVER_LABEL);
        interactiveButtonPreviewUpload.target.setCallback((event) => {
          event.preventDefault();
          previewFormInputFileElement.click();
        });
        interactiveButtonPreviewUpload.assembly();

        let previewImageContainerElement = document.createElement('div');
        previewImageContainerElement.classList.add('form-page-static-preview__container-image');

        fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
          method: 'GET'
        }).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          if (data1.statusCode == 1) {
            let pageData = data1.outputData.pageStatic;
            
            if (pageData.previewURL != '') {
              let imageElement = document.createElement('img');
              imageElement.setAttribute('src', pageData.previewURL);
              imageElement.style.width = '100%';

              previewImageContainerElement.innerHTML = '';
              previewImageContainerElement.appendChild(imageElement);
            }

            previewFormElement.appendChild(previewFormInputFileElement);
            previewFormElement.appendChild(interactiveButtonPreviewUpload.target.element);
            previewBlockContentContainerElement.appendChild(previewImageContainerElement);
            previewBlockContentContainerElement.appendChild(previewFormElement);

            this.buttons.unpublish.target.element.style.display = (pageData.isPublished) ? 'flex' : 'none';
            this.buttons.publish.target.element.style.display = (pageData.isPublished) ? 'none' : 'flex';
            this.buttons.delete.target.element.style.display = 'flex';
            this.buttons.save.target.element.style.display = 'flex';
          } else {
            this.buttons.unpublish.target.element.style.display = 'none';
            this.buttons.publish.target.element.style.display = 'none';
            this.buttons.delete.target.element.style.display = 'none';
            this.buttons.save.target.element.style.display = 'flex';
          }
        });
      }

      let interactiveContainer = document.querySelector('#SYSTEM_E3724126170');
      interactiveContainer.append(this.buttons.delete.target.element);
      interactiveContainer.append(this.buttons.unpublish.target.element);
      interactiveContainer.append(this.buttons.publish.target.element);
      interactiveContainer.append(this.buttons.save.target.element);
    });
  }
}