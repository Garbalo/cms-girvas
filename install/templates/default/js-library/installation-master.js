'use strict';

class installationMaster {
  constructor(installationArea) {
    this.setStageID(0);

    this.setInstallationArea(installationArea);
    this.sectionInitialization();
    this.stagesBreadcumpsInitialization();
    this.buttonPanelInitialization();

    this.updateInstallationArea();
  }

  setStageID(value) {
    this.stageID = value;
  }

  sectionInitialization() {
    this.installationStagesBreadcumps = $('[role="installationStages"] > li');
    this.installationStagesSections = $('[data-stage-index]');
    this.installationButtonsPanel = $('[data-stage-event]');
  }

  buttonPanelInitialization() {
    for (let buttonPanel of this.installationButtonsPanel) {
      $(buttonPanel).click(() => {
        let nextStage;

        if ($(buttonPanel).attr('data-stage-event') == 'next') {
          if (this.stageID < this.installationStagesBreadcumps.length - 1) {
            this.setStageID(this.stageID + 1);
          }
        }

        if ($(buttonPanel).attr('data-stage-event') == 'last') {
          if (this.stageID > 0) {
            this.setStageID(this.stageID - 1);
          }
        }

        if ($(buttonPanel).attr('data-stage-event') == 'finished') {
          //
        }
        
        this.updateInstallationArea();
      });
    }
  }

  stagesBreadcumpsInitialization() {
    let stageIndex = 0;
    
    for (let stageBreadcump of this.installationStagesBreadcumps) {
      let newIndex = stageIndex;
      
      $(stageBreadcump).click(() => {
        this.setStageID(newIndex);
        this.updateInstallationArea();
      });

      stageIndex++;
    }
  }

  updateInstallationArea() {
    this.updateInstallationBreadcumps();
    this.updateInstallationStageSections();
  }

  updateInstallationBreadcumps() {
    let stageIndex = 0;
    
    for (let stageBreadcump of this.installationStagesBreadcumps) {
      $(stageBreadcump).removeClass('install-stages-list__item_active');
      
      if (stageIndex === this.stageID) {
        $(stageBreadcump).addClass('install-stages-list__item_active');
      }
      
      stageIndex++;
    }
  }

  updateInstallationStageSections() {
    let stageIndex = 0;
    
    for (let stageSection of this.installationStagesSections) {
      $(stageSection).css('display', 'none');
      
      if (stageIndex === this.stageID) {
        $(stageSection).css('display', 'block');
      }
      
      stageIndex++;
    }
  }

  setInstallationArea(installationArea) {
    this.installationArea = installationArea;
  }
}