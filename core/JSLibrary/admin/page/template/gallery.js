'use strict';

class Gallery {
  constructor(galleryElement) {
    this.galleryElement = galleryElement;
  }

  init() {
    let galleryItems = this.galleryElement.querySelectorAll('.gallery__item');
    let controllers = this.galleryElement.querySelectorAll('.gallery__controller');
    for (let controller of controllers) {
      controller.addEventListener('click', (event) => {
        let firstItem = galleryItems[0], firstItemCurrentStyle = firstItem.currentStyle || window.getComputedStyle(firstItem);
        let firstItemStyleMarginLeft = parseInt(firstItemCurrentStyle.marginLeft);
        if (controller.classList.contains('gallery__controller_left')) {
          if (firstItemStyleMarginLeft < 0 && firstItemStyleMarginLeft < ((galleryItems.length - 1) * parseInt(firstItem.offsetWidth))) {
            firstItem.style.marginLeft = parseInt(firstItemCurrentStyle.marginLeft) + parseInt(firstItem.offsetWidth) + 'px';
          }
        }

        if (controller.classList.contains('gallery__controller_right')) {
          if ((firstItemStyleMarginLeft * -1) < ((galleryItems.length - 1) * parseInt(firstItem.offsetWidth))) {
            firstItem.style.marginLeft = parseInt(firstItemCurrentStyle.marginLeft) - parseInt(firstItem.offsetWidth) + 'px';
          }
        }
      });
    }
  }
}