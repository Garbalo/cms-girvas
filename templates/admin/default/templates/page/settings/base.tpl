<form action="/handler/admin/settings" class="form form_settings">
  <table class="table table_settings">
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">{LANG:SETTINGS_PAGE_SETTING_BASE_TITLE_TITLE}</div>
        <div class="cell__description">{LANG:SETTINGS_PAGE_SETTING_BASE_TITLE_DESCRIPTION}</div>
      </td>
      <td class="table__cell cell">
        <input name="setting_{SETTINGS_NAME}_title" type="text" class="form__input form__input_text" placeholder="{LANG:SETTINGS_PAGE_SETTING_BASE_TITLE_PLACEHOLDER}">
      </td>
    </tr>
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">{LANG:SETTINGS_PAGE_SETTING_BASE_TIMEZONE_TITLE}</div>
        <div class="cell__description">{LANG:SETTINGS_PAGE_SETTING_BASE_TIMEZONE_DESCRIPTION}</div>
      </td>
      <td id="TC6474389679" class="table__cell cell"></td>
    </tr>
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">{LANG:SETTINGS_PAGE_SETTING_BASE_LOCALE_SITE_TITLE}</div>
        <div class="cell__description">{LANG:SETTINGS_PAGE_SETTING_BASE_LOCALE_SITE_DESCRIPTION}</div>
      </td>
      <td id="TC6474389680" class="table__cell cell"></td>
    </tr>
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">{LANG:SETTINGS_PAGE_SETTING_BASE_LOCALE_ADMIN_TITLE}</div>
        <div class="cell__description">{LANG:SETTINGS_PAGE_SETTING_BASE_LOCALE_ADMIN_DESCRIPTION}</div>
      </td>
      <td id="TC6474389681" class="table__cell cell"></td>
    </tr>
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">{LANG:SETTINGS_PAGE_SETTING_BASE_ENGINEERING_WORK_TITLE}</div>
        <div class="cell__description">{LANG:SETTINGS_PAGE_SETTING_BASE_ENGINEERING_WORK_DESCRIPTION}</div>
      </td>
      <td id="TC6474389682" class="table__cell cell">
        <textarea class="form__textarea" name="setting_{SETTINGS_NAME}_engineering_works_text" id="" cols="30" rows="10" placeholder="{LANG:SETTINGS_PAGE_SETTING_BASE_ENGINEERING_WORK_PLACEHOLDER}"></textarea>
      </td>
    </tr>
  </table>
  <input type="submit" class="form__input form__input_submit" value="{LANG:BUTTON_SAVE_LABEL}">
</form>