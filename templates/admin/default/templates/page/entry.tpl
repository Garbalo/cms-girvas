<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  {PAGE_NAVIGATION}
  <div class="page__title-container">
    <h1 class="page__title">Создание записи</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form id="SYSTEM_F0648538658" class="form form_entry entry-form" action="/handler/entry">
      <input name="entry_id" type="hidden" value="{ENTRY_ID}">
      <table class="table">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Техническое наименование</div>
            <div class="cell__description">
              <div class="page__phar-block">Это техническое наименование записи, которое будет использоваться в URL.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="entry_name" typde="text" class="form__input entry-form__input" value="{ENTRY_NAME}" placeholder="my-first-entry">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Заголовок</div>
            <div class="cell__description">
              <div class="page__phar-block">Заголовок записи должен отображать суть ее содержимого.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="entry_title_rus" type="text" class="form__input entry-form__input" role="entryTitle" value="{ENTRY_TITLE}" placeholder="Заголовок записи">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Описание</div>
            <div class="cell__description">
              <div class="page__phar-block">Описание записи должно отображать суть ее содержимого. При составлении описания старайтесь описать содержимое кратко и точно, используя ключевые слова, чтобы это положительно отразилось на поисковой оптимизации.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="entry_description_rus" class="form__textarea entry-form__textarea" role="entryDescription" placeholder="Описание записи">{ENTRY_DESCRIPTION}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Ключевые слова и фразы</div>
            <div class="cell__description">
              <div class="page__phar-block">Ключевые слова и фразы могут помогать в поиске контента в сети Интернет поисковым роботам. Рекомендуем включать их в описание, заголовок и содержимое записи.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="entry_keywords_rus" class="form__textarea entry-form__textarea" role="entryKeywords" placeholder="Вулкан Гирвас, Республика Карелия, Ловля рыбы в поселке Надвоицы">{ENTRY_KEYWORDS}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Категория</div>
            <div class="cell__description">
              <div class="page__phar-block">Выберите соответствующую категорию, или оставьте поле пустым.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block" id="TC6474389611"></div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell" colspan="2">
            <div class="page__phar-block">
              {ENTRY_EDITOR}
            </div>
          </td>
        </tr>
      </table>
      <div class="form__bottom-panel" id="SYSTEM_E3724126170"></div>
    </form>
  </div>
</article>
<aside class="main__page-aside page-aside">
  <article class="page-aside__block" id="SYSTEM_E3754926184">
    <h2 class="page-aside__block-title">Обложка</h2>
    <div class="page-aside__block-content"></div>
  </article>
  <article class="page-aside__block block-comments">
    <h2 class="page-aside__block-title">Последние комментарии</h2>
    <div class="page-aside__block-content">
      {ENTRY_COMMENTS_LIST}
    </div>
  </article>
  <article class="page-aside__block">
    <h2 class="page-aside__block-title">Популярные записи</h2>
    <div class="page-aside__block-content">
      Тут колонка записей
    </div>
  </article>
</aside>