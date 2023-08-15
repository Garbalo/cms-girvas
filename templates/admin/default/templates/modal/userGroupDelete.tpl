<div class="modal__header">Удаление группы пользователей</div>
<div class="modal__content">Вы действительно хотите удалить группу пользователей?</div>
<div class="modal__footer">
  <form class="form" action="/handler/userGroup">
    <input type="hidden" name="user_group_id" value="{USER_GROUP_ID}">
    <input type="hidden" name="user_group_event_delete" value="true">
    <input class="form__input form__input_submit" name="user_group_event_delete" formmethod="DELETE" type="submit" value="Удалить">
    <button class="form__button" data-modal-closer>Отмена</button>
  </form>
</div>