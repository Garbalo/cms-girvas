'use strict';

import {PageEntry as PageDefaultEntry} from './page/entry.class.js';
import {URLParser} from './urlParser.class.js';

export class Page {
  constructor(pageCategory, pageName, params = {}) {
    this.target = null;

    if (pageCategory == 'default') {
      switch (pageName) {
        case 'entry': this.target = new PageDefaultEntry(params); break;
      }
    }

    if (this.target != null) {
      this.init();
    }
  }

  init() {
    this.target.init();
    console.log(`Page "${this.target.constructor.name} inited!"`);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  let searchParams = new URLParser();
  let page = new Page('default', 'entry');
});