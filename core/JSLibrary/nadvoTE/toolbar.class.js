'use strict';

import {ToolBold} from './toolbar/toolBold.class.js';
import {ToolItalic} from './toolbar/toolItalic.class.js';
import {ToolUnderline} from './toolbar/toolUnderline.class.js';
import {ToolHeader} from './toolbar/toolHeader.class.js';
import {ToolPreview} from './toolbar/toolPreview.class.js';
import {ToolSource} from './toolbar/toolSource.class.js';

export class Toolbar {
  constructor(editor, options = []) {
    this.editor = editor;
    this.options = options;
    this.tools = {};

    this.editor.toolbar = this;
    console.log(`[NADVO TE] Object toolbar created.`);
  }

  init() {
    let toolbarElement = this.editor.createElementUl();
    toolbarElement.classList.add('nadvo-te__toolbar-list');

    if (typeof(this.options) != 'undefined') {
      for (let optionItem of this.options) {
        let optionItemElement = this.editor.createElementLi();
        optionItemElement.classList.add('nadvo-te__toolbar-item');
        
        let optionItemInteractiveElement;
        if (optionItem.type == 'button') {
          optionItemInteractiveElement = this.editor.createElementButton(optionItem.name);
          optionItemInteractiveElement.classList.add('nadvo-te__toolbar-button');
        }

        if (optionItem.type == 'select') {
          optionItemIconContainerElement = this.editor.createElementDiv();
          optionItemIconContainerElement.classList.add('nadvo-te__toolbar-icon-container');

          optionItemInteractiveElement = this.editor.createElementDiv();
          optionItemInteractiveElement.classList.add('nadvo-te__toolbar-select');
        }

        switch (optionItem.name) {
          case 'bold': this.tools.bold = new ToolBold(this.editor, optionItemInteractiveElement); break;
          case 'italic': this.tools.italic = new ToolItalic(this.editor, optionItemInteractiveElement); break;
          case 'underline': this.tools.underline = new ToolUnderline(this.editor, optionItemInteractiveElement); break;
          case 'header': this.tools.header = new ToolHeader(this.editor, optionItemInteractiveElement); break;
          case 'preview': this.tools.preview = new ToolPreview(this.editor, optionItemInteractiveElement); break;
          case 'source': this.tools.source = new ToolSource(this.editor, optionItemInteractiveElement); break;
        }

        toolbarElement.appendChild(optionItemElement);
        optionItemElement.appendChild(optionItemInteractiveElement);
      }
    }

    this.element = toolbarElement;
  }
}