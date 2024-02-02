<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  {PAGE_NAVIGATION}
  <div class="page__title-container">
    <h1 class="page__title">Создание группы пользователей</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form class="form form_user-group" action="/handler/usersGroup">
      <input name="user_group_id" type="hidden" value="{USER_GROUP_ID}">
      <table class="table">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Заголовок</div>
            <div class="cell__description">
              <div class="page__phar-block">Заголовок группы пользователей. Он будет использоваться для отображения на сайте и в административной панели.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="user_group_title_rus" typde="text" class="form__input form__input_text" value="{USER_GROUP_TITLE}" placeholder="My Group" role="usersGroupTitle"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Техническое наименование</div>
            <div class="cell__description">
              <div class="page__phar-block">Это техническое наименование группы пользователей, оно необходимо для проведения некоторых внутренний манипуляций.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="user_group_name" typde="text" class="form__input form__input_text" value="{USER_GROUP_NAME}" placeholder="my-group" role="usersGroupName"></div>
          </td>
        </tr>
        <tr class="table__row">
          <th class="table__cell table__cell_header" colspan="2">Административные права</th>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Авторизация в административной панели</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308970" name="user_group_permissions[]" type="checkbox" value="admin_panel_auth" {USER_GROUP_PERMISSION_ADMIN_PANEL_AUTH_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308970"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Управление пользователями</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308972" name="user_group_permissions[]" type="checkbox" value="admin_users_management" {USER_GROUP_PERMISSION_ADMIN_USERS_MANAGEMENT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308972"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Управление группами пользователей</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308982" name="user_group_permissions[]" type="checkbox" value="admin_users_groups_management" {USER_GROUP_PERMISSION_ADMIN_USERS_GROUPS_MANAGEMENT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308982"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Управление шаблонами CMS</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308974" name="user_group_permissions[]" type="checkbox" value="admin_templates_management" {USER_GROUP_PERMISSION_ADMIN_TEMPLATES_MANAGEMENT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308974"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Управление настройками CMS</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308975" name="user_group_permissions[]" type="checkbox" value="admin_settings_management" {USER_GROUP_PERMISSION_ADMIN_SETTINGS_MANAGEMENT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308975"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Просмотр отчетов CMS</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308976" name="user_group_permissions[]" type="checkbox" value="admin_viewing_logs" {USER_GROUP_PERMISSION_ADMIN_VIEWING_LOGS_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308976"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <th class="table__cell table__cell_header" colspan="2">Права модерации</th>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Блокировка пользователей</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308971" name="user_group_permissions[]" type="checkbox" value="moder_users_ban" {USER_GROUP_PERMISSION_MODER_USERS_BAN_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308971"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Управление комментариями к записям</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308977" name="user_group_permissions[]" type="checkbox" value="moder_entries_comments_management" {USER_GROUP_PERMISSION_MODER_ENTRIES_COMMENTS_MANAGEMENT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308977"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Выдача предупреждений пользователям</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308978" name="user_group_permissions[]" type="checkbox" value="moder_users_warns" {USER_GROUP_PERMISSION_MODER_USERS_WARNS_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308978"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <th class="table__cell table__cell_header" colspan="2">Права редакции</th>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Редактирование записей</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308979" name="user_group_permissions[]" type="checkbox" value="editor_entries_edit" {USER_GROUP_PERMISSION_EDITOR_ENTRIES_EDIT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308979"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Редактирование категорий записей</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308980" name="user_group_permissions[]" type="checkbox" value="editor_entries_categories_edit" {USER_GROUP_PERMISSION_EDITOR_ENTRIES_CATEGORIES_EDIT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308980"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Редактирование статических страниц</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308981" name="user_group_permissions[]" type="checkbox" value="editor_pages_static_edit" {USER_GROUP_PERMISSION_EDITOR_PAGES_STATIC_EDIT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308981"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <th class="table__cell table__cell_header" colspan="2">Базовые права</th>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Изменение рейтинга у комментариев</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308983" name="user_group_permissions[]" type="checkbox" value="base_entry_comment_rate" {USER_GROUP_PERMISSION_BASE_ENTRY_COMMENT_RATE_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308983"></label>
            </div>
          </td>
        </tr>
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