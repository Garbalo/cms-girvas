<li class="templates-list__item template">
  <div class="template__preview" style="background-image: url('{TEMPLATE_PREVIEW_URL}');">
    <button class="template__delete-button" role="template-delete" data-modal-call="admin-template-delete" data-modal-params="templateName={TEMPLATE_NAME}">
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
        <rect x="13.9" y="16.1" width="37.3" height="47.9"/>
        <path d="M41.4,10l1-7.3L24.7,0.3l-1,7.3L10.2,5.7l-1,7.3l44.6,6.2l1-7.3L41.4,10z M25.6,7.8l0.7-5l13.8,1.9l-0.7,5L25.6,7.8z"/>
      </svg>
    </button>
  </div>
  <div class="template__data">
    <h2 class="template__title">
      <a href="{TEMPLATE_PAGE_URL}">{TEMPLATE_TITLE}</a>
    </h2>
    <div class="template__description">{TEMPLATE_DESCRIPTION}</div>
  </div>
  <div class="template__metadata">
    <div class="template__created-timestamp">{TEMPLATE_CREATED_TIMESTAMP}</div>
    <div class="template__author">{TEMPLATE_AUTHOR}</div>
  </div>
</li>