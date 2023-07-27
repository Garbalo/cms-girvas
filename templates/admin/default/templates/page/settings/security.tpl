<form action="/handler/admin/settings" class="form form_settings">
  <table class="table table_settings">
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">Фильтр почтовых адресов при регистрации</div>
        <div class="cell__description">Если необходимо разрешить регистрироваться на сайте только по почтовым адресам определенных доменов, то можно их здесь перечислить через запятую. Если регистрация разрешена всем, то поле можно оставить пустым.</div>
      </td>
      <td class="table__cell cell">
        <textarea class="form__textarea" name="setting_{SETTINGS_NAME}_allowed_emails" id="" cols="30" rows="10" placeholder="yandex.ru, rambler.ru"></textarea>
      </td>
    </tr>
  </table>
  <input type="submit" class="form__input form__input_submit" value="{LANG:BUTTON_SAVE_LABEL}">
</form>