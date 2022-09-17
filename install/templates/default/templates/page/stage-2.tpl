<section class="section" data-stage-index="2">
  <div class="container">
    <h1 class="section__title">Этап 2: Проверка установленных компонентов PHP</h1>
    <div class="section__content">
      <table class="section__table table">
        <tr>
          <th colspan="3" class="table__header">Анализ системных требований</th>
        </tr>
        <tr>
          <th class="table__header">Требование</th>
          <th class="table__header">Рекомендация</th>
          <th class="table__header">Результат</th>
        </tr>
        <tr>
          <th class="table__header">Версия PHP 8.0 и выше</th>
          <td class="table__cell">&gt;= 8.0</td>
          <td class="table__cell">{PHP_VERSION_STATUS}</td>
        </tr>
        <tr>
          <th class="table__header">Поддержка PDO</th>
          <td class="table__cell">Да</td>
          <td class="table__cell">{EXTENSION_PDO_STATUS}</td>
        </tr>
        <tr>
          <th class="table__header">Поддержка PostgreSQL</th>
          <td class="table__cell">Да</td>
          <td class="table__cell">{EXTENSION_POSTGRESQL_STATUS}</td>
        </tr>
        <tr>
          <th class="table__header">Поддержка XML</th>
          <td class="table__cell">Да</td>
          <td class="table__cell">{EXTENSION_XML_STATUS}</td>
        </tr>
      </table>
    </div>
    <div class="section__panel">
      <button class="button panel__item" data-stage-event="last">&lt; Назад</button>
      <button class="button panel__item" data-stage-event="next">Далее &gt;</button>
    </div>
  </div>
</section>