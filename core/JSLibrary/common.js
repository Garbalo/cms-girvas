'use strict';

document.addEventListener('DOMContentLoaded', () => {
  // Инициализация всех форм в документе
  let formsElementsArray = document.body.querySelectorAll('form');
  for (let formElement of formsElementsArray) {
    let form = new Form(formElement);
    form.initFormElement();
  }
});