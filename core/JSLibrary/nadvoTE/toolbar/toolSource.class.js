/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Tool} from './tool.class.js';

export class ToolSource extends Tool {
  constructor(editor, element) {
    super(editor, {
      name: 'source',
      type: 'button',
      iconPath: '/core/JSLibrary/nadvoTE/images/icons/toolbar/source.svg',
      element: element
    });

    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      
      if (this.editor.textareaVisual.element.classList.contains('nadvo-te__textarea-visual_is-showed')) {
        this.editor.textareaVisual.element.classList.remove('nadvo-te__textarea-visual_is-showed');
      }

      if (this.editor.textarea.element.classList.contains('nadvo-te__textarea_is-hidden')) {
        this.editor.textarea.element.classList.remove('nadvo-te__textarea_is-hidden');
      }
    });
  }
}