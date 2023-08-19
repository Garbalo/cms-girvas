<form action="/handler/admin/settings" class="form form_settings">
  <table class="table table_settings">
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">Ограничение доступа к административной панели по IP-адресу</div>
        <div class="cell__description">
          <div class="page__phar-block">Ограничение доступа к административной панели по IP-адресу позволит давать доступ к административной панели исключительно по IP-адресам.</div>
          <div class="page__phar-block">В случае, если группы пользователя имеется доступ к административной панели, но включен данный фильтр, то в случае отсутствия IP-адреса пользователя в данном списке - ему доступ будет закрыт.</div>
        </div>
      </td>
      <td class="table__cell cell">
        <div class="page__phar-block">
          <div class="form__checkbox-container checkbox-container">
            <input type="hidden" name="setting_{SETTINGS_NAME}_allowed_admin_ip_status" id="I1474308110" value="{SETTING_ALLOWED_IP_ADMIN_STATUS_VALUE}">
            <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308800" name="setting_{SETTINGS_NAME}_allowed_admin_ip_status" type="checkbox" {SETTING_ALLOWED_IP_ADMIN_CHECKED_VALUE} data-logic-block="I1474308810" data-status-block="I1474308110">
            <label class="checkbox-container__label form__label" for="I1474308800"></label>
          </div>
        </div>
        <div class="page__phar-block">
          <textarea class="form__textarea" name="setting_{SETTINGS_NAME}_allowed_admin_ip" id="I1474308810" cols="30" rows="10" placeholder="127.0.0.1, 93.190.204.73">{SETTING_ALLOWED_IP_ADMIN_VALUE}</textarea>
        </div>
      </td>
    </tr>
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">Фильтр почтовых адресов при регистрации</div>
        <div class="cell__description">
          <div class="page__phar-block">Фильтр почтовых адресов поможет отсеять нежелательные регистрации со стороны различных почтовых адресов. Система не будет пропускать к регистрации тех пользователей, которые пытаются создать аккаунт через почтовые сервисы, которые НЕ внесены в данный список.</div>
        </div>
      </td>
      <td class="table__cell cell">
        <div class="page__phar-block">
          <div class="form__checkbox-container checkbox-container">
            <input type="hidden" name="setting_{SETTINGS_NAME}_allowed_emails_status" id="I1474308111" value="{SETTING_ALLOWED_EMAILS_STATUS_VALUE}">
            <input class="checkbox-container__input form__input form__input_checkbox" id="I1474308801" name="setting_{SETTINGS_NAME}_allowed_emails_status" type="checkbox" {SETTING_ALLOWED_EMAILS_CHECKED_VALUE} data-logic-block="I1474308811" data-status-block="I1474308111">
            <label class="checkbox-container__label form__label" for="I1474308801"></label>
          </div>
        </div>
        <div class="page__phar-block">
          <textarea class="form__textarea" name="setting_{SETTINGS_NAME}_allowed_emails" id="I1474308811" cols="30" rows="10" placeholder="yandex.ru, rambler.ru">{SETTING_ALLOWED_EMAILS_VALUE}</textarea>
        </div>
      </td>
    </tr>
  </table>
  <input type="submit" class="form__input form__input_submit" value="{LANG:BUTTON_SAVE_LABEL}">
</form>