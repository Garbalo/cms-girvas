'use strict';

class Form {
  /**
   * constructor
   * 
   * @param {HTMLFormElement} element
   */
  constructor(element) {
    this.setFormElement(element);
  }
  /**
   * Назначить элемент формы
   * 
   * @param {HTMLFormElement} element 
   */
  setFormElement(element) {
    /** @type {HTMLFormElement} */
    this.element = element;
  }
  /**
   * Инициализация элемента формы
   */
  initFormElement() {
    this.element.addEventListener('submit', (event) => {
      event.preventDefault();
      this.send();
    });
  }
  getFormMethod() {
    return (this.element.hasAttribute('method')) ? this.element.getAttribute('method') : 'POST';
  }
  getFormAction() {
    return (this.element.hasAttribute('action')) ? this.element.getAttribute('action') : '/handler';
  }
  send() {
    /** @type {FormData} */
    let formData = new FormData(this.element);

    // Отправка через Fetch API
    fetch(this.getFormAction(), {
      method: this.getFormMethod(),
      body: formData
    }).then((response) => {
      console.log(response.json());
    }).catch((error) => {
      console.error(error);
    });
  }
}