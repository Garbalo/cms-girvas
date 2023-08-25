<div class="modal__header">Удаление веб-канала</div>
<div class="modal__content">Вы действительно хотите удалить веб-канал?</div>
<div class="modal__footer">
  <form class="form" action="/handler/webChannel">
    <input type="hidden" name="web_channel_id" value="{WEB_CHANNEL_ID}">
    <input type="hidden" name="web_channel_event_delete" value="true">
    <input class="form__input form__input_submit" name="web_channel_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>