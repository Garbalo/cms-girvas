/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Tool} from './tool.class.js';

export class ToolQuote extends Tool {
  constructor(editor, element) {
    super(editor, {
      name: 'quote',
      type: 'button',
      iconPath: `/core/JSLibrary/nadvoTE/images/icons/toolbar/quote.svg`,
      element: element
    });

    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      this.editor.textarea.replaceStringSelection(
        '> ' + this.editor.getSelectionString()
      );
    });
  }
}