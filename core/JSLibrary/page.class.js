/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Andrey-Shestakov/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Andrey-Shestakov/cms-girvas/LICENSE.md
 */

'use strict';

import {PageEntry as PageDefaultEntry} from './page/entry.class.js';
import {PageEntry as PageAdminEntry} from './page/admin/entry.class.js';
import {PageEntries as PageAdminEntries} from './page/admin/entries.class.js';
import {PageEntriesCategory as PageAdminEntriesCategory} from './page/admin/entriesCategory.class.js';
import {PageEntriesCategories as PageAdminEntriesCategories} from './page/admin/entriesCategories.class.js';
import {PageEntriesComments as PageAdminEntriesComments} from './page/admin/entriesComments.class.js';
import {PagePages as PageAdminPages} from './page/admin/pages.class.js';
import {PagePageStatic as PageAdminPageStatic} from './page/admin/pageStatic.class.js';
import {PageMedia as PageAdminMedia} from './page/admin/media.class.js';
import {PageModule as PageAdminModule} from './page/admin/module.class.js';
import {PageModules as PageAdminModules} from './page/admin/modules.class.js';
import {PageSettings as PageAdminSettings} from './page/admin/settings.class.js';
import {PageTemplate as PageAdminTemplate} from './page/admin/template.class.js';
import {PageTemplates as PageAdminTemplates} from './page/admin/templates.class.js';
import {PageUser as PageAdminUser} from './page/admin/user.class.js';
import {PageUsers as PageAdminUsers} from './page/admin/users.class.js';
import {PageUsersGroup as PageAdminUsersGroup} from './page/admin/usersGroup.class.js';
import {PageUsersGroups as PageAdminUsersGroups} from './page/admin/usersGroups.class.js';
import {PageWebChannel as PageAdminWebChannel} from './page/admin/webChannel.class.js';
import {PageWebChannels as PageAdminWebChannels} from './page/admin/webChannels.class.js';
import {PageGlobal as PageAdminGlobal} from './page/admin/global.class.js';
import {PageGlobal as PageDefaultGlobal} from './page/global.class.js';
import {URLParser} from './urlParser.class.js';

export class Page {
  constructor(pageCategory, pageName, params = {}) {
    this.target = null;

    if (pageCategory == 'default') {
      switch (pageName) {
        case 'entry': this.target = new PageDefaultEntry(params); break;
        case 'global': this.target = new PageDefaultGlobal(params); break;
      }
    }

    if (pageCategory == 'admin') {
      switch (pageName) {
        case 'entry': this.target = new PageAdminEntry(params); break;
        case 'entries': this.target = new PageAdminEntries(params); break;
        case 'entriesCategory': this.target = new PageAdminEntriesCategory(params); break;
        case 'entriesCategories': this.target = new PageAdminEntriesCategories(params); break;
        case 'entriesComments': this.target = new PageAdminEntriesComments(params); break;
        case 'pages': this.target = new PageAdminPages(params); break;
        case 'pageStatic': this.target = new PageAdminPageStatic(params); break;
        case 'media': this.target = new PageAdminMedia(params); break;
        case 'module': this.target = new PageAdminModule(params); break;
        case 'modules': this.target = new PageAdminModules(params); break;
        case 'settings': this.target = new PageAdminSettings(params); break;
        case 'template': this.target = new PageAdminTemplate(params); break;
        case 'templates': this.target = new PageAdminTemplates(params); break;
        case 'user': this.target = new PageAdminUser(params); break;
        case 'users': this.target = new PageAdminUsers(params); break;
        case 'usersGroup': this.target = new PageAdminUsersGroup(params); break;
        case 'usersGroups': this.target = new PageAdminUsersGroups(params); break;
        case 'webChannel': this.target = new PageAdminWebChannel(params); break;
        case 'webChannels': this.target = new PageAdminWebChannels(params); break;
        case 'global': this.target = new PageAdminGlobal(params); break;
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
  let searchParams = new URLParser(), pages = {default: {}, admin: {}};

  if (searchParams.getPathPart(1) == 'entry') {
    pages.default.entry = new Page('default', 'entry');
  }

  if (searchParams.getPathPart(1) == 'admin') {
    if (searchParams.getPathPart(2) == 'entry') pages.admin.entry = new Page('admin', 'entry');
    if (searchParams.getPathPart(2) == 'entries') pages.admin.entries = new Page('admin', 'entries');
    if (searchParams.getPathPart(2) == 'entriesCategory') pages.admin.entriesCategory = new Page('admin', 'entriesCategory');
    if (searchParams.getPathPart(2) == 'entriesCategories') pages.admin.entriesCategories = new Page('admin', 'entriesCategories');
    if (searchParams.getPathPart(2) == 'entriesComments') pages.admin.entriesComments = new Page('admin', 'entriesComments');
    if (searchParams.getPathPart(2) == 'pages') pages.admin.pages = new Page('admin', 'pages');
    if (searchParams.getPathPart(2) == 'page') pages.admin.pageStatic = new Page('admin', 'pageStatic');
    if (searchParams.getPathPart(2) == 'media') pages.admin.media = new Page('admin', 'media');
    if (searchParams.getPathPart(2) == 'module') pages.admin.module = new Page('admin', 'module');
    if (searchParams.getPathPart(2) == 'modules') pages.admin.modules = new Page('admin', 'modules');
    if (searchParams.getPathPart(2) == 'settings') pages.admin.settings = new Page('admin', 'settings');
    if (searchParams.getPathPart(2) == 'template') pages.admin.template = new Page('admin', 'template');
    if (searchParams.getPathPart(2) == 'templates') pages.admin.templates = new Page('admin', 'templates');
    if (searchParams.getPathPart(2) == 'user') pages.admin.user = new Page('admin', 'user');
    if (searchParams.getPathPart(2) == 'users') pages.admin.users = new Page('admin', 'users');
    if (searchParams.getPathPart(2) == 'userGroup') pages.admin.usersGroup = new Page('admin', 'usersGroup');
    if (searchParams.getPathPart(2) == 'usersGroups') pages.admin.usersGroups = new Page('admin', 'usersGroups');
    if (searchParams.getPathPart(2) == 'webChannel') pages.admin.webChannel = new Page('admin', 'webChannel');
    if (searchParams.getPathPart(2) == 'webChannels') pages.admin.webChannels = new Page('admin', 'webChannels');

    pages.admin.global = new Page('admin', 'global');
  } else {
    pages.default.global = new Page('default', 'global');
  }
});