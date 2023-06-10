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