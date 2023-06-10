<div id="E3473967486" class="page__entry-editor"></div>
<script type="module">
  import {NadvoTE} from '/core/JSLibrary/nadvoTE.class.js';

  document.addEventListener('DOMContentLoaded', () => {
    let nadvoTE = new NadvoTE(document.querySelector('#E3473967486'), {
      'handler': '/handler/parsedown',
      'toolbar': [
        {
          'name': 'bold',
          'type': 'button'
        },
        {
          'name': 'italic',
          'type': 'button'
        },
        {
          'name': 'underline',
          'type': 'button'
        },
        // {
        //   'name': 'header',
        //   'type': 'select'
        // },
        {
          'name': 'preview',
          'type': 'button'
        },
        {
          'name': 'source',
          'type': 'button'
        }
      ]
    });
    nadvoTE.init();
    nadvoTE.textarea.element.classList.add('form__textarea');
    nadvoTE.textarea.element.value = '{ENTRY_CONTENT}';
    nadvoTE.textarea.element.setAttribute('name', 'entry_content_rus');
  });
</script>