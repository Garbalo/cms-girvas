<form action="/handler/entry/comment" class="article__comment-form form" role="entryCommentForm">
  <textarea class="form__textarea" name="comment_content" cols="30" rows="3" placeholder="Что думаете об этом?"></textarea>
  <input type="hidden" name="comment_parent_id" value="0">
  <input name="comment_entry_id" type="hidden" value="{ENTRY_ID}">
  <input class="form__input form__input_submit" type="submit" value="Отправить" formmethod="PUT">
</form>