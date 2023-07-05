<li id="#comment{COMMENT_INDEX}" class="comments-list__item comment">
  <ul class="comment__metadata-list metadata-list list-reset">
    <li class="metadata-list__item">#{COMMENT_INDEX}</li>
    <li class="metadata-list__item">Дата публикации: {COMMENT_CREATED_DATE_TIMESTAMP}</li>
  </ul>
  <div class="comment__avatar-container">
    <img src="{COMMENT_AUTHOR_AVATAR_URL}" alt="COMMENT_AUTHOR_LOGIN">
  </div>
  <div class="comment__container">
    <div class="comment__author-login-container">{COMMENT_AUTHOR_LOGIN}</div>
    <div class="comment__content-container">{COMMENT_CONTENT}</div>
  </div>
</li>