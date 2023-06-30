<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <h1 class="page__title">Создание статической страницы</h1>
  <div class="page__content">
    <form class="form page__page-static-editor-form page-static-editor-form" action="/handler/page-static">
      <input name="page-static_id" type="hidden" value="{PAGE_STATIC_ID}">
      <input name="page-static_title_rus" type="text" class="form__input page-static-editor-form__input" value="{PAGE_STATIC_TITLE}">
      <input name="page-static_name" type="text" class="form__input page-static-editor-form__input" value="{PAGE_STATIC_NAME}">
      <textarea name="page-static_description_rus" class="form__textarea page-static-editor-form__textarea">{PAGE_STATIC_DESCRIPTION}</textarea>
      {PAGE_STATIC_EDITOR}
      <div class="page-static-editor-form__panel">
        <button class="form__button form__button_red page-static-editor-form__panel-button" data-modal-call="admin-page-static-delete">Удалить</button>
        <input type="submit" class="form__input form__input_submit page-static-editor-form__panel-submit" name="page-static_event_unpublish" formmethod="PATCH" value="Снять с публикации">
        <input type="submit" class="form__input form__input_submit form__input_submit_blue page-static-editor-form__panel-submit" name="page-static_event_save" formmethod="{PAGE_STATIC_FORM_METHOD}" value="Сохранить">
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