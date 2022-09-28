'use strict';

class installationMaster {
  constructor(installationArea) {
    this.setStageID(0);

    this.setInstallationArea(installationArea);
    this.sectionInitialization();
    this.stagesBreadcumpsInitialization();
    this.formDatabaseGenerateInitialization();
    this.formAdminCreateInitialization();
    this.buttonPanelInitialization();

    this.updateInstallationArea();
  }



  setStageID(value) {
    this.stageID = value;
  }

  sectionInitialization() {
    this.sectionStages = [];
    for (let stageIndex = 0; stageIndex < 7; stageIndex++) {
      let section = $(`[data-stage-index="${stageIndex + 1}"]`)[0];
      this.sectionStages.push(section);
    }

    this.installationStagesBreadcumps = $('[role="installationStages"] > li');
    this.installationStagesSections = $('[data-stage-index]');
    this.installationButtonsPanel = $('[data-stage-event]');
    this.formDatabaseGenerate = $(this.sectionStages[3]).find('.form[data-not-handler]')[0];
    this.formAdminCreate = $(this.sectionStages[4]).find('.form[data-not-handler]')[0];
    
  }
  
  formDatabaseGenerateInitialization() {
    $('[data-stage-event="database-generate"]').click((event) => {
      this.generateDatabase(this.formDatabaseGenerate, 1);
    });
  }
  
  formAdminCreateInitialization() {
    $('[data-stage-event="admin-create"]').click((event) => {
      this.adminCreate(this.formAdminCreate);
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

        let consoleEmulate = $('.section__block_console-log')[0];
        let consoleEmulateContent = $(consoleEmulate).find('.content__container')[0];
        $(consoleEmulateContent).append($('<p/>', {html: dataJSON.message}));

        if (generateStage < 3) {
          this.generateDatabase(form, generateStage + 1);
        }
      }
    });
  }

  /**
   * Get class for notice
   * 
   * @param {int} messageType 
   * @returns 
   */
  getNoticeClass(messageType) {
    switch (messageType) {
      case 1: return 'section__notice section__notice_success';
      case 2: return 'section__notice section__notice_error';
      case 3: return 'section__notice section__notice_info';
      case 4: return 'section__notice';
    }
  }

  adminCreate(form) {
    let formData = new FormData(form);
    
    $.ajax({
      method: 'POST',
      url: `/cron/api.php?query=install&event=admin-create`,
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
        
        let noticeContainer = $(this.sectionStages[4]).find('.section__notice-container')[0];
        let notice = $('<div/>', {class: this.getNoticeClass(dataJSON.messageType), html: dataJSON.message});
        $(noticeContainer).html(notice);

        $(noticeContainer).css('margin-bottom', '25px');
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