/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';
import {Tool} from './tool.class.js';

export class ToolBold extends Tool {
  constructor(editor, element) {
    super(editor, {
      name: 'bold',
      type: 'button',
      iconPath: '/core/JSLibrary/nadvoTE/images/icons/toolbar/bold.svg',
      element: element
    });

    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      this.editor.textarea.replaceStringSelection(
        '**' + this.editor.getSelectionString() + '**'
      );
    });
  }
}