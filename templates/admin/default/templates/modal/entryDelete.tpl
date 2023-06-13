<div class="modal__header">Удаление записи</div>
<div class="modal__content">Вы действительно хотите удалить запись?</div>
<div class="modal__footer">
  <form class="form" action="/handler/entry">
    <input type="hidden" name="entry_id" value="{ENTRY_ID}">
    <input type="hidden" name="entry_event_delete" value="true">
    <input class="form__input form__input_submit" name="entry_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>