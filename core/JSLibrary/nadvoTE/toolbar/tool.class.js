'use strict';

export class Tool {
  constructor(editor, data) {
    this.editor = editor;

    this.setName(data.name);
    this.setType(data.type);
    this.setIconPath(data.iconPath);
    this.setElement(data.element);
    this.setElementIcon(data.iconPath);

    console.log(`[NADVO TE] Tool ${data.name} created.`);
  }
  
  setType(value) {
    this.type = value;
  }
  
  setName(value) {
    this.name = value;
  }
  
  setIconPath(value) {
    this.iconPath = value;
  }

  setElement(element) {
    this.element = element;
  }

  setElementIcon(path) {
    fetch(path,  {
      method: 'GET',
      headers: {
        'Content-Type': 'image/svg+xml'
      }
    }).then((response) => {
      if (response.status == 200) {
        response.text().then((text) => {
          this.element.innerHTML = text;
        });
      } else {
        this.element.innerHTML = this.name;
      }
    }).catch((error) => {
      console.error(error);
    });
  }

  addClickEvent(callback) {
    this.element.addEventListener('click', (event) => {
      event.preventDefault();

      callback();
    });
  }
}