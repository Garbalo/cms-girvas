'use strict';

import {Interactive} from "../interactive.class.js";

export class PageEntry {
  constructor(params = {}) {
    this.commentsLimit = 0;
    this.commentsData = [];
  }

  init() {
    let elementEntry = document.querySelector('[role="entry"]');
    let elementEntryCommentForm = document.querySelector('[role="entry"]');
    let entryCommentsListElement = elementEntry.querySelector('[role="entry-comments-list"]');
    let entryID = elementEntry.getAttribute('data-entry-id');
    let userPermissions = {};
    let clientUserData = [];

    this.commentsLimit = entryCommentsListElement.querySelectorAll('[role="entry-comment"]').length;
    console.log(this.commentsLimit);
    this.commentsOffset = 0;

    fetch('/handler/user/@me/permissions', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      userPermissions = data.outputData.user.permissions;
      
      return fetch(`/handler/entry/${entryID}/comments`, {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      this.commentsData = data.outputData.comments;

      let entryCommentsContainerElement = elementEntry.querySelector('[role="entry-comments-container"]');
      let interactiveButtonCommentsLoad = new Interactive('button');
      interactiveButtonCommentsLoad.target.setLabel('Загрузить еще комментарии');
      interactiveButtonCommentsLoad.target.setCallback((event) => {
        this.commentsOffset += this.commentsLimit;

        fetch(`/handler/entry/${entryID}/comments?limit=${this.commentsLimit}&offset=${this.commentsOffset}&sortColumn=created_unix_timestamp&sortType=desc`, {method: 'GET'}).then((response) => {
          return (response.ok) ? response.json() : Promise.reject(response);
        }).then((loadedData) => {
          let commentsLoadedData = loadedData.outputData.comments;
          let commentsLoadedDataIndex = 0;

          let getCommentAssembled = (comment) => {
            fetch(`/handler/user/${comment.authorID}`, {method: 'GET'}).then((response) => {
              return (response.ok) ? response.json() : Promise.reject(response);
            }).then((loadedData2) => {
              let userLoadedData = loadedData2.outputData.user;
              let entryCommentsCount = entryCommentsListElement.querySelectorAll('[role="entry-comment"]').length;
              let commentContent = (!comment.isHidden) ? comment.content : comment.hiddenReason;
  
              let requestData = {
                templateCategory: 'default',
                templateFilePath: 'templates/page/entry/comment.tpl',
                patternNames: ['COMMENT_ID', 'COMMENT_INDEX', 'COMMENT_CONTENT', 'COMMENT_AUTHOR_LOGIN', 'COMMENT_AUTHOR_AVATAR_URL'],
                patternValues: [
                  comment.id,
                  entryCommentsCount + 1,
                  commentContent.replaceAll(",", "{DELIM}"),
                  userLoadedData.login,
                  userLoadedData.avatarURL
                ]
              };
  
              let urlSearchParams = new URLSearchParams(requestData);
  
              return fetch('/handler/template/assembly?' + urlSearchParams.toString(), {method: 'GET'});
            }).then((response) => {
              return (response.ok) ? response.json() : Promise.reject(response);
            }).then((loadedData2) => {
              let templateAssembled = loadedData2.outputData.templateAssembled;
              let htmlElement = document.createElement('div');
              htmlElement.innerHTML = templateAssembled;

              if (comment.isHidden) {
                htmlElement.firstChild.classList.add('comment_is-hidden');
              }

              entryCommentsListElement.append(htmlElement.firstChild);

              commentsLoadedDataIndex++;
              if (commentsLoadedDataIndex < this.commentsLimit) {
                getCommentAssembled(commentsLoadedData[commentsLoadedDataIndex]);
              }
            });
          };

          if (commentsLoadedData.length > 0) {
            getCommentAssembled(commentsLoadedData[0]);
          }
        });
      });

      interactiveButtonCommentsLoad.target.assembly();
      entryCommentsContainerElement.append(interactiveButtonCommentsLoad.target.assembled);
      
      return fetch(`/handler/user/@me`, {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      clientUserData = data.outputData.user;

      let comments = document.querySelectorAll('[role="entry-comment"]');
      
      comments.forEach((comment, commentIndex) => {
        let commentPanel = comment.querySelector('[id^=E7453975856\_]');
        let commentID = comment.getAttribute('data-comment-id');
        let commentData = {};
        
        this.commentsData.forEach((element) => {
          if (element.id == commentID) {
            commentData = element;
            return;
          }
        });

        if (clientUserData.id == commentData.authorID) {
          let interactiveButtonEdit = new Interactive('button');
          interactiveButtonEdit.target.setLabel('Редактировать');
          interactiveButtonEdit.target.assembly();
          commentPanel.append(interactiveButtonEdit.target.assembled);
        }

        if (userPermissions.moder_entries_comments_management) {
          let interactiveButtonHide = new Interactive('button');
          interactiveButtonHide.target.setLabel('Скрыть');
          interactiveButtonHide.target.setCallback((event) => {
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
          });

          interactiveButtonHide.target.assembly();
          commentPanel.append(interactiveButtonHide.target.assembled);

          if (commentData.isHidden) {
            interactiveButtonHide.target.assembled.style.display = 'none';
          }
        }

        if (userPermissions.moder_entries_comments_management) {
          let interactiveButtonPublish = new Interactive('button');
          interactiveButtonPublish.target.setLabel('Опубликовать');
          interactiveButtonPublish.target.setCallback((event) => {
            event.preventDefault();

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
          });
          interactiveButtonPublish.target.assembly();
          commentPanel.append(interactiveButtonPublish.target.assembled);

          if (!commentData.isHidden) {
            interactiveButtonPublish.target.assembled.style.display = 'none';
          }
        }

        if (userPermissions.moder_entries_comments_management) {
          let interactiveButtonDelete = new Interactive('button');
          interactiveButtonDelete.target.setLabel('Удалить');
          interactiveButtonDelete.target.setCallback((event) => {
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
          interactiveButtonDelete.target.assembly();
          commentPanel.append(interactiveButtonDelete.target.assembled);
        }

        if (!commentData.isHidden) {
          let interactiveButtonAnswer = new Interactive('button');
          interactiveButtonAnswer.target.setLabel('Ответить');
          interactiveButtonAnswer.target.assembly();
          commentPanel.append(interactiveButtonAnswer.target.assembled);
            
          let commentRatePanel = comment.querySelector('[role="entry-comment-rate"]');
          
          if (userPermissions.base_entry_comment_rate) {
            let interactiveButtonRateUp = new Interactive('button');
            interactiveButtonRateUp.target.setLabel('🡅');
            interactiveButtonRateUp.target.setCallback((event) => {
              let formData = new FormData();
              formData.append('comment_id', commentID);
              formData.append('comment_rating_vote', 'up');

              fetch('/handler/entry/comment', {
                method: 'PATCH',
                body: formData
              }).then((response) => {
                return response.json();
              }).then((data) => {
                let notification = new PopupNotification(data.message, document.body, true);
                notification.show();
              });
            });
            interactiveButtonRateUp.target.assembly();

            let interactiveButtonElement = interactiveButtonRateUp.target.assembled.querySelector('button');
            interactiveButtonElement.classList.add('interactive__button_green');

            commentRatePanel.append(interactiveButtonRateUp.target.assembled);
          }

          let rateCountElement = document.createElement('div');
          rateCountElement.classList.add('comment__rating-count');
          rateCountElement.append(commentData.rating);
          commentRatePanel.append(rateCountElement);

          if (userPermissions.base_entry_comment_rate) {
            let interactiveButtonRateDown = new Interactive('button');
            interactiveButtonRateDown.target.setLabel('🡇');
            interactiveButtonRateDown.target.setCallback((event) => {
              let formData = new FormData();
              formData.append('comment_id', commentID);
              formData.append('comment_rating_vote', 'down');

              fetch('/handler/entry/comment', {
                method: 'PATCH',
                body: formData
              }).then((response) => {
                return response.json();
              }).then((data) => {
                let notification = new PopupNotification(data.message, document.body, true);
                notification.show();
              });
            });
            interactiveButtonRateDown.target.assembly();

            let interactiveButtonElement = interactiveButtonRateDown.target.assembled.querySelector('button');
            interactiveButtonElement.classList.add('interactive__button_red');

            commentRatePanel.append(interactiveButtonRateDown.target.assembled);
          }
        }

        if (commentData.isHidden) {
          comment.classList.add('comment_is-hidden');
        }
      });
    });
  }

  
}