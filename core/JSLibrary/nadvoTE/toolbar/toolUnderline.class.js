'use strict';

import {Tool} from './tool.class.js';

export class ToolUnderline extends Tool {
  constructor(editor, element) {
    super(editor, {
      name: 'underline',
      type: 'button',
      iconPath: '/core/JSLibrary/nadvoTE/images/icons/toolbar/underline.svg',
      element: element
    });

    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      this.editor.textarea.replaceStringSelection(
        '<u>' + this.editor.getSelectionString() + '</u>'
      );
    });
  }
}