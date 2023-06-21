<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <h1 class="page__title">Создание пользователя</h1>
  <div class="page__content">
    <form class="form page__user-editor-form user-editor-form" action="/handler/user">
      <input name="user_id" type="hidden" value="{USER_ID}">
      <input name="user_login" type="text" class="form__input user-editor-form__input" value="{USER_LOGIN}" placeholder="user2311">
      <input name="user_email" type="email" class="form__input user-editor-form__input" value="{USER_EMAIL}" placeholder="user@mail.com">
      <input name="user_password" type="password" class="form__input user-editor-form__input" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
      <input name="user_password_repeat" type="password" class="form__input user-editor-form__input" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;">
      <div class="user-editor-form__panel">
        <button class="form__button form__button_red user-editor-form__panel-button" data-modal-call="admin-user-delete">Удалить</button>
        <input type="submit" class="form__input form__input_submit form__input_submit_blue user-editor-form__panel-submit" name="user_event_save" formmethod="{USER_FORM_METHOD}" value="Сохранить">
      </div>
    </form>
  </div>
</article>
<aside class="main__page-aside page-aside">
  <article class="page-aside__block">
    <h2 class="page-aside__block-title">Популярные записи</h2>
    <div class="page-aside__block-content">
      Тут колонка записей
    </div>
  </article>
</aside>