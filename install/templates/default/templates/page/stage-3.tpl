<section class="section" data-stage-index="3">
  <div class="container">
    <h1 class="section__title">Этап 3: Проверка прав на запись файловой системы</h1>
    <div class="section__content">
      <div class="section__notice-container"></div>
      <p class="section__phar phar-illustration phar-illustration_what-this">
        Ой, а&nbsp;это что? А, точно... Нам еще нужно проверить, может&nbsp;ли система управления содержимым получить доступ для записи в&nbsp;собственных&nbsp;же папках. Без этих разрешений CMS не&nbsp;сможет полноценно работать. Давай проверим?
      </p>
      <table class="section__table table">
        <tr>
          <th colspan="3" class="table__header">Проверка прав доступа на запись в файловой системе CMS</th>
        </tr>
        <tr>
          <th class="table__header">Путь</th>
          <th class="table__header">Право</th>
          <th class="table__header">Статус</th>
        </tr>
        <tr>
          <td class="table__cell">./backups/</th>
          <td class="table__cell">{CHMOD_DIR_BACKUPS}</td>
          <td class="table__cell">{CHMOD_DIR_BACKUPS_RESULT}</td>
        </tr>
        <tr>
          <td class="table__cell">./templates/</th>
          <td class="table__cell">{CHMOD_DIR_TEMPLATES}</td>
          <td class="table__cell">{CHMOD_DIR_TEMPLATES_RESULT}</td>
        </tr>
        <tr>
          <td class="table__cell">./templates/default/</th>
          <td class="table__cell">{CHMOD_DIR_TEMPLATES_DEFAULT}</td>
          <td class="table__cell">{CHMOD_DIR_TEMPLATES_DEFAULT_RESULT}</td>
        </tr>
        <tr>
          <td class="table__cell">./uploads/</th>
          <td class="table__cell">{CHMOD_DIR_UPLOADS}</td>
          <td class="table__cell">{CHMOD_DIR_UPLOADS_RESULT}</td>
        </tr>
      </table>
    </div>
    <div class="section__panel">
      <button class="button panel__item" data-stage-event="last">&lt; Назад</button>
      <button class="button panel__item" data-stage-event="next">Далее &gt;</button>
    </div>
  </div>
</section>