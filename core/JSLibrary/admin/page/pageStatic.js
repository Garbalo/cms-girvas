'use strict';

import {Interactive} from "../../interactive.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();

  fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
    method: 'GET'
  }).then((response) => {
    return response.json();
  }).then((data) => {
    let buttons = {save: null, delete: null, publish: null, unpublish: null};
    buttons.save = new Interactive('button');
    buttons.save.target.setLabel('Сохранить');
    buttons.save.target.setCallback((event) => {
      event.preventDefault();
      
      let formTargetElement = document.querySelector('#SYSTEM_F0648538658');
      let formData = new FormData(formTargetElement);

      fetch('/handler/pageStatic', {
        method: (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH',
        body: formData
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
          let pageData = data1.outputData.pageStatic;
          window.location.href = '/admin/pages/' + pageData.id;
        }

        let notification = new PopupNotification(data1.message, document.body, true);
        notification.show();
      });
    });
    buttons.save.target.assembly();

    buttons.delete = new Interactive('button');
    buttons.delete.target.setLabel('Удалить');
    buttons.delete.target.setCallback((event) => {
      event.preventDefault();

      let interactiveModal = new Interactive('modal', {title: "Удаление страницы", content: "Вы действительно хотите удалить страницу? Действие отменить будет нельзя."});
      interactiveModal.target.addButton('Удалить', () => {
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

      interactiveModal.target.addButton('Отмена', () => {
        interactiveModal.target.close();
      });

      interactiveModal.target.assembly();
      document.body.appendChild(interactiveModal.target.assembled);
      interactiveModal.target.show();
    });
    buttons.delete.target.assembly();

    buttons.publish = new Interactive('button');
    buttons.publish.target.setLabel('Опубликовать');
    buttons.publish.target.setCallback((event) => {
      event.preventDefault();

      let formData = new FormData();
      formData.append('page_static_id', searchParams.getPathPart(3));
      formData.append('page_static_is_published', 1);

      fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
        method: 'PATCH',
        body: formData
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        if (data1.statusCode == 1) {
          buttons.unpublish.target.assembled.style.display = 'flex';
          buttons.publish.target.assembled.style.display = 'none';
        }

        let notification = new PopupNotification(data1.message, document.body, true);
        notification.show();
      });
    });
    buttons.publish.target.assembly();

    buttons.unpublish = new Interactive('button');
    buttons.unpublish.target.setLabel('Снять с публикации');
    buttons.unpublish.target.setCallback((event) => {
      event.preventDefault();

      let formData = new FormData();
      formData.append('page_static_id', searchParams.getPathPart(3));
      formData.append('page_static_is_published', 0);

      fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
        method: 'PATCH',
        body: formData
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        if (data1.statusCode == 1) {
          buttons.unpublish.target.assembled.style.display = 'none';
          buttons.publish.target.assembled.style.display = 'flex';
        }

        let notification = new PopupNotification(data1.message, document.body, true);
        notification.show();
      });
    });
    buttons.unpublish.target.assembly();
    
    let pageData = data.outputData.pageStatic;
    if (searchParams.getPathPart(3) == null) {
      buttons.unpublish.target.assembled.style.display = 'none';
      buttons.publish.target.assembled.style.display = 'none';
      buttons.delete.target.assembled.style.display = 'none';
      buttons.save.target.assembled.style.display = 'flex';
    } else {
      buttons.unpublish.target.assembled.style.display = (pageData.isPublished) ? 'flex' : 'none';
      buttons.publish.target.assembled.style.display = (pageData.isPublished) ? 'none' : 'flex';
      buttons.delete.target.assembled.style.display = 'flex';
      buttons.save.target.assembled.style.display = 'flex';
    }

    let interactiveContainer = document.querySelector('#SYSTEM_E3724126170');
    interactiveContainer.append(buttons.delete.target.assembled);
    interactiveContainer.append(buttons.unpublish.target.assembled);
    interactiveContainer.append(buttons.publish.target.assembled);
    interactiveContainer.append(buttons.save.target.assembled);
  });

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
              
              fetch('/handler/pageStatic/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return response.json();
              }).then((data) => {
                contentTextareaElement.value = data.outputData.pageStatic.content;
                descriptionTextareaElement.value = data.outputData.pageStatic.description;
                titleInputElement.value = data.outputData.pageStatic.title;
                keywordsInputElement.value = data.outputData.pageStatic.keywords.join(', ');
              });
            }
          });
        });
      });
    }
  });

  if (searchParams.getPathPart(3) != null) {
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

    let interactiveButtonPreviewUpload = new Interactive('button');
    interactiveButtonPreviewUpload.target.setLabel('Загрузить обложку');
    interactiveButtonPreviewUpload.target.setCallback((event) => {
      event.preventDefault();
      previewFormInputFileElement.click();
    });
    interactiveButtonPreviewUpload.target.assembly();

    let previewImageContainerElement = document.createElement('div');
    previewImageContainerElement.classList.add('form-page-static-preview__container-image');

    fetch('/handler/pageStatic/' + searchParams.getPathPart(3), {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      if (data.statusCode == 1) {
        if (data.outputData.pageStatic.previewURL != '') {
          let imageElement = document.createElement('img');
          imageElement.setAttribute('src', data.outputData.pageStatic.previewURL);
          imageElement.style.width = '100%';
          previewImageContainerElement.innerHTML = '';
          previewImageContainerElement.appendChild(imageElement);
        }
      }
    });

    previewFormInputFileElement.addEventListener('change', (event) => {
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

    previewFormElement.appendChild(previewFormInputFileElement);
    previewFormElement.appendChild(interactiveButtonPreviewUpload.target.assembled);
    previewBlockContentContainerElement.appendChild(previewImageContainerElement);
    previewBlockContentContainerElement.appendChild(previewFormElement);
  }
});