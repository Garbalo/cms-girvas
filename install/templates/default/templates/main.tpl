{SITE_HEADER}
<main class="main" role="installationSections">
  {SITE_MAIN}
</main>
<script>
  let forms = $(document).find('form');
  for (let form_i = 0; form_i < forms.length; form_i++) {
    new Form(forms[form_i]);
  }

  let master = new installationMaster(document.body);
</script>
{SITE_FOOTER}