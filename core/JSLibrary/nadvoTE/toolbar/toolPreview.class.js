/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Tool} from './tool.class.js';

export class ToolPreview extends Tool {
  constructor(editor, element) {
    super(editor, {
      name: 'preview',
      type: 'button',
      iconPath: '/core/JSLibrary/nadvoTE/images/icons/toolbar/preview.svg',
      element: element
    });

    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      
      if (typeof(this.editor.options.handler) != 'undefined') {
        let formData = new FormData();
        formData.append('markdown_text', this.editor.textarea.element.value);

        fetch(this.editor.options.handler, {
          method: 'POST',
          dataType: 'json',
          body: formData
        }).then((response) => {
          return response.json();
        }).then((data) => {
          this.editor.textareaVisual.element.innerHTML = data.outputData.parsedown;
        }).catch((error) => {
          console.error(error);
        });
      }

      if (!this.editor.textareaVisual.element.classList.contains('nadvo-te__textarea-visual_is-showed')) {
        this.editor.textareaVisual.element.classList.add('nadvo-te__textarea-visual_is-showed');
      }

      if (!this.editor.textarea.element.classList.contains('nadvo-te__textarea_is-hidden')) {
        this.editor.textarea.element.classList.add('nadvo-te__textarea_is-hidden');
      }
    });
  }
}