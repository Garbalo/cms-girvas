'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let galleryElement = document.querySelector('.template__gallary-container');
  let gallery = new Gallery(galleryElement);
  gallery.init();
});