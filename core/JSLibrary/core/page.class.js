/**
 * CMS GIRVAS (https://www.cms-girvas.ru/)
 * 
 * @link        https://github.com/Garbalo/cms-girvas Путь до репозитория системы
 * @copyright   Copyright (c) 2022 - 2024, Andrey Shestakov & Garbalo (https://www.garbalo.com/)
 * @license     https://github.com/Garbalo/cms-girvas/LICENSE.md
 */

'use strict';

import {PageProfile as PageDefaultProfile} from './page/profile.class.js';
import {PageEntry as PageDefaultEntry} from './page/entry.class.js';
import {PageAnalytics as PageAdminAnalytics} from './page/admin/analytics.class.js';
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
import {URLParser} from "../urlParser.class.js";

export class Page {
  constructor(core, pageCategory, pageName, params = {}) {
    this.target = null;
    this.core = core;

    let searchParams = new URLParser();

    if (pageCategory == 'default') {
      switch (pageName) {
        case 'entry': this.target = new PageDefaultEntry(this, params); break;
        case 'global': this.target = new PageDefaultGlobal(this, params); break;
        case 'profile': this.target = new PageDefaultProfile(this, params); break;
      }
    }

    if (pageCategory == 'admin') {
      switch (pageName) {
        case 'analytics': this.target = new PageAdminAnalytics(this, params); break;
        case 'entry': this.target = new PageAdminEntry(this, params); break;
        case 'entries': this.target = new PageAdminEntries(this, params); break;
        case 'entriesCategory': this.target = new PageAdminEntriesCategory(this, params); break;
        case 'entriesCategories': this.target = new PageAdminEntriesCategories(this, params); break;
        case 'entriesComments': this.target = new PageAdminEntriesComments(this, params); break;
        case 'pages': this.target = new PageAdminPages(this, params); break;
        case 'pageStatic': this.target = new PageAdminPageStatic(this, params); break;
        case 'media': this.target = new PageAdminMedia(this, params); break;
        case 'module': this.target = new PageAdminModule(this, params); break;
        case 'modules': this.target = new PageAdminModules(this, params); break;
        case 'settings': this.target = new PageAdminSettings(this, params); break;
        case 'template': this.target = new PageAdminTemplate(this, params); break;
        case 'templates': this.target = (searchParams.getPathPart(4) != null) ? new PageAdminTemplate(this, params) : new PageAdminTemplates(this, params); break;
        case 'user': this.target = new PageAdminUser(this, params); break;
        case 'users': this.target = new PageAdminUsers(this, params); break;
        case 'usersGroup': this.target = new PageAdminUsersGroup(this, params); break;
        case 'usersGroups': this.target = new PageAdminUsersGroups(this, params); break;
        case 'webChannel': this.target = new PageAdminWebChannel(this, params); break;
        case 'webChannels': this.target = new PageAdminWebChannels(this, params); break;
        case 'global': this.target = new PageAdminGlobal(this, params); break;
      }
    }

    if (this.target != null) {
      this.init();
    }
  }

  init() {
    this.target.init();

    window.CMSCore.debugLog(1, 'CMSCore', `Page "${this.target.constructor.name} inited!"`, true);
  }
}