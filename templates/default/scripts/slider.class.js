'use strict';

class Slider {
  /**
   * Конструктор класса
   * 
   * @param {HTMLElement} element 
   */
  constructor(element) {
    this.element = element;
    this.sliderInterval = null;
    this.sliderIntervalTime = 10000;

    this.sliderListElement = null;
    this.sliderControllersListElement = null;
    this.sliderIndicatorsListElement = null;

    this.init();
  }

  /**
   * Инициализация слайдера
   */
  init() {
    this.sliderInterval = setInterval(() => {
      this.controllNext();
    }, this.sliderIntervalTime);
  }

  /**
   * Переключение на следующий слайд
   */
  controllNext() {
    /** @type {NodeList} */
    let slidesElements = this.getSlidesElements();
    /** @type {Object} */
    let slideFirstElementStyle = slidesElements[0].currentStyle || window.getComputedStyle(slidesElements[0]);
    /** @var {Number} */
    let slideFirstElementMarginLeft = parseInt(slideFirstElementStyle.marginLeft);
    
    if ((slideFirstElementMarginLeft * -1) < ((slidesElements.length - 1) * parseInt(slidesElements[0].offsetWidth))) {
      slidesElements[0].style.marginLeft = parseInt(slideFirstElementStyle.marginLeft) - parseInt(slidesElements[0].offsetWidth) + 'px';
    } else {
      slidesElements[0].style.marginLeft = 0;
    }
  }

  /**
   * Получить элемент списка слайдов
   * 
   * @returns {Node}
   */
  getListElement() {
    return this.element.querySelector('[data-slider-list-element]');
  }

  /**
   * Получить массив элементов слайдов
   * 
   * @returns {NodeList}
   */
  getSlidesElements() {
    let element = this.getListElement();
    return element.querySelectorAll('[data-slider-item-element]');
  }
}