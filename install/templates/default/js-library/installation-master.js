'use strict';

class installationMaster {
  constructor(installationArea) {
    this.setStageID(0);

    this.setInstallationArea(installationArea);
    this.sectionInitialization();
    this.stagesBreadcumpsInitialization();
    this.formDatabaseGenerateInitialization();
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
    this.formDatabaseGenerate = $('[data-stage-event="4"] .form[data-not-handler]');
  }
  
  formDatabaseGenerateInitialization() {
    $('[data-stage-event="database-generate"]').click((event) => {
      this.generateDatabase(this.formDatabaseGenerate[0], 1);
    });
  }

  generateDatabase(form, stageID) {
    let generateStage = stageID;
    let formData = new FormData(form);

    $.ajax({
      method: 'POST',
      url: `/cron/api.php?query=install&event=database-generate&stage=${stageID}`,
      xhrFields: { 
				withCredentials: true 
			},
      data: formData,
      cache: false,
			processData: false,
			contentType: false,
      enctype: 'multipart/form-data',
      success: (data) => {
        let dataJSON = JSON.parse(data);

        console.log(dataJSON.message);
        if (generateStage < 2) {
          this.generateDatabase(form, generateStage + 1);
        }
      }
    });
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