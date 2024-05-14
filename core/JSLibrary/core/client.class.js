/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {Locale} from './locale.class.js';
import {URLParser} from '../urlParser.class.js';

/**
 * Клиент
 */
export class Client {
  constructor(core) {
    this.core = core;
  }

  /**
   * Получение IP-адреса клиента
   * @returns {String}
   */
  async getIPAddress() {
    return fetch('/handler/client/ip-address', {
      method: 'GET'
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      if (data.outputData.hasOwnProperty('result')) {
        return data.outputData.result; 
      }

      return '0.0.0.0';
    });
  }

  /**
   * Проверка авторизации клиента
   * @returns {Boolean}
   */
  async isLogged() {
    return fetch('/handler/client/is-logged', {
      method: 'GET'
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      if (data.outputData.hasOwnProperty('result')) {
        return data.outputData.result; 
      }

      return false;
    });
  }
}