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

      let formMethod = (event.submitter.hasAttribute('formmethod')) ? event.submitter.getAttribute('formmethod') : '';
      this.send(event);
    });
  }
  getFormMethod(submitEvent) {
    if (submitEvent.submitter.hasAttribute('formmethod')) {
      if (submitEvent.submitter.getAttribute('formmethod') != '') {
        return submitEvent.submitter.getAttribute('formmethod');
      }
    }

    return (this.element.hasAttribute('method')) ? this.element.getAttribute('method') : 'POST';
  }
  getFormAction() {
    return (this.element.hasAttribute('action')) ? this.element.getAttribute('action') : '/handler';
  }
  send(submitEvent) {
    /** @type {FormData} */
    let formData = new FormData(this.element);
    let submitterName = submitEvent.submitter.hasAttribute('name') ? submitEvent.submitter.getAttribute('name') : 'submitter';
    formData.append(submitterName, true);

    // Отправка через Fetch API
    fetch(this.getFormAction(), {
      method: this.getFormMethod(submitEvent),
      body: formData
    }).then((response) => {
      try {
        let responseJSON = response.json();
        console.log(responseJSON);
      } catch (error) {
        console.error(error);
      }
    }).catch((error) => {
      console.error(error);
    });
  }
}