/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Interactive} from "../../interactive.class.js";
import {ElementButton} from "../../interactive/form/elementButton.class.js";
import {ElementTextarea} from "../../interactive/form/elementTextarea.class.js";
import {EntryComment} from "./entry/comment.class.js";

export class PageEntry {
  constructor(page, params = {}) {
    this.page = page;
    
    this.commentsLimit = 0;
    this.commentsData = [];
    this.commentForm = null;
  }

  init() {
    let elementEntry = document.querySelector('[role="entry"]');
    let entryCommentsListElement = elementEntry.querySelector('[role="entryCommentsList"]');
    let entryID = elementEntry.getAttribute('data-entry-id');
    this.clientUserPermissions = {};
    this.clientUserData = {};

    this.commentsLimit = (entryCommentsListElement != null) ? entryCommentsListElement.querySelectorAll('[role="entryComment"]').length : 0;
    this.commentsOffset = 0;
    this.postLoadComments = [];
    this.comments = [];

    let locales;

    fetch('/handler/client/is-logged', {method: 'GET'}).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      return fetch('/handler/locales', {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      locales = data.outputData.locales;
      return window.CMSCore.locales.base.getData();
    }).then((localeData) => {
      this.localeBaseData = localeData;
      return fetch(`/handler/user/@me/permissions?localeMessage=${window.CMSCore.locales.base.name}`, {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      if (typeof data.outputData.user != 'undefined') {
        this.clientUserPermissions = data.outputData.user.permissions;

        /** @type {Interactive} */
        let commentForm = new Interactive('form');
        commentForm.target.init({
          id: 'E7429674077',
          method: 'PUT',
          action: `/handler/entry/comment`,
          role: 'entryCommentForm'
        });

        let commentFormElement = commentForm.target.element.querySelector('form');
        commentFormElement.classList.add('form_entry-comment');
        commentFormElement.setAttribute('readonly', '');

        commentForm.target.successCallback = (data) => {
          if (data.outputData.hasOwnProperty('comment')) {
            if (commentForm.target.element.firstChild.getAttribute('method') == 'PUT') {
              let commentID = data.outputData.comment.id;
              let commentData = {}, authorData = {};
              
              fetch(`/handler/entry/comment/${commentID}?localeMessage=${window.CMSCore.locales.base.name}`, {method: 'GET'}).then((response) => {
                return (response.ok) ? response.json() : Promise.reject(response);
              }).then((commentCreatedData) => {
                commentData = commentCreatedData.outputData.comment;
                return fetch(`/handler/user/${commentData.authorID}?localeMessage=${window.CMSCore.locales.base.name}`, {method: 'GET'});
              }).then((response) => {
                return (response.ok) ? response.json() : Promise.reject(response);
              }).then((authorCommentCreatedData) => {
                authorData = authorCommentCreatedData.outputData.user;

                let newEntryComment = new EntryComment(this, commentData);
                newEntryComment.assembly({login: authorData.login, avatarURL: authorData.avatarURL}, (commentElement) => {
                  entryCommentsListElement.prepend(commentElement);
                  newEntryComment.initPanel(this.clientUserData, this.clientUserPermissions);
                });
              });
            }

            if (commentForm.target.element.firstChild.getAttribute('method') == 'PATCH') {
              let commentID = data.outputData.comment.id;
              let commentContent = data.outputData.comment.content;
              let commentElement = elementEntry.querySelector(`[data-comment-id="${commentID}"]`);
              if (commentElement != null) {
                let commentContentElement = commentElement.querySelector('[role="entryCommentContent"]');
                commentContentElement.innerHTML = commentContent;
              }
            }
          } else {
            let notification = new PopupNotification(data.message, document.body, true);
            notification.show();
          }
        };
        
        /** @type {ElementInput} */
        let formInputEntryID = commentForm.target.createElementInput();
        formInputEntryID.init({
          name: 'comment_entry_id',
          type: 'hidden'
        });

        formInputEntryID.element.value = entryID;
        
        /** @type {ElementInput} */
        let formInputParentID = commentForm.target.createElementInput();
        formInputParentID.init({
          name: 'comment_parent_id',
          type: 'hidden'
        });
        
        /** @type {ElementTextarea} */
        let formTextarea = commentForm.target.createElementTextarea();
        formTextarea.init({
          name: 'comment_content',
          placeholder: this.localeBaseData.ENTRY_COMMENT_TEXTAREA_PLACEHOLDER,
          rows: 6
        });

        /** @type {ElementButton} */
        let formButton = commentForm.target.createElementButton();
        formButton.setStringLabel(this.localeBaseData.BUTTON_SEND_LABEL);
        formButton.setClickEvent((event) => {
          event.preventDefault();

          if (formTextarea.element.value != '') {
            commentForm.target.send();
          }

          formTextarea.element.value = '';

          let formInputCommentID = commentForm.target.element.querySelector('[name="comment_id"]');
          if (formInputCommentID != null) {
            let commentTargetElement = elementEntry.querySelector(`[data-comment-id="${formInputCommentID.value}"]`);
            commentTargetElement.scrollIntoView({block: "center", behavior: "smooth"});
            formInputCommentID.remove();

            if (commentForm.target.element.firstChild.hasAttribute('method')) {
              if (commentForm.target.element.firstChild.getAttribute('method') == 'PATCH') {
                commentForm.target.element.firstChild.setAttribute('method', 'PUT');
              }
            }
          }

          let formButtonRest = commentForm.target.element.querySelector('[role="comment-form-button-reset"]');
          if (formButtonRest != null) formButtonRest.remove();
        });
        formButton.init({
          role: 'comment-form-button-send'
        });

        let commentFormContainerElement = document.querySelector('[role="entryCommentFormContainer"]');

        if (commentFormContainerElement != null) {
          let commentFormPanelElement = document.createElement('div');
          commentFormPanelElement.classList.add('form__panel-container');
          commentFormPanelElement.append(formButton.element);

          // Assembly form
          commentForm.target.element.setAttribute('id', 'E7443753064');
          commentForm.target.element.firstChild.append(formInputParentID.element);
          commentForm.target.element.firstChild.append(formInputEntryID.element);
          commentForm.target.element.firstChild.append(formTextarea.element);
          commentForm.target.element.firstChild.append(commentFormPanelElement);
          commentForm.assembly();

          this.commentForm = commentForm;

          // Append form to container
          commentFormContainerElement.append(this.commentForm.target.element);
        }
      }

      return fetch(`/handler/entry/${entryID}/comments?localeMessage=${window.CMSCore.locales.base.name}&limit=${this.commentsLimit}&offset=${this.commentsOffset}&sortColumn=created_unix_timestamp&sortType=desc&parentID=0`, {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      this.postLoadComments = data.outputData.comments;
      
      let entryCommentsContainerElement = elementEntry.querySelector('[role="entryCommentsContainer"]');
      let interactiveButtonCommentsLoad = new Interactive('button');
      interactiveButtonCommentsLoad.target.setLabel(this.localeBaseData.BUTTON_LOAD_MORE_COMMENTS_LABEL);
      interactiveButtonCommentsLoad.target.setCallback((event) => {
        let request = new Interactive('request', {
          method: 'GET',
          url: `/handler/entry/${entryID}/comments?localeMessage=${window.CMSCore.locales.base.name}&limit=${this.commentsLimit}&offset=${this.commentsOffset}&sortColumn=created_unix_timestamp&sortType=desc&parentID=0`
        });

        request.target.showingNotification = false;

        request.target.send().then((data1) => {
          if (data1.statusCode == 1 && data1.outputData.hasOwnProperty('comments')) {
            let comments = data1.outputData.comments, commentLoadedIndex = 0;
            
            let appendComment = (commentData) => {
              let requestAppend = new Interactive('request', {
                method: 'GET',
                url: `/handler/user/${commentData.authorID}`
              });

              requestAppend.target.showingNotification = false;

              requestAppend.target.send().then((authorLoadedData) => {
                let authorData = authorLoadedData.outputData.user;
                
                commentData.index = entryCommentsListElement.querySelectorAll('[role="entryComment"]').length + 1;
                commentData.entryID = entryID;
                commentData.answersLoadingLimit = 4;

                let entryComment = new EntryComment(this, commentData);
                entryComment.assembly({login: authorData.login, avatarURL: authorData.avatarURL}, (commentElement) => {
                  commentLoadedIndex++;
                  this.commentsOffset++;
                  entryCommentsListElement.append(commentElement);

                  entryComment.initPanel(this.clientUserData, this.clientUserPermissions);

                  if (commentLoadedIndex < comments.length) {
                    appendComment(comments[commentLoadedIndex]);
                  }
                  
                  if (commentData.answersCount > 0) {
                    entryComment.initAnswersPanel(this.clientUserData, this.clientUserPermissions);
                  }
                });
              });
            };

            if (comments.length > 0) {
              appendComment(comments[0]);
            }
          }
        });
      });

      interactiveButtonCommentsLoad.assembly();
      entryCommentsContainerElement.append(interactiveButtonCommentsLoad.target.element);
      
      return fetch(`/handler/user/@me?localeMessage=${window.CMSCore.locales.base.name}`, {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      this.clientUserData = (data.outputData.hasOwnProperty('user')) ? Object.assign(data.outputData.user) : this.clientUserData;

      let commentsElements = (entryCommentsListElement != null) ? entryCommentsListElement.querySelectorAll('[role="entryComment"]') : [];
      commentsElements.forEach((comment, commentIndex) => {
        if (typeof(this.postLoadComments) != 'undefined') {
          this.postLoadComments[commentIndex].entryID = entryID;
          let entryComment = new EntryComment(this, this.postLoadComments[commentIndex]);
          entryComment.elementAssembled = comment;
          entryComment.answersLoadingLimit = 4;
          entryComment.initPanel(this.clientUserData, this.clientUserPermissions);
          
          if (entryComment.answersCount > 0) {
            entryComment.initAnswersPanel(this.clientUserData, this.clientUserPermissions);
          }
          
          if (entryComment.isHidden) {
            comment.classList.add('comment_is-hidden');
          }

          this.commentsOffset++;
        }
      });
    });

    let commentFormElement = document.querySelector('[role="entryCommentForm"]');
    if (commentFormElement != null) {
      if (commentFormElement.hasAttribute('readonly')) {
        commentFormElement.removeAttribute('readonly');
      }
    }
  }
}