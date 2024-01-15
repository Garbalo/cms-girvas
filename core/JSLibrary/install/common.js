'use strict';

import {Interactive} from "../interactive.class.js";
import {InstallationMaster} from "./installationMaster.class.js";

document.addEventListener('DOMContentLoaded', () => {
  let installationPagesElements = document.querySelectorAll('[data-page-index]');
  let interactiveChoicesDMSSelector = document.querySelector('[role="cms-dms-selector"]');

  let installationMaster = new InstallationMaster(installationPagesElements.length);
  
  installationMaster.buildPanel();
});