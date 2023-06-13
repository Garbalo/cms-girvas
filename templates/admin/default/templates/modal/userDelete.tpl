<div class="modal__header">Удаление пользователя</div>
<div class="modal__content">Вы действительно хотите удалить пользователя?</div>
<div class="modal__footer">
  <form class="form" action="/handler/user">
    <input type="hidden" name="user_id" value="{USER_ID}">
    <input type="hidden" name="user_event_delete" value="true">
    <input class="form__input form__input_submit" name="user_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>