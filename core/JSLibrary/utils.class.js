/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

import {UString} from './utils/string.class.js';

'use strict';

/**
 * Утилиты
 */
export class Utils {
  constructor() {
    //
  }

  createString(string) {
    return new UString(string);
  }
}