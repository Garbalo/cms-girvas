<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <div class="page__title-container">
    <h1 class="page__title">Медиа-файлы</h1>
  </div>
  <div class="page__content">
    <ul class="media-list list-reset">
      <li class="media-list__item item">
        <form class="form" action="/handler">
          <input class="form__input form__input_file" name="media[]" type="file" multiple style="display: none;">
          <button class="form__button media-list__button-uploader">Загрузить</button>
        </form>
      </li>
      {MEDIA_LIST_ITEMS}
    </ul>
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