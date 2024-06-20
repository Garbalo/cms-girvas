/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

export class DataDot {
  constructor(x, y) {
    this.collision = false;
    this.x = x;
    this.y = y;

    this.data = null;
    this.label = null;
  }

  setData(value) {
    this.data = value;
  }

  getData() {
    return this.data;
  }

  setLabel(value) {
    this.label = value;
  }

  getLabel() {
    return this.label;
  }
}