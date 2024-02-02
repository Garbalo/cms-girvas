/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

export class URLParser {
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