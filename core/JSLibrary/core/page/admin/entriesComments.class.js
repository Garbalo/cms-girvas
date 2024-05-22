/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../../interactive.class.js";
import {URLParser} from "../../../urlParser.class.js";

export class PageEntriesComments {
  constructor(page, params = {}) {
    this.page = page;
  }

  init() {
    let tableItems = document.querySelectorAll('.table-entries-comments__item');
    let locales;

    fetch('/handler/locales', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.admin.getData();
    }).then((localeData) => {
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
              let interactiveModal = new Interactive('modal', {
                title: localeData.MODAL_COMMENT_SHOW_TITLE,
                content: localeData.MODAL_COMMENT_SHOW_DESCRIPTION
              });

              interactiveModal.target.addButton(localeData.BUTTON_YES_LABEL, () => {
                let formData = new FormData();
                formData.append('comment_id', commentID);
                formData.append('comment_is_hidden', 'off');
                formData.append('comment_hidden_reason', '');

                let request = new Interactive('request', {
                  method: 'PATCH',
                  url: '/handler/entry/comment?localeMessage=' + window.CMSCore.locales.admin.name
                });
        
                request.target.data = formData;
        
                request.target.send().then((data) => {
                  interactiveModal.target.close();

                  if (data.statusCode == 1) {
                    window.location.reload();
                  }
                });
              });

              interactiveModal.target.addButton(localeData.BUTTON_NO_LABEL, () => {
                interactiveModal.target.close();
              });

              interactiveModal.assembly();
              document.body.appendChild(interactiveModal.target.element);
              interactiveModal.target.show();
            }

            if (button.getAttribute('role') == 'comment-hide') {
              let elementForm = document.createElement('form');
              elementForm.classList.add('form');
              let elementTextarea = document.createElement('textarea');
              elementTextarea.classList.add('form__textarea');
              elementTextarea.style.width = '300px';
              elementTextarea.setAttribute('name', 'comment_hidden_reason');
              elementTextarea.setAttribute('placeholder', localeData.MODAL_COMMENT_HIDE_REASON_PLACEHOLDER);
              elementForm.append(elementTextarea);
              
              let interactiveModal = new Interactive('modal', {
                title: localeData.MODAL_COMMENT_HIDE_TITLE,
                content: elementForm
              });

              interactiveModal.target.addButton(localeData.BUTTON_SUBMIT_LABEL, () => {
                let formData = new FormData();
                formData.append('comment_id', commentID);
                formData.append('comment_is_hidden', 'on');
                formData.append('comment_hidden_reason', elementTextarea.value);

                let request = new Interactive('request', {
                  method: 'PATCH',
                  url: '/handler/entry/comment?localeMessage=' + window.CMSCore.locales.admin.name
                });
        
                request.target.data = formData;
        
                request.target.send().then((data) => {
                  interactiveModal.target.close();

                  if (data.statusCode == 1) {
                    window.location.reload();
                  }
                });
              });

              interactiveModal.target.addButton(localeData.BUTTON_CANCEL_LABEL, () => {
                interactiveModal.target.close();
              });

              interactiveModal.assembly();
              document.body.appendChild(interactiveModal.target.element);
              interactiveModal.target.show();
            }

            if (button.getAttribute('role') == 'comment-delete') {
              let interactiveModal = new Interactive('modal', {
                title: localeData.MODAL_ENTRY_COMMENT_DELETE_TITLE,
                content: localeData.MODAL_ENTRY_COMMENT_DELETE_DESCRIPTION
              });
              
              interactiveModal.target.addButton(localeData.BUTTON_DELETE_LABEL, () => {
                let formData = new FormData();
                formData.append('comment_id', commentID);

                let request = new Interactive('request', {
                  method: 'DELETE',
                  url: '/handler/entry/comment?localeMessage=' + window.CMSCore.locales.admin.name
                });
        
                request.target.data = formData;
        
                request.target.send().then((data) => {
                  interactiveModal.target.close();

                  if (data.statusCode == 1) {
                    tableItem.remove();
                    window.location.reload();
                  }
                });
              });

              interactiveModal.target.addButton(localeData.BUTTON_CANCEL_LABEL, () => {
                interactiveModal.target.close();
              });

              interactiveModal.assembly();
              document.body.appendChild(interactiveModal.target.element);
              interactiveModal.target.show();
            }
          });
        }
      }
    });
  }
}