{SITE_HEADER}
<main class="main" role="installationSections">
  {SITE_MAIN}
</main>
<script>
  let forms = $(document).find('form');
  for (let form in forms) {
    if (typeof($(form).attr('data-not-handler')) !== 'undefined') {
      new Form(form);
    }
  }

  let master = new installationMaster(document.body);
</script>
{SITE_FOOTER}