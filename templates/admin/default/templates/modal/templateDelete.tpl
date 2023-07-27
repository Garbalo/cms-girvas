<div class="modal__header">Удаление шаблона</div>
<div class="modal__content">Вы действительно хотите удалить шаблон "{TEMPLATE_NAME}"?</div>
<div class="modal__footer">
  <form class="form" action="/handler/template">
    <input type="hidden" name="template_name" value="{TEMPLATE_NAME}">
    <input type="hidden" name="template_event_delete" value="true">
    <input class="form__input form__input_submit" name="template_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>