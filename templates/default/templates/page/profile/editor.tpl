<article class="page__article article">
  <div class="page__article-header" style="background-image: url('{SITE_TEMPLATE_URL}/images/entry/default_1024.png');">
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
              <th class="table__cell table__cell_header">Логин</th>
              <td class="table__cell"><input name="user_login" type="text" class="form__input" value="{USER_LOGIN}" placeholder="User3425"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">E-Mail</th>
              <td class="table__cell"><input name="user_email" type="email" class="form__input" value="{USER_EMAIL}" placeholder="user3425@mail.com"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header">Дата рождения</th>
              <td class="table__cell"><input name="user_birthdate" type="date" class="form__input" value="{USER_BIRTHDATE}" placeholder="19.01.1997"></td>
            </tr>
            <tr class="table__row">
              <th class="table__cell table__cell_header" colspan="2">Дополнительная информация</th>
            </tr>
            {PROFILE_ADDITIONAL_FIELDS}
          </table>
          <div class="profile__form-panel">
            <button class="form__button form__input_submit">Сохранить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</article>
<aside class="sidebar">
  <h2 class="sidebar__title title">Наименование блока</h2>
  <div class="sidebar__text-block">
    <p class="sidebar__text">
        Но&nbsp;интерактивные прототипы могут быть указаны как претенденты на&nbsp;роль ключевых
        факторов! Учитывая ключевые сценарии поведения, дальнейшее развитие различных форм деятельности
        создаёт предпосылки для направлений прогрессивного развития.
    </p>
  </div>
</aside>