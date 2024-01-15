'use strict';

import {Interactive} from "../interactive.class.js";

export class InstallationMaster {
  constructor(stepsCount) {
    this.setStepIndex(0);
    this.setStepsCount(stepsCount);
    this.buttons = {};
    
    let installationPages = document.querySelectorAll('[data-page-index]');
    installationPages.forEach((element, elementIndex) => {
      element.style.display = (elementIndex == 0) ? 'block' : 'none';
    });
  }

  generateRequestAPI() {

  }

  buildPanel() {
    let buttonsPanel = document.querySelector('[role="installation-buttons-panel"]');
    buttonsPanel.innerHTML = '';

    for (let buttonName in this.buttons) {
      delete this.buttons[buttonName];
    }

    if (this.getStepIndex() > 0) {
      this.buttons.prevStepIndex = new Interactive('button');
      this.buttons.prevStepIndex.target.setLabel('Назад');
      this.buttons.prevStepIndex.target.setCallback((event) => {
        event.preventDefault();
        this.prevStepIndex();

        this.buildPanel();
      });

      this.buttons.prevStepIndex.assembly();
    }
    
    if (this.getStepIndex() == 0) {
      this.buttons.nextStepIndex = new Interactive('button');
      this.buttons.nextStepIndex.target.setLabel('Принять и продолжить');
      this.buttons.nextStepIndex.target.setCallback((event) => {
        event.preventDefault();
        this.nextStepIndex();

        fetch('/handler/install?stepIndex=1&mode=install', {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-table-systems"]');

          if (!tableSystemsElement) {
            let dynamicDiv = document.createElement('div');
            dynamicDiv.setAttribute('role', 'cms-table-systems');
            dynamicDiv.innerHTML = resultHTML;

            let installationPages = document.querySelectorAll('[data-page-index]');
            installationPages[this.getStepIndex()].appendChild(dynamicDiv);
          }
        });

        this.buildPanel();
      });
      this.buttons.nextStepIndex.assembly();
    }

    if (this.getStepIndex() == 1) {
      this.buttons.updateData = new Interactive('button');
      this.buttons.updateData.target.setLabel('Обновить данные');
      this.buttons.updateData.target.setCallback((event) => {
        event.preventDefault();

        fetch('/handler/install?stepIndex=1&mode=install', {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-table-systems"]');

          if (tableSystemsElement) {
            tableSystemsElement.remove();
          }
          
          let dynamicDiv = document.createElement('div');
          dynamicDiv.setAttribute('role', 'cms-table-systems');
          dynamicDiv.innerHTML = resultHTML;

          let installationPages = document.querySelectorAll('[data-page-index]');
          installationPages[this.getStepIndex()].appendChild(dynamicDiv);
        });
      });

      this.buttons.updateData.assembly();
    }

    if (this.getStepIndex() == 2) {
      this.buttons.updateData = new Interactive('button');
      this.buttons.updateData.target.setLabel('Обновить данные');
      this.buttons.updateData.target.setCallback((event) => {
        event.preventDefault();

        fetch('/handler/install?stepIndex=2&mode=install', {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-table-directories-exists"]');

          if (tableSystemsElement) {
            tableSystemsElement.remove();
          }
          
          let dynamicDiv = document.createElement('div');
          dynamicDiv.setAttribute('role', 'cms-table-directories-exists');
          dynamicDiv.innerHTML = resultHTML;

          let installationPages = document.querySelectorAll('[data-page-index]');
          installationPages[this.getStepIndex()].appendChild(dynamicDiv);
        });
      });

      this.buttons.updateData.assembly();
    }

    if (this.getStepIndex() == 3) {
      this.buttons.updateData = new Interactive('button');
      this.buttons.updateData.target.setLabel('Обновить данные');
      this.buttons.updateData.target.setCallback((event) => {
        event.preventDefault();

        fetch('/handler/install?stepIndex=3&mode=install', {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-table-directories-perms"]');

          if (tableSystemsElement) {
            tableSystemsElement.remove();
          }
          
          let dynamicDiv = document.createElement('div');
          dynamicDiv.setAttribute('role', 'cms-table-directories-perms');
          dynamicDiv.innerHTML = resultHTML;

          let installationPages = document.querySelectorAll('[data-page-index]');
          installationPages[this.getStepIndex()].appendChild(dynamicDiv);
        });
      });

      this.buttons.updateData.assembly();
    }

    if (this.getStepIndex() == 4) {
      this.buttons.updateData = new Interactive('button');
      this.buttons.updateData.target.setLabel('Обновить данные');
      this.buttons.updateData.target.setCallback((event) => {
        event.preventDefault();

        fetch('/handler/install?stepIndex=4&mode=install', {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-table-dms-exists"]');

          if (tableSystemsElement) {
            tableSystemsElement.remove();
          }
          
          let dynamicDiv = document.createElement('div');
          dynamicDiv.setAttribute('role', 'cms-table-dms-exists');
          dynamicDiv.innerHTML = resultHTML;

          let installationPages = document.querySelectorAll('[data-page-index]');
          installationPages[this.getStepIndex()].appendChild(dynamicDiv);
        });
      });

      this.buttons.updateData.assembly();
    }

    if (this.getStepIndex() == 5) {
      this.buttons.updateData = new Interactive('button');
      this.buttons.updateData.target.setLabel('Создать конфигурации');
      this.buttons.updateData.target.setCallback((event) => {
        event.preventDefault();

        let formTarget = document.querySelector('[role="form-database"]');
        let formData = new FormData(formTarget);

        fetch('/handler/install?stepIndex=5&mode=install&' + new URLSearchParams(formData).toString(), {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-dms-connect-test"]');

          if (tableSystemsElement) {
            tableSystemsElement.remove();
          }
          
          let dynamicDiv = document.createElement('div');
          dynamicDiv.setAttribute('role', 'cms-dms-connect-test');
          dynamicDiv.innerHTML = resultHTML;

          let installationPages = document.querySelectorAll('[data-page-index]');
          installationPages[this.getStepIndex()].appendChild(dynamicDiv);
        });
      });

      this.buttons.updateData.assembly();
    }

    if (this.getStepIndex() == 6) {


      this.buttons.updateData = new Interactive('button');
      this.buttons.updateData.target.setLabel('Сгенерировать таблицы');
      this.buttons.updateData.target.setCallback((event) => {
        event.preventDefault();

        fetch('/handler/install?stepIndex=6&mode=install', {method: 'GET'}).then((response) => {
          return response.json();
        }).then((data) => {
          let resultHTML = data.outputData.html;

          let tableSystemsElement = document.querySelector('[role="cms-dms-tables-generate"]');

          if (tableSystemsElement) {
            tableSystemsElement.remove();
          }
          
          let dynamicDiv = document.createElement('div');
          dynamicDiv.setAttribute('role', 'cms-dms-tables-generate');
          dynamicDiv.innerHTML = resultHTML;

          let installationPages = document.querySelectorAll('[data-page-index]');
          installationPages[this.getStepIndex()].appendChild(dynamicDiv);
        });
      });

      this.buttons.updateData.assembly();
    }

    if (this.getStepIndex() < this.getStepsCount() - 1) {
      if (this.getStepIndex() > 0) {
        this.buttons.nextStepIndex = new Interactive('button');
        this.buttons.nextStepIndex.target.setLabel('Далее');
        this.buttons.nextStepIndex.target.setCallback((event) => {
          event.preventDefault();
          this.nextStepIndex();

          if (this.getStepIndex() == 2) {
            fetch('/handler/install?stepIndex=2&mode=install', {method: 'GET'}).then((response) => {
              return response.json();
            }).then((data) => {
              let resultHTML = data.outputData.html;
    
              let tableSystemsElement = document.querySelector('[role="cms-table-directories-exists"]');
    
              if (tableSystemsElement) {
                tableSystemsElement.remove();
              }
              
              let dynamicDiv = document.createElement('div');
              dynamicDiv.setAttribute('role', 'cms-table-directories-exists');
              dynamicDiv.innerHTML = resultHTML;
    
              let installationPages = document.querySelectorAll('[data-page-index]');
              installationPages[this.getStepIndex()].appendChild(dynamicDiv);
            });
          }

          if (this.getStepIndex() == 3) {
            fetch('/handler/install?stepIndex=3&mode=install', {method: 'GET'}).then((response) => {
              return response.json();
            }).then((data) => {
              let resultHTML = data.outputData.html;
    
              let tableSystemsElement = document.querySelector('[role="cms-table-directories-perms"]');
    
              if (tableSystemsElement) {
                tableSystemsElement.remove();
              }
              
              let dynamicDiv = document.createElement('div');
              dynamicDiv.setAttribute('role', 'cms-table-directories-perms');
              dynamicDiv.innerHTML = resultHTML;
    
              let installationPages = document.querySelectorAll('[data-page-index]');
              installationPages[this.getStepIndex()].appendChild(dynamicDiv);
            });
          }

          if (this.getStepIndex() == 4) {
            fetch('/handler/install?stepIndex=4&mode=install', {method: 'GET'}).then((response) => {
              return response.json();
            }).then((data) => {
              let resultHTML = data.outputData.html;
    
              let tableSystemsElement = document.querySelector('[role="cms-table-dms-exists"]');
    
              if (tableSystemsElement) {
                tableSystemsElement.remove();
              }
              
              let dynamicDiv = document.createElement('div');
              dynamicDiv.setAttribute('role', 'cms-table-dms-exists');
              dynamicDiv.innerHTML = resultHTML;
    
              let installationPages = document.querySelectorAll('[data-page-index]');
              installationPages[this.getStepIndex()].appendChild(dynamicDiv);
            });
          }

          this.buildPanel();
        });

        this.buttons.nextStepIndex.assembly();
      }
    }

    for (let buttonName in this.buttons) {
      buttonsPanel.appendChild(this.buttons[buttonName].target.element);
    }
  }

