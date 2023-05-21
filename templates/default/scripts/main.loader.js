'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  /** @type {NodeList} */
  let slidersElements = document.body.querySelectorAll('[data-slider-element]');
  for (let sliderElement of slidersElements) {
    /** @type {Slider} */
    let slider = new Slider(sliderElement);
  }
});