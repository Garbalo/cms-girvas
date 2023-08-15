<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <div class="page__title-container">
    <h1 class="page__title">Создание группы пользователей</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form class="form page__user-group-editor-form user-group-editor-form" action="/handler/userGroup">
      <input name="user_group_id" type="hidden" value="{USER_GROUP_ID}">
      <input name="user_group_name" typde="text" class="form__input user-group-editor-form__input" value="{USER_GROUP_NAME}" placeholder="friend">
      <table class="table">
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
          <td class="table__cell">Редактирование пользователей</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308972" name="user_group_permissions[]" type="checkbox" value="admin_users_edit" {USER_GROUP_PERMISSION_ADMIN_USERS_EDIT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308972"></label>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell">Управление модулями CMS</td>
          <td class="table__cell">
            <div class="form__checkbox-container checkbox-container">
              <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308973" name="user_group_permissions[]" type="checkbox" value="admin_modules_management" {USER_GROUP_PERMISSION_ADMIN_MODULES_MANAGEMENT_VALUE}>
              <label class="checkbox-container__label form__label" for="I1474308973"></label>
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
      </table>
      <div class="user-group-editor-form__panel">
        <button class="form__button form__button_red user-group-editor-form__panel-button" data-modal-call="admin-user-group-delete">Удалить</button>
        <input type="submit" class="form__input form__input_submit form__input_submit_blue user-group-editor-form__panel-submit" name="user_group_event_save" formmethod="{USER_GROUP_FORM_METHOD}" value="Сохранить">
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