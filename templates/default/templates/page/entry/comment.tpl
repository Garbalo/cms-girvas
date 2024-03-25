<li id="comment_{COMMENT_INDEX}" class="comments-list__item comment" role="entry-comment" data-comment-id="{COMMENT_ID}">
  <ul class="comment__metadata-list metadata-list list-reset">
    <li class="metadata-list__item metadata-list__item_index item">
      <a class="item__value" href="#" role="entry-comment-index">#{COMMENT_INDEX}</a>
    </li>
    <li class="metadata-list__item metadata-list__item_datetime item">
      <span class="item__title">Создан</span>  
      <time class="item__value" datetime="{COMMENT_CREATED_DATE_TIMESTAMP}">{COMMENT_CREATED_DATE_TIMESTAMP}</time>
    </li>
    <li class="metadata-list__item metadata-list__item_user-group item">
      <span class="item__value metadata">Администратор</span>
    </li>
  </ul>
  <div class="comment__container">
    <div class="comment__avatar-container">
      <img class="comment__avatar" src="{COMMENT_AUTHOR_AVATAR_URL}" alt="{COMMENT_AUTHOR_LOGIN}" role="entry-comment-author-avatar">
      <div id="E7453975856_{COMMENT_INDEX}" class="comment__panel-interactive">
        <div class="comment__rate-block" role="entry-comment-rate"></div>
      </div>
    </div>
    <div class="comment__content-container">
      <h4 class="comment__author-login">{COMMENT_AUTHOR_LOGIN}</h4>
      <div class="comment__content">{COMMENT_CONTENT}</div>
    </div>
  </div>
</li>