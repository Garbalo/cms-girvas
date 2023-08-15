'use strict';

document.addEventListener('DOMContentLoaded', (event) => {
  let mediaListElement = document.querySelector('.media-list');
  let mediaUploaderInput = document.querySelector('.form__input_file');
  let mediaUploaderButton = document.querySelector('.media-list__button-uploader');
  mediaUploaderButton.addEventListener('click', (event) => {
    event.preventDefault();
    mediaUploaderInput.click();
  });

  let mediaLoader = new MediaLoader(mediaUploaderInput, mediaListElement);
  mediaLoader.init();

  let mediaFilesListElements = document.querySelectorAll('.media-list__item');
  for (let mediaFileElement of mediaFilesListElements) {
    let mediaFile = mediaFileElement.getAttribute('data-media-file');
    let buttons = mediaFileElement.querySelectorAll('button[role]');
    for (let button of buttons) {
      button.addEventListener('click', (event) => {
        if (button.getAttribute('role') == 'media-copy-url') {
          navigator.clipboard.writeText(`/uploads/media/${mediaFile}`);

          let notification = new PopupNotification('Относительная ссылка на файл скопирована.', document.body, true);
          notification.show();
        }
      });
    }
  }
});