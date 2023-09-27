<div id="E3473967486" class="page__page-static-editor"></div>
<div id="E3473967486_CONTENT">{PAGE_STATIC_CONTENT}</div>
<script type="module">
  import {NadvoTE} from '/core/JSLibrary/nadvoTE.class.js';

  document.addEventListener('DOMContentLoaded', () => {
    let editorContent = document.querySelector('#E3473967486_CONTENT');

    let nadvoTE = new NadvoTE(document.querySelector('#E3473967486'), {
      'handler': '/handler/parsedown',
      'toolbar': [
        {'name': 'bold', 'type': 'button'},
        {'name': 'italic', 'type': 'button'},
        {'name': 'underline', 'type': 'button'},
        {'name': 'header1', 'type': 'button'},
        {'name': 'header2', 'type': 'button'},
        {'name': 'header3', 'type': 'button'},
        {'name': 'header4', 'type': 'button'},
        {'name': 'header5', 'type': 'button'},
        {'name': 'header6', 'type': 'button'},
        {'name': 'quote', 'type': 'button'},
        {'name': 'link', 'type': 'button'},
        {'name': 'image', 'type': 'button'},
        {'name': 'preview', 'type': 'button'},
        {'name': 'source', 'type': 'button'},
      ]
    });
    nadvoTE.init();
    nadvoTE.textarea.element.classList.add('form__textarea');
    nadvoTE.textarea.element.value = editorContent.innerHTML;
    nadvoTE.textarea.element.setAttribute('name', 'page_static_content_rus');
    nadvoTE.textarea.element.setAttribute('role', 'pageStaticContent');

    editorContent.remove();
  });
</script>