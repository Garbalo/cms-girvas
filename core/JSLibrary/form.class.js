'use strict';

class Form {
  /**
   * constructor
   * 
   * @param {HTMLFormElement} element
   */
  constructor(element) {
    this.modalParent = null;
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
      return response.json();
    }).then((data) => {
      console.log(data);
      if (typeof(data.outputData.modalClose) != 'undefined') {
        this.modalParent.remove();
      }

      if (typeof(data.outputData.reload) != 'undefined') {
        this.timeout = setTimeout(() => {
          window.location.reload();
        }, 10);
      }

      if (typeof(data.outputData.href) != 'undefined') {
        this.timeout = setTimeout(() => {
          window.location.href = data.outputData.href;
        }, 10);
      }
    }).catch((error) => {
      console.error(error);
    });
  }
}