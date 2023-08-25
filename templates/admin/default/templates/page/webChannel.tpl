<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <div class="page__title-container">
    <h1 class="page__title">Создание веб-канала</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form class="form page__web-channel-editor-form web-channel-editor-form" action="/handler/webChannel">
      <input name="web_channel_id" type="hidden" value="{WEB_CHANNEL_ID}">
      <table class="table">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Техническое наименование канала</div>
            <div class="cell__description">
              <div class="page__phar-block">Это техническое наименование канала, которое будет использоваться в URL.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="web_channel_name" typde="text" class="form__input web-channel-editor-form__input" value="{WEB_CHANNEL_NAME}" placeholder="my-channel"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Заголовок канала</div>
            <div class="cell__description">
              <div class="page__phar-block">Укажите заголовок, который описывал бы суть содержимого веб-канала.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block"><input name="web_channel_title_rus" role="webChannelTitle" type="text" class="form__input form__input_text" placeholder="Мой канал" value="{WEB_CHANNEL_TITLE}"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Описание канала</div>
            <div class="cell__description">
              <div class="page__phar-block">Кратко опишите содержимое веб-канала.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea class="form__textarea" name="web_channel_description_rus" role="webChannelDescription" cols="30" rows="10" placeholder="Канал последних новостей">{WEB_CHANNEL_DESCRIPTION}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Тип канала</div>
            <div class="cell__description">
              <div class="page__phar-block">Выберите тип канала, на основе спецификации которого будет отображаться веб-канал.</div>
            </div>
          </td>
          <td id="TC6474387201" class="table__cell cell"></td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Категория контента</div>
            <div class="cell__description">
              <div class="page__phar-block">Выберите категорию контента, которая будет выгружаться через веб-канал.</div>
            </div>
          </td>
          <td id="TC6474387200" class="table__cell cell"></td>
        </tr>
      </table>
      <div class="web-channel-editor-form__panel">
        <button class="form__button form__button_red web-channel-editor-form__panel-button" data-modal-call="admin-web-channel-delete">Удалить</button>
        <input type="submit" class="form__input form__input_submit form__input_submit_blue web-channel-editor-form__panel-submit" name="web_channel_event_save" formmethod="{WEB_CHANNEL_FORM_METHOD}" value="Сохранить">
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