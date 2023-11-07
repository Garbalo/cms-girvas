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

        document.querySelector('#TC6474389679').prepend(interactiveChoicesSettingsTimezone.target.element);
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

        document.querySelector('#TC6474389682').append(interactiveChoicesSettingsCharset.target.element);
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

        document.querySelector('#TC6474389680').append(interactiveChoicesSettingsBaseLocale.target.element);
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

        document.querySelector('#TC6474389681').append(interactiveChoicesSettingsAdminLocale.target.element);
      });
    });
  }

  if (urlp.getPathPart(3) == null || urlp.getPathPart(3) == 'users') {
    let tableAdditionalFields = document.querySelector('[role="profile-table-additional-fields"]');
    let tableAdditionalFieldsButtonContainer = document.querySelector('[role="profile-add-field"]');

    let addField = (targetElement, fieldData = {}) => {
      let tableRow = document.createElement('tr');
      tableRow.setAttribute('role', 'additional-field');
      tableRow.classList.add('table__row');
      
      let tableCellTypeField = document.createElement('td');
      tableCellTypeField.classList.add('table__cell');

      let tableCellTitleField = document.createElement('td');
      tableCellTitleField.classList.add('table__cell');

      let tableCellNameField = document.createElement('td');
      tableCellNameField.classList.add('table__cell');

      let tableCellDescriptionField = document.createElement('td');
      tableCellDescriptionField.classList.add('table__cell');

      let interactiveChoicesTypeField = new Interactive('choices');
      interactiveChoicesTypeField.target.addItem('String', 'text');
      interactiveChoicesTypeField.target.addItem('Number', 'number');
      interactiveChoicesTypeField.target.addItem('Date', 'date');
      interactiveChoicesTypeField.target.addItem('Text', 'textarea');
      interactiveChoicesTypeField.target.setName('setting_users_additional_field_type[]');

      if (typeof fieldData.type != 'undefined') {
        switch (fieldData.type) {
          case 'text': interactiveChoicesTypeField.target.setItemSelectedIndex(0); break;
          case 'number': interactiveChoicesTypeField.target.setItemSelectedIndex(1); break;
          case 'date': interactiveChoicesTypeField.target.setItemSelectedIndex(2); break;
          case 'textarea': interactiveChoicesTypeField.target.setItemSelectedIndex(3); break;
          default: interactiveChoicesTypeField.target.setItemSelectedIndex(0);
        }
      }

      interactiveChoicesTypeField.assembly();

      tableCellTypeField.append(interactiveChoicesTypeField.target.element);

      let additionalFieldInputTitle = document.createElement('input');
      additionalFieldInputTitle.setAttribute('type', 'text');
      additionalFieldInputTitle.setAttribute('name', 'setting_users_additional_field_title[]');
      additionalFieldInputTitle.setAttribute('placeholder', 'My field');
      additionalFieldInputTitle.classList.add('form__input');
      additionalFieldInputTitle.classList.add('form__input_text');

      if (typeof fieldData.title != 'undefined') {
        additionalFieldInputTitle.value = fieldData.title;
      }

      tableCellTitleField.append(additionalFieldInputTitle);

      let additionalFieldInputName = document.createElement('input');
      additionalFieldInputName.setAttribute('pattern', '[a-z0-9_]+');
      additionalFieldInputName.setAttribute('type', 'text');
      additionalFieldInputName.setAttribute('name', 'setting_users_additional_field_name[]');
      additionalFieldInputName.setAttribute('placeholder', 'my_field');
      additionalFieldInputName.classList.add('form__input');
      additionalFieldInputName.classList.add('form__input_text');

      if (typeof fieldData.name != 'undefined') {
        additionalFieldInputName.value = fieldData.name;
      }

      tableCellNameField.append(additionalFieldInputName);

      let additionalFieldInputDescription = document.createElement('textarea');
      additionalFieldInputDescription.setAttribute('name', 'setting_users_additional_field_description[]');
      additionalFieldInputDescription.setAttribute('placeholder', 'Description field...');
      additionalFieldInputDescription.classList.add('form__textarea');

      if (typeof fieldData.description != 'undefined') {
        additionalFieldInputDescription.innerText = fieldData.description;
      }

      tableCellDescriptionField.append(additionalFieldInputDescription);

      tableRow.append(tableCellTypeField);
      tableRow.append(tableCellTitleField);
      tableRow.append(tableCellNameField);
      tableRow.append(tableCellDescriptionField);

      targetElement.parentElement.before(tableRow);
    };

    let buttonAddProfileField = new Interactive('button');
    buttonAddProfileField.target.setLabel('Новое поле');
    buttonAddProfileField.target.setCallback((event) => {
      event.preventDefault();

      addField(tableAdditionalFieldsButtonContainer);
    });
    buttonAddProfileField.assembly();
    
    let interactiveChoicesFieldsLocale = new Interactive('choices'), locales = [];
    interactiveChoicesFieldsLocale.target.setName('_users_additional_fields_locale');

    // Получаем все установленные языковые пакеты
    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      locales.forEach((locale, localeIndex) => {
        let localeIconImageElement = document.createElement('img');
        localeIconImageElement.setAttribute('src', locale.iconURL);
        localeIconImageElement.setAttribute('alt', locale.title);

        let localeLabelElement = document.createElement('span');
        localeLabelElement.innerText = locale.title;

        let localeTemplate = document.createElement('template');
        localeTemplate.innerHTML += localeIconImageElement.outerHTML;
        localeTemplate.innerHTML += localeLabelElement.outerHTML;

        interactiveChoicesFieldsLocale.target.addItem(localeTemplate.innerHTML, locale.name);
      });
    
      return fetch('/handler/locale/base', {method: 'GET'});

    // Получает выбранный базовый языковой пакет
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      let localeSelected = data.outputData.locale;
      locales.forEach((locale, localeIndex) => {
        if (locale.name === localeSelected.name) {
          interactiveChoicesFieldsLocale.target.setItemSelectedIndex(localeIndex);
        }
      });

      interactiveChoicesFieldsLocale.assembly();
      
      let interactiveChoicesFieldsLocaleSelectElement = interactiveChoicesFieldsLocale.target.element.querySelector('select');
      interactiveChoicesFieldsLocaleSelectElement.addEventListener('change', (event) => {
        fetch('/handler/profile/additional-fields?locale=' + interactiveChoicesFieldsLocaleSelectElement.value, {method: 'GET'}).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((data1) => {
          let additionalFields = document.querySelectorAll('[role="additional-field"]');
          additionalFields.forEach((element) => {
            element.remove();
          })

          let fields = data1.outputData.additionalFields;
          fields.forEach((field) => {
            addField(tableAdditionalFieldsButtonContainer, {
              type: field.type,
              title: field.title,
              description: field.description,
              name: field.name
            });
          });
        });
      });

      let profileAdditionalFieldsLocaleContainer = document.querySelector('[role="profile-additional-fields-locale"]');
      profileAdditionalFieldsLocaleContainer.append(interactiveChoicesFieldsLocale.target.element);

      return fetch('/handler/profile/additional-fields?locale=' + localeSelected.name, {method: 'GET'});
    
    // Получаем дополнительные поля для профиля
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      let fields = data.outputData.additionalFields;
      fields.forEach((field) => {
        addField(tableAdditionalFieldsButtonContainer, {
          type: field.type,
          title: field.title,
          description: field.description,
          name: field.name
        });
      });
    });

    tableAdditionalFieldsButtonContainer.append(buttonAddProfileField.target.element);
  }
});