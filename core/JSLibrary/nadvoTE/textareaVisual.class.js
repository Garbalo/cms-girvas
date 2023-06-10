'use strict';

export class TextareaVisual {
  constructor(editor, options = []) {
    this.editor = editor;
    this.options = options;

    this.editor.textareaVisual = this;
    console.log(`[NADVO TE] Object textarea visual created.`);
  }

  init() {
    this.element = this.editor.createElementDiv();
    this.element.classList.add('nadvo-te__textarea-visual');
  }
}