<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  {PAGE_NAVIGATION}
  <div class="page__title-container">
    <h1 class="page__title">Редактирование категории записей</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form class="form form_entries-category page__entries-category-form entries-category-form" action="/handler/entry/category">
      <input name="entries_category_id" type="hidden" value="{ENTRIES_CATEGORY_ID}">
      <table class="table">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Техническое наименование</div>
            <div class="cell__description">
              <div class="page__phar-block">Это техническое наименование категории, которое будет использоваться в URL.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="entries_category_name" typde="text" class="form__input entries-category-form__input" value="{ENTRIES_CATEGORY_NAME}" placeholder="news">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Заголовок категории</div>
            <div class="cell__description">
              <div class="page__phar-block">Заголовок категории должен отображать суть ее содержимого.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="entries_category_title_rus" type="text" class="form__input entries-category-form__input" role="entriesCategoryTitle" value="{ENTRIES_CATEGORY_TITLE}" placeholder="Заголовок категории">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Описание категории</div>
            <div class="cell__description">
              <div class="page__phar-block">Описание категории должно отображать суть ее содержимого. При составлении категории старайтесь описать содержимое кратко и точно, используя ключевые слова, чтобы это положительно отразилось на поисковой оптимизации.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="entries_category_description_rus" class="form__textarea entries-category-form__textarea" role="entriesCategoryDescription" placeholder="Описание категории">{ENTRIES_CATEGORY_DESCRIPTION}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">Родительская категория</div>
            <div class="cell__description">
              <div class="page__phar-block">Если необходимо выстраивать иерархическую систему категорий, то можно выбрать родительскую категорию. Этот параметр необязателен.</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div id="TC6474389602" class="page__phar-block"></div>
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