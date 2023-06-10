'use strict';

import {Tool} from './tool.class.js';

export class ToolHeader extends Tool {
  constructor(editor, element) {
    super(editor, {
      name: 'header',
      type: 'select',
      iconPath: '/core/JSLibrary/nadvoTE/images/icons/toolbar/header.svg',
      element: element
    });

    this.initClickEvent();
  }

  initClickEvent() {
    super.addClickEvent(() => {
      console.log(`[NADVO TE] Tool ${this.name} clicked!`);
      
    });
  }
}