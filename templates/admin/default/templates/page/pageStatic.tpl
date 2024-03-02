<article class="main__page page page_{ADMIN_PANEL_PAGE_NAME}">
  {PAGE_NAVIGATION}
  <div class="page__title-container">
    <h1 class="page__title">{LANG:PAGE_STATIC_PAGE_TITLE}</h1>
    <div id="E8548530785" class="page__interactive-container"></div>
  </div>
  <div class="page__content">
    <form class="form form_page-static page-static-form" action="/handler/pageStatic">
      <input name="page_static_id" type="hidden" value="{PAGE_STATIC_ID}">
      <table class="table">
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_STATIC_PAGE_INPUT_TECH_NAME_LABEL}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_STATIC_PAGE_INPUT_TECH_NAME_DESCRIPTION}</div>
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
            <div class="cell__title">{LANG:PAGE_STATIC_PAGE_INPUT_TITLE_LABEL}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_STATIC_PAGE_INPUT_TITLE_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <input name="page_static_title_rus" type="text" class="form__input page-static-form__input" role="pageStaticTitle" value="{PAGE_STATIC_TITLE}" placeholder="{LANG:PAGE_STATIC_PAGE_INPUT_TITLE_LABEL}">
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_STATIC_PAGE_INPUT_DESCRIPTION_LABEL}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_STATIC_PAGE_INPUT_DESCRIPTION_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="page_static_description_rus" class="form__textarea page-static-form__textarea" role="pageStaticDescription" placeholder="{LANG:PAGE_STATIC_PAGE_INPUT_DESCRIPTION_LABEL}">{PAGE_STATIC_DESCRIPTION}</textarea>
            </div>
          </td>
        </tr>
        <tr class="table__row">
          <td class="table__cell cell">
            <div class="cell__title">{LANG:PAGE_STATIC_PAGE_INPUT_KEYWORDS_LABEL}</div>
            <div class="cell__description">
              <div class="page__phar-block">{LANG:PAGE_STATIC_PAGE_INPUT_KEYWORDS_DESCRIPTION}</div>
            </div>
          </td>
          <td class="table__cell cell">
            <div class="page__phar-block">
              <textarea name="page_static_keywords_rus" class="form__textarea page-static-form__textarea" role="pageStaticKeywords" placeholder="{LANG:PAGE_STATIC_PAGE_INPUT_KEYWORDS_PLACEHOLDER}">{PAGE_STATIC_KEYWORDS}</textarea>
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
  <article class="page-aside__block">
    <h2 class="page-aside__block-title">{LANG:PAGE_STATIC_PAGE_SIDEBAR_BLOCK_ABOUT_TITLE}</h2>
    <div class="page-aside__block-content block-content">
      <div class="note-block note-block_blue">
        <p class="block-content__phar">{LANG:PAGE_STATIC_PAGE_SIDEBAR_BLOCK_ABOUT_DESCRIPTION_1}</p>
        <p class="block-content__phar">{LANG:PAGE_STATIC_PAGE_SIDEBAR_BLOCK_ABOUT_DESCRIPTION_2}</p>
      </div>
    </div>
  </article>
</aside>