  setStepIndex(index) {
    this.stepIndex = index;
  }

  getStepIndex() {
    return this.stepIndex;
  }

  setStepsCount(count) {
    this.stepsCount = count;
  }

  getStepsCount() {
    return this.stepsCount;
  }

  getStepTitle() {
    switch (this.getStepIndex()) {
      case 0: return 'Знакомство';
      case 1: return 'Проверка совместимости';
      case 2: return 'Проверка целостности';
      case 3: return 'Проверка прав доступа';
      case 4: return 'Проверка драйверов PDO';
      case 5: return 'Генерация локальных конфигураций';
      case 6: return 'Выбор СУБД и генерация таблиц БД';
      case 7: return 'Создание администратора';
      case 8: return 'Настройка CMS';
      case 9: return 'Завершение';
      default: return '¯\_(ツ)_/¯';
    }
  }

  nextStepIndex() {
    let stepIndex = this.getStepIndex();
    let stepsCount = this.getStepsCount();

    if (stepIndex < stepsCount - 1) {
      this.setStepIndex(stepIndex + 1);
    }

    let installationPages = document.querySelectorAll('[data-page-index]');
    installationPages.forEach((element, elementIndex) => {
      element.style.display = (elementIndex == this.getStepIndex()) ? 'block' : 'none';
    });

    let stepNumberElement = document.querySelector('.title-container__step-number');
    stepNumberElement.innerHTML = this.getStepIndex() + 1;

    let stepTitleElement = document.querySelector('.main__title');
    stepTitleElement.innerHTML = this.getStepTitle();
  }

  prevStepIndex() {
    let stepIndex = this.getStepIndex();
    
    if (stepIndex > 0) {
      this.setStepIndex(stepIndex - 1);
    }

    let installationPages = document.querySelectorAll('[data-page-index]');
    installationPages.forEach((element, elementIndex) => {
      element.style.display = (elementIndex == this.getStepIndex()) ? 'block' : 'none';
    });

    let stepNumberElement = document.querySelector('.title-container__step-number');
    stepNumberElement.innerHTML = this.getStepIndex() + 1;

    let stepTitleElement = document.querySelector('.main__title');
    stepTitleElement.innerHTML = this.getStepTitle();
  }
}