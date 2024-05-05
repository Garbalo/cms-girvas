/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

/**
 * Парсер адресной строки браузера
 */
export class URLParser {
  /**
   * constructor
   */
  constructor() {
    this.path = this.getPathArray();
  }

  /**
   * Получить часть адреса по индексу
   * 
   * @param {Number} index 
   * @returns 
   */
  getPathPart(index) {
    return (typeof(this.path[index]) != 'undefined') ? this.path[index] : null;
  }

  /**
   * Получить весь адрес в виде массива
   * 
   * @returns {Array}
   */
  getPathArray() {
    return window.location.pathname.split('/');
  }

  /**
   * Получить весь адрес в виде строки
   * 
   * @returns {String}
   */
  getPathString() {
    return window.location.pathname;
  }
  
  /**
   * Получить значение параметра адреса
   * 
   * @param {*} name 
   * @returns 
   */
  getParam(name) {
    /** @var {URLSearchParams} */
    let urlSearchParams = new URLSearchParams(window.location.search);

    if (urlSearchParams.has(name)) {
      return urlSearchParams.get(name);
    }

    return null;
  }
}