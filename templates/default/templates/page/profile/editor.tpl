<article class="page__article article">
  <div class="page__article-header" style="background-image: url('{SITE_TEMPLATE_URL}/images/entry/default_1024.png');">
    <div class="page__buttons-panel" role="profilePanelButtons"></div>
    <h2 class="article__title">{USER_LOGIN}</h2>
  </div>
  <div class="article__content">
    <div class="profile profile_edit">
      <div class="profile__avatar-container">
        <div class="profile__avatar" style="background-image: url('{USER_AVATAR_URL}');" role="profile-avatar"></div>
      </div>
      <div class="profile__information-container">
        <form id="SYSTEM_F0648538312" class="profile__form form form_profile" method="PATCH" action="/handler/user">
          <input type="hidden" name="user_id" value="{USER_ID}">
          <table class="table profile__information-table">
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_LOGIN_TITLE}</th>
              <td class="table__cell"><input name="user_login" type="text" class="form__input" value="{USER_LOGIN}" placeholder="User3425" required></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_EMAIL_TITLE}</th>
              <td class="table__cell"><input name="user_email" type="email" class="form__input" value="{USER_EMAIL}" placeholder="user3425@mail.com" required></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_NAME_TITLE}</th>
              <td class="table__cell"><input name="user_name" type="text" class="form__input" value="{USER_NAME}" placeholder="Иван"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_SURNAME_TITLE}</th>
              <td class="table__cell"><input name="user_surname" type="text" class="form__input" value="{USER_SURNAME}" placeholder="Иванов"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_PATRONYMIC_TITLE}</th>
              <td class="table__cell"><input name="user_patronymic" type="text" class="form__input" value="{USER_PATRONYMIC}" placeholder="Иванович"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_BIRTHDATE_TITLE}</th>
              <td class="table__cell"><input name="user_birthdate" type="date" class="form__input" value="{USER_BIRTHDATE}" placeholder="19.01.1997"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header" colspan="2">{LANG:PAGE_PROFILE_SECURITY_GROUP_TITLE}</th>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_PASSWORD_OLD_TITLE}</th>
              <td class="table__cell"><input name="user_password_old" type="password" class="form__input" value="" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" role="profileFormInputUserPasswordOld"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_PASSWORD_TITLE}</th>
              <td class="table__cell"><input name="user_password" type="password" class="form__input" value="" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" role="profileFormInputUserPassword"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">{LANG:PAGE_PROFILE_USER_PASSWORD_REPEAT_TITLE}</th>
              <td class="table__cell"><input name="user_password_repeat" type="password" class="form__input" value="" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;" role="profileFormInputUserPasswordRepeat"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header" colspan="2">{LANG:PAGE_PROFILE_ADDITIONAL_FIELDS_GROUP_TITLE}</th>
            </tr>
            {PROFILE_ADDITIONAL_FIELDS}
          </table>
          <div class="profile__form-panel">
            <button class="form__button form__input_submit">{LANG:DEFAULT_TEXT_SAVE}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</article>
<aside class="sidebar" role="siteSidebarRight">
  <div class="sidebar__block block">
    <h2 class="block__title title">Наименование блока</h2>
    <div class="block__content content">
      <p>
        Но&nbsp;интерактивные прототипы могут быть указаны как претенденты на&nbsp;роль ключевых
        факторов! Учитывая ключевые сценарии поведения, дальнейшее развитие различных форм деятельности
        создаёт предпосылки для направлений прогрессивного развития.
      </p>
    </div>
  </div>
</aside>