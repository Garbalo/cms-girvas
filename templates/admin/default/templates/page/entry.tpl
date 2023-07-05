<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <h1 class="page__title">Создание записи</h1>
  <div class="page__content">
    <form class="form page__entry-editor-form entry-editor-form" action="/handler/entry">
      <input name="entry_id" type="hidden" value="{ENTRY_ID}">
      <input name="entry_title_rus" type="text" class="form__input entry-editor-form__input" value="{ENTRY_TITLE}">
      <input name="entry_name" type="text" class="form__input entry-editor-form__input" value="{ENTRY_NAME}">
      <textarea name="entry_description_rus" class="form__textarea entry-editor-form__textarea">{ENTRY_DESCRIPTION}</textarea>
      {ENTRY_EDITOR}
      <div class="entry-editor-form__panel">
        <button class="form__button form__button_red entry-editor-form__panel-button" data-modal-call="admin-entry-delete">Удалить</button>
        <input type="submit" class="form__input form__input_submit entry-editor-form__panel-submit" name="entry_event_unpublish" formmethod="PATCH" value="Снять с публикации">
        <input type="submit" class="form__input form__input_submit form__input_submit_blue entry-editor-form__panel-submit" name="entry_event_save" formmethod="{ENTRY_FORM_METHOD}" value="Сохранить">
      </div>
    </form>
  </div>
</article>
<aside class="main__page-aside page-aside">
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