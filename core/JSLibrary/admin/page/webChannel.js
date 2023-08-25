'use strict';

import {Interactive} from "../../interactive.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let searchParams = new URLParser();

  fetch('/handler/locales', {
    method: 'GET'
  }).then((response) => {
    return response.json();
  }).then((data) => {
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
          let webChannelDescriptionTextareaElement = document.querySelector('[role="webChannelDescription"]');
          let webChannelTitleInputElement = document.querySelector('[role="webChannelTitle"]');
          
          locales.forEach((locale, localeIndex) => {
            if (locale.name == event.target.value) {
              webChannelDescriptionTextareaElement.setAttribute('name', 'web_channel_description_' + locale.iso639_2);
              webChannelTitleInputElement.setAttribute('name', 'web_channel_title_' + locale.iso639_2);
              
              fetch('/handler/webChannel/' + searchParams.getPathPart(3) + '?locale=' + locale.name, {
                method: 'GET'
              }).then((response) => {
                return response.json();
              }).then((data) => {
                webChannelDescriptionTextareaElement.value = data.outputData.webChannel.description;
                webChannelTitleInputElement.value = data.outputData.webChannel.title;
              });
            }
          });
        });
      });
    }
  });

  if (searchParams.getPathPart(3) != null) {
    fetch('/handler/webChannels/types', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      fetch('/handler/webChannel/' + searchParams.getPathPart(3), {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data1) => {
        let webChannelsTypes = data.outputData.webChannelsTypes;
        let interactiveChoicesWebChannelsTypes = new Interactive('choices');

        webChannelsTypes.forEach((type, typeIndex) => {
          interactiveChoicesWebChannelsTypes.target.addItem(type.title, type.id);
        });

        webChannelsTypes.forEach((type, typeIndex) => {
          if (type.id == data1.outputData.webChannel.typeID) {
            interactiveChoicesWebChannelsTypes.target.setItemSelectedIndex(typeIndex);
          }
        });

        interactiveChoicesWebChannelsTypes.target.setName('web_channel_type_id');
        interactiveChoicesWebChannelsTypes.assembly();

        document.querySelector('#TC6474387201').append(interactiveChoicesWebChannelsTypes.target.assembled);
      });
    });
    
    fetch('/handler/locale/admin', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data2) => {
      let localeSelected = data2.outputData.locale;

      fetch('/handler/entries/categories?locale=' + localeSelected.name, {
        method: 'GET'
      }).then((response) => {
        return response.json();
      }).then((data) => {
        fetch('/handler/webChannel/' + searchParams.getPathPart(3), {
          method: 'GET'
        }).then((response) => {
          return response.json();
        }).then((data1) => {
          let entriesCategories = data.outputData.entriesCategories;
          let interactiveChoicesEntriesCategories = new Interactive('choices');

          entriesCategories.forEach((entryCategory, entryCategoryIndex) => {
            interactiveChoicesEntriesCategories.target.addItem(entryCategory.title, entryCategory.id);
          });

          entriesCategories.forEach((entryCategory, entryCategoryIndex) => {
            if (entryCategory.id == data1.outputData.webChannel.entriesCategoryID) {
              interactiveChoicesEntriesCategories.target.setItemSelectedIndex(entryCategoryIndex);
            }
          });

          interactiveChoicesEntriesCategories.target.setName('web_channel_entries_category_id');
          interactiveChoicesEntriesCategories.assembly();

          document.querySelector('#TC6474387200').append(interactiveChoicesEntriesCategories.target.assembled);
        });
      });
    });
  } else {
    fetch('/handler/webChannels/types', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      let webChannelsTypes = data.outputData.webChannelsTypes;
      let interactiveChoicesWebChannelsTypes = new Interactive('choices');

      webChannelsTypes.forEach((type, typeIndex) => {
        interactiveChoicesWebChannelsTypes.target.addItem(type.title, type.id);
      });

      interactiveChoicesWebChannelsTypes.target.setName('web_channel_type_id');
      interactiveChoicesWebChannelsTypes.assembly();

      document.querySelector('#TC6474387201').append(interactiveChoicesWebChannelsTypes.target.assembled);
    });

    fetch('/handler/entries/categories', {
      method: 'GET'
    }).then((response) => {
      return response.json();
    }).then((data) => {
      let entriesCategories = data.outputData.entriesCategories;
      let interactiveChoicesEntriesCategories = new Interactive('choices');

      entriesCategories.forEach((entryCategory, entryCategoryIndex) => {
        interactiveChoicesEntriesCategories.target.addItem(entryCategory.title, entryCategory.id);
      });

      interactiveChoicesEntriesCategories.target.setName('web_channel_entries_category_id');
      interactiveChoicesEntriesCategories.assembly();

      document.querySelector('#TC6474387200').append(interactiveChoicesEntriesCategories.target.assembled);
    });
  }
});