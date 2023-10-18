'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();

  fetch('/handler/entry/' + searchParams.getPathPart(3), {
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

      fetch('/handler/entry', {
        method: (searchParams.getPathPart(3) == null) ? 'PUT' : 'PATCH',
        body: formData
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        if (data1.statusCode == 1 && searchParams.getPathPart(3) == null) {
          let entryData = data1.outputData.entry;
          window.location.href = '/admin/entry/' + entryData.id;
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

      let interactiveModal = new Interactive('modal', {title: "Удаление записи", content: "Вы действительно хотите удалить запись? Действие отменить будет нельзя."});
      interactiveModal.target.addButton('Удалить', () => {
        let formData = new FormData();
        formData.append('entry_id', searchParams.getPathPart(3));

        fetch('/handler/entry/' + searchParams.getPathPart(3), {
          method: 'DELETE',
          body: formData
        }).then((response) => {
          return response.json();
        }).then((data1) => {
          if (data1.statusCode == 1) {
            window.location.href = '/admin/entries';
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
      formData.append('entry_id', searchParams.getPathPart(3));
      formData.append('entry_is_published', 1);

      fetch('/handler/entry/' + searchParams.getPathPart(3), {
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
      formData.append('entry_id', searchParams.getPathPart(3));
      formData.append('entry_is_published', 0);

      fetch('/handler/entry/' + searchParams.getPathPart(3), {
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
    
    let entryData = data.outputData.entry;
    if (searchParams.getPathPart(3) == null) {
      buttons.unpublish.target.assembled.style.display = 'none';
      buttons.publish.target.assembled.style.display = 'none';
      buttons.delete.target.assembled.style.display = 'none';
      buttons.save.target.assembled.style.display = 'flex';
    } else {
      buttons.unpublish.target.assembled.style.display = (entryData.isPublished) ? 'flex' : 'none';
      buttons.publish.target.assembled.style.display = (entryData.isPublished) ? 'none' : 'flex';
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

            fetch('/handler/entry/' + searchParams.getPathPart(3), {
              method: 'GET'
            }).then((response) => {
              return response.json();
            }).then((data3) => {
              let entryCategoryID = data3.outputData.entry.categoryID;
              fetch('/handler/entry/categories' + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return response.json();
              }).then((data4) => {
                let entriesCategories = data4.outputData.entriesCategories;
                let interactiveChoicesCategories = new Interactive('choices');
                entriesCategories.forEach((entriesCategory, entriesCategoryIndex) => {
                  interactiveChoicesCategories.target.addItem(entriesCategory.title, entriesCategory.id);
                });
                entriesCategories.forEach((entriesCategory, entriesCategoryIndex) => {
                  if (entriesCategory.id == entryCategoryID) {
                    interactiveChoicesCategories.target.setItemSelectedIndex(entriesCategoryIndex);
                  }
                });
                
                interactiveChoicesCategories.target.setName('entry_category_id');
                interactiveChoicesCategories.assembly();
        
                let interactiveContainer = document.querySelector('#TC6474389611');
                interactiveContainer.append(interactiveChoicesCategories.target.assembled);
              });
            });
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
          let entryKeywordsInputElement = document.querySelector('[role="entryKeywords"]');
          
          locales.forEach((locale, localeIndex) => {
            if (locale.name == event.target.value) {
              entryContentTextareaElement.setAttribute('name', 'entry_content_' + locale.iso639_2);
              entryDescriptionTextareaElement.setAttribute('name', 'entry_description_' + locale.iso639_2);
              entryTitleInputElement.setAttribute('name', 'entry_title_' + locale.iso639_2);
              entryKeywordsInputElement.setAttribute('name', 'entry_keywords_' + locale.iso639_2);
              
              fetch('/handler/entry/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return response.json();
              }).then((data) => {
                entryContentTextareaElement.value = data.outputData.entry.content;
                entryDescriptionTextareaElement.value = data.outputData.entry.description;
                entryTitleInputElement.value = data.outputData.entry.title;
                entryKeywordsInputElement.value = data.outputData.entry.keywords.join(', ');
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
    interactiveButtonPreviewUpload.target.setLabel('Загрузить обложку');
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