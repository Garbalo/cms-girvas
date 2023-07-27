'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItemsTemplates = document.querySelectorAll('.templates-list__item');
  for (let tableItemTemplate of tableItemsTemplates) {
    let templateName = tableItemTemplate.getAttribute('data-template-name');
    let buttons = tableItemTemplate.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'template-download') {
          let formData = new FormData();
          formData.append('template_name', templateName);

          let notification_start = new PopupNotification('Загрузка шаблона...', document.body, true);
          notification_start.show();

          fetch('/handler/admin/templates/download', {
            method: 'POST',
            body: formData
          }).then((response) => {
            return response.json();
          }).then((data) => {
            notification_start.hide();

            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          });;
        }
      });
    }
  }
});