<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  {PAGE_NAVIGATION}
  <div class="page__title-container">
    <h1 class="page__title">{LANG:PAGE_USER_TITLE}</h1>
  </div>
  <div class="page__content">
    <form class="form form_user" action="/handler/user">
      <input name="user_id" type="hidden" value="{USER_ID}">
      <table id="SYSTEM_F0648538231" class="table table_user">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_USER_USER_LOGIN_TITLE}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_USER_USER_LOGIN_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="user_login" type="text" class="form__input user-editor-form__input" value="{USER_LOGIN}" placeholder="user2311"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_USER_USER_EMAIL_TITLE}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_USER_USER_EMAIL_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="user_email" type="email" class="form__input user-editor-form__input" value="{USER_EMAIL}" placeholder="user@mail.ru"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_USER_USER_PASSWORD_TITLE}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_USER_USER_PASSWORD_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="user_password" type="password" class="form__input user-editor-form__input" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_USER_USER_PASSWORD_REPEAT_TITLE}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_USER_USER_PASSWORD_REPEAT_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="user_password_repeat" type="password" class="form__input user-editor-form__input" placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_USER_USER_GROUP_TITLE}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_USER_USER_GROUP_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div id="SYSTEM_E6372840180" class="page__phar-block"></div>
          </td>
        </tr>
        {USER_ADDITIONAL_FIELDS}
      </table>
      <div class="form__bottom-panel" id="SYSTEM_E3724126170"></div>
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