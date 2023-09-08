'use strict';

import {Tool} from './tool.class.js';

export class ToolHeader extends Tool {
  constructor(editor, element, level) {
    super(editor, {
      name: 'header' + level,
      type: 'button',
      iconPath: `/core/JSLibrary/nadvoTE/images/icons/toolbar/header${level}.svg`,
      element: element
    });

    this.level = level;
    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      this.editor.textarea.replaceStringSelection(
        '#'.repeat(this.level) + ' ' + this.editor.getSelectionString()
      );
    });
  }
}