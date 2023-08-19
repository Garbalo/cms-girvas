<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  <div class="page__title-container">
    <h1 class="page__title">Шаблон: {TEMPLATE_TITLE}</h1>
  </div>
  <div class="page__content">
    <div class="template">
      <div class="template__left-block">
        <div class="template__gallary-container">
          <div class="gallery__controllers-list">
            <button class="gallery__controller gallery__controller_left">
              <svg class="gallery__controller-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <polygon points="35,0 16,32 35,64 48,64 29.3,32 48,0 "/>
              </svg>
            </button>
            <button class="gallery__controller gallery__controller_right">
              <svg class="gallery__controller-icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                <polygon points="26,0 45,32 26,64 13,64 31.7,32 13,0 "/>
              </svg>
            </button>
          </div>
          {TEMPLATE_GALLARY_LIST}
        </div>
        <div class="template__metadata-container">
          <h2 class="template__metadata-label">Метаданные шаблона</h2>
          {TEMPLATE_METADATA_LIST}
        </div>
      </div>
      <div class="template__right-block">
        <div class="template__description-container">
          <h2 class="template__description-label">Описание шаблона</h2>
          <div class="template__description">{TEMPLATE_DESCRIPTION}</div>
        </div>
      </div>
    </div>
  </div>
</article>
<aside class="main__page-aside page-aside">
  <article class="page-aside__block">
    <h2 class="page-aside__block-title">Оценки пользователей</h2>
    <div class="page-aside__block-content">
      Тут оценки
    </div>
  </article>
</aside>