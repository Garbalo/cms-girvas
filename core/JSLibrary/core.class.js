/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {Locale} from './core/locale.class.js';
import {Page} from './core/page.class.js';
import {URLParser} from './urlParser.class.js';

export class Core {
  constructor() {
    this.searchParams = new URLParser();
    this.pages = {default: {}, admin: {}};
    this.locales = {base: {}, admin: {}};
  }

  async initLocales() {
    return fetch('/handler/locale/base', {
      method: 'GET'
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      this.locales.base = new Locale(data.outputData.locale.name, 'base');
      return fetch('/handler/locale/admin', {method: 'GET'});
    }).then((response) => {
      return (response.ok) ? response.json() : Promise.reject(response);
    }).then((data) => {
      this.locales.admin = new Locale(data.outputData.locale.name, 'admin');
      console.log('[CMSCore] Locales initied!');
    });
  }
  
  async initPages() {
    if (this.searchParams.getPathPart(1) == 'entry') {
      this.pages.default.entry = new Page('default', 'entry');
    }
  
    if (this.searchParams.getPathPart(1) == 'admin') {
      if (this.searchParams.getPathPart(2) == 'entry') this.pages.admin.entry = new Page('admin', 'entry');
      if (this.searchParams.getPathPart(2) == 'entries') this.pages.admin.entries = new Page('admin', 'entries');
      if (this.searchParams.getPathPart(2) == 'entriesCategory') this.pages.admin.entriesCategory = new Page('admin', 'entriesCategory');
      if (this.searchParams.getPathPart(2) == 'entriesCategories') this.pages.admin.entriesCategories = new Page('admin', 'entriesCategories');
      if (this.searchParams.getPathPart(2) == 'entriesComments') this.pages.admin.entriesComments = new Page('admin', 'entriesComments');
      if (this.searchParams.getPathPart(2) == 'pages') this.pages.admin.pages = new Page('admin', 'pages');
      if (this.searchParams.getPathPart(2) == 'page') this.pages.admin.pageStatic = new Page('admin', 'pageStatic');
      if (this.searchParams.getPathPart(2) == 'media') this.pages.admin.media = new Page('admin', 'media');
      if (this.searchParams.getPathPart(2) == 'module') this.pages.admin.module = new Page('admin', 'module');
      if (this.searchParams.getPathPart(2) == 'modules') this.pages.admin.modules = new Page('admin', 'modules');
      if (this.searchParams.getPathPart(2) == 'settings') this.pages.admin.settings = new Page('admin', 'settings');
      if (this.searchParams.getPathPart(2) == 'template') this.pages.admin.template = new Page('admin', 'template');
      if (this.searchParams.getPathPart(2) == 'templates') this.pages.admin.templates = new Page('admin', 'templates');
      if (this.searchParams.getPathPart(2) == 'user') this.pages.admin.user = new Page('admin', 'user');
      if (this.searchParams.getPathPart(2) == 'users') this.pages.admin.users = new Page('admin', 'users');
      if (this.searchParams.getPathPart(2) == 'userGroup') this.pages.admin.usersGroup = new Page('admin', 'usersGroup');
      if (this.searchParams.getPathPart(2) == 'usersGroups') this.pages.admin.usersGroups = new Page('admin', 'usersGroups');
      if (this.searchParams.getPathPart(2) == 'webChannel') this.pages.admin.webChannel = new Page('admin', 'webChannel');
      if (this.searchParams.getPathPart(2) == 'webChannels') this.pages.admin.webChannels = new Page('admin', 'webChannels');
  
      this.pages.admin.global = new Page('admin', 'global');
    } else {
      this.pages.default.global = new Page('default', 'global');
    }

    console.log('[CMSCore] Pages initied!');

    return true;
  }
}

document.addEventListener('DOMContentLoaded', async () => {
  window.CMSCore = new Core();
  await window.CMSCore.initLocales().then(() => {
    return window.CMSCore.initPages();
  });
});