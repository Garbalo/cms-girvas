<div class="modal__header">Удаление файла</div>
<div class="modal__content">Вы действительно хотите удалить файл "{MEDIA_FILE_FULLNAME}"?</div>
<div class="modal__footer">
  <form class="form" action="/handler/media">
    <input type="hidden" name="media_file_fullname" value="{MEDIA_FILE_FULLNAME}">
    <input type="hidden" name="media_event_delete" value="true">
    <input class="form__input form__input_submit" name="media_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>