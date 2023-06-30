<div class="modal__header">Удаление страницы</div>
<div class="modal__content">Вы действительно хотите удалить страницу?</div>
<div class="modal__footer">
  <form class="form" action="/handler/page-static">
    <input type="hidden" name="page-static_id" value="{PAGE_STATIC_ID}">
    <input type="hidden" name="page-static_event_delete" value="true">
    <input class="form__input form__input_submit" name="page-static_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>