<div class="modal__header">Удаление модуля</div>
<div class="modal__content">Вы действительно хотите удалить модуль "{MODULE_NAME}"?</div>
<div class="modal__footer">
  <form class="form" action="/handler/module">
    <input type="hidden" name="module_name" value="{MODULE_NAME}">
    <input type="hidden" name="module_event_delete" value="true">
    <input class="form__input form__input_submit" name="module_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>