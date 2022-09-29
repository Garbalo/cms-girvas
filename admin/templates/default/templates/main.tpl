{SITE_HEADER}
<main class="main">
  {SITE_MAIN}
</main>
<script>
  let forms = $(document).find('form');
  for (let form in forms) {
    if (typeof($(form).attr('data-not-handler')) !== 'undefined') {
      new Form(form);
    }
  }
</script>
{SITE_FOOTER}