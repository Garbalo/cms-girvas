<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  {PAGE_NAVIGATION}
  <div class="page__title-container">
    <h1 class="page__title">Создание статической страницы</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form id="SYSTEM_F0648538658" class="form form_page-static page-static-form" action="/handler/pageStatic">
      <input name="page_static_id" type="hidden" value="{PAGE_STATIC_ID}">
      <table class="table">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Техническое наименование</div>
            <div class="cell__description">
              <div class="page__phar-block">Это техническое наименование страницы, которое будет использоваться в URL.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="page_static_name" typde="text" class="form__input page-static-form__input" value="{PAGE_STATIC_NAME}" placeholder="my-first-page">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Заголовок</div>
            <div class="cell__description">
              <div class="page__phar-block">Заголовок страницы должен отображать суть ее содержимого.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="page_static_title_rus" type="text" class="form__input page-static-form__input" role="pageStaticTitle" value="{PAGE_STATIC_TITLE}" placeholder="Заголовок страницы">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Описание</div>
            <div class="cell__description">
              <div class="page__phar-block">Описание страницы должно отображать суть ее содержимого. При составлении описания старайтесь описать содержимое кратко и точно, используя ключевые слова, чтобы это положительно отразилось на поисковой оптимизации.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="page_static_description_rus" class="form__textarea page-static-form__textarea" role="pageStaticDescription" placeholder="Описание записи">{PAGE_STATIC_DESCRIPTION}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Ключевые слова и фразы</div>
            <div class="cell__description">
              <div class="page__phar-block">Ключевые слова и фразы могут помогать в поиске контента в сети Интернет поисковым роботам. Рекомендуем включать их в описание, заголовок и содержимое страницы.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="page_static_keywords_rus" class="form__textarea page-static-form__textarea" role="pageStaticKeywords" placeholder="Вулкан Гирвас, Республика Карелия, Ловля рыбы в поселке Надвоицы">{PAGE_STATIC_KEYWORDS}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell" colspan="2">
            <div class="page__phar-block">
              {PAGE_STATIC_EDITOR}
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
  <article class="page-aside__block">
    <h2 class="page-aside__block-title">Популярные записи</h2>
    <div class="page-aside__block-content">
      Тут колонка записей
    </div>
  </article>
</aside>