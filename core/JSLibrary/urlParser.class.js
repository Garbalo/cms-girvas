'use strict';

class URLParser {
  constructor() {
    this.path = this.getPath();
  }
  getPathPart(index) {
    return (typeof(this.path[index]) != 'undefined') ? this.path[index] : null;
  }
  getPath() {
    return window.location.pathname.split('/');
  }
  getParam(name) {
    let urlSearchParams = new URLSearchParams(window.location.search);
    if (urlSearchParams.has(name)) {
      return urlSearchParams.get(name);
    }

    return null;
  }
}