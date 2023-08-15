'use strict';

class MediaLoader {
  constructor(input, mediaListElement) {
    this.input = input;
    this.mediaListElement = mediaListElement;
  }

  addItemToListElement(element) {
    let mediaListItems = this.mediaListElement.querySelectorAll('li');
    mediaListItems[0].after(element);
  }

  upload(index) {
    let formData = new FormData();
    formData.append('mediaFile', this.input.files[index]);

    fetch('/handler/upload/media', {
      method: 'POST',
      body: formData
    }).then((response) => {
      return response.json();
    }).then((data) => {
      console.log(data);

      let notification = new PopupNotification(data.message, document.body, true);
      notification.show();

      if (typeof(data.outputData.dom.listItem) != 'undefined') {
        let listItemElement = document.createElement('div');
        listItemElement.innerHTML = data.outputData.dom.listItem;
        this.addItemToListElement(listItemElement.firstChild);
      }

      if (data.statusCode == 1 && index < (this.input.files.length - 1)) {
        this.upload(index + 1);
      }
    });
  }

  init() {
    this.input.addEventListener('change', (event) => {
      console.log(this.input.files);

      if (this.input.files.length > 0) {
        this.upload(0);
      }
    });
  }
}