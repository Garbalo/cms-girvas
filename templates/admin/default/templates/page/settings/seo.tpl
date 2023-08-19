<form action="/handler/admin/settings" class="form form_settings">
  <table class="table table_settings">
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">Описание сайта</div>
        <div class="cell__description">
          <p>Описание сайта должно точно отражать суть его содержимого, а также включать в себя ключевые слова.</p>
        </div>
      </td>
      <td class="table__cell cell">
        <div class="page__phar-block">
          <textarea class="form__textarea" name="setting_{SETTINGS_NAME}_site_description" id="" cols="30" rows="10" placeholder="Описание сайта">{SETTING_SITE_DESCRIPTION_VALUE}</textarea>
        </div>
      </td>
    </tr>
    <tr class="table__row">
      <td class="table__cell cell">
        <div class="cell__title">Ключевые слова</div>
        <div class="cell__description">
          <div class="page__phar-block">Ключевые слова должны помогать находить сайт в поисковых системах. Для подбора ключевых слов рекомендуем использовать такие сервисы &laquo;<a href="https://wordstat.yandex.ru/" target="_blank">Yandex&nbsp;Wordstat</a>&raquo; и &laquo;<a href="https://trends.google.com/" target="_blank">Google&nbsp;Trends</a>&raquo;.</div>
        </div>
      </td>
      <td class="table__cell cell">
        <div class="page__phar-block">
          <textarea class="form__textarea" name="setting_{SETTINGS_NAME}_site_keywords" id="" cols="30" rows="10" placeholder="Ключевое слово, ключевое слово, ключевое слово">{SETTING_SITE_KEYWORDS_VALUE}</textarea>
        </div>
      </td>
    </tr>
  </table>
  <input type="submit" class="form__input form__input_submit" value="{LANG:BUTTON_SAVE_LABEL}">
</form>