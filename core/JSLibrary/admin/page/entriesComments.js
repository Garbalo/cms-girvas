'use strict';

import {Interactive} from "../../interactive.class.js";
import {URLParser} from "../../urlParser.class.js";

document.addEventListener('DOMContentLoaded', (event) => {
  let tableItems = document.querySelectorAll('.table-entries-comments__item');
  for (let tableItem of tableItems) {
    let commentID = tableItem.getAttribute('data-comment-id');
    let commentIsHidden = tableItem.getAttribute('data-comment-is-hidden');
    let buttons = tableItem.querySelectorAll('button[role]');
    for (let button of buttons) {
      if (button.getAttribute('role') == 'comment-hide' && commentIsHidden == 'true') {
        button.parentElement.style.display = 'none';
      }

      if (button.getAttribute('role') == 'comment-show' && commentIsHidden == 'false') {
        button.parentElement.style.display = 'none';
      }

      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'comment-show') {
          let interactiveModal = new Interactive('modal', {title: "Снять запрет на показ", content: 'Вы действительно хотите вновь показывать этот комментарий?'});
          interactiveModal.target.addButton('Да', () => {
            let formData = new FormData();
            formData.append('comment_id', commentID);
            formData.append('comment_is_hidden', 'off');
            formData.append('comment_hidden_reason', '');

            fetch('/handler/entry/comment', {
              method: 'PATCH',
              body: formData
            }).then((response) => {
              return response.json();
            }).then((data) => {
              interactiveModal.target.close();
    
              if (data.statusCode == 1) {
                window.location.reload();
              }
    
              let notification = new PopupNotification(data.message, document.body, true);
              notification.show();
            });
          });

          interactiveModal.target.addButton('Нет', () => {
            interactiveModal.target.close();
          });

          interactiveModal.target.assembly();
          document.body.appendChild(interactiveModal.target.assembled);
          interactiveModal.target.show();
        }

        if (button.getAttribute('role') == 'comment-hide') {
          let elementForm = document.createElement('form');
          elementForm.classList.add('form');
          let elementTextarea = document.createElement('textarea');
          elementTextarea.classList.add('form__textarea');
          elementTextarea.style.width = '300px';
          elementTextarea.setAttribute('name', 'comment_hidden_reason');
          elementTextarea.setAttribute('placeholder', 'Укажите причину...');
          elementForm.append(elementTextarea);
          
          let interactiveModal = new Interactive('modal', {title: "Скрытие комментария", content: elementForm});
          interactiveModal.target.addButton('Подтвердить', () => {
            let formData = new FormData();
            formData.append('comment_id', commentID);
            formData.append('comment_is_hidden', 'on');
            formData.append('comment_hidden_reason', elementTextarea.value);

            fetch('/handler/entry/comment', {
              method: 'PATCH',
              body: formData
            }).then((response) => {
              return response.json();
            }).then((data) => {
              interactiveModal.target.close();
    
              if (data.statusCode == 1) {
                window.location.reload();
              }
    
              let notification = new PopupNotification(data.message, document.body, true);
              notification.show();
            });
          });

          interactiveModal.target.addButton('Отмена', () => {
            interactiveModal.target.close();
          });

          interactiveModal.target.assembly();
          document.body.appendChild(interactiveModal.target.assembled);
          interactiveModal.target.show();
        }

        if (button.getAttribute('role') == 'comment-delete') {
          let interactiveModal = new Interactive('modal', {title: "Удаление комментария", content: "Вы действительно хотите удалить комментарий? Действие отменить будет нельзя."});
          interactiveModal.target.addButton('Удалить', () => {
            let formData = new FormData();
            formData.append('comment_id', commentID);

            fetch('/handler/entry/comment', {
              method: 'DELETE',
              body: formData
            }).then((response) => {
              return response.json();
            }).then((data) => {
              interactiveModal.target.close();
    
              if (data.statusCode == 1) {
                tableItem.remove();
                window.location.reload();
              }
    
              let notification = new PopupNotification(data.message, document.body, true);
              notification.show();
            });
          });

          interactiveModal.target.addButton('Отмена', () => {
            interactiveModal.target.close();
          });

          interactiveModal.target.assembly();
          document.body.appendChild(interactiveModal.target.assembled);
          interactiveModal.target.show();
        }
      });
    }
  }
});