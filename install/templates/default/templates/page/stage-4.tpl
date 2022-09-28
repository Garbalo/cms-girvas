<section class="section" data-stage-index="4">
  <div class="container">
    <h1 class="section__title">Этап 4: Подключение к базе данных и ее генерация</h1>
    <div class="section__content">
      <div class="section__notice-container"></div>
      <form action="/cron/api.php?query=install&event=database-generate" class="form" data-not-handler="data-not-handler">
        <label for="">Сервер PostgreSQL</label>
        <input type="text" name="database_server" id="" placeholder="127.0.0.1">
        <label for="">Имя БД</label>
        <input type="text" name="database_name" id="" placeholder="DatabaseName">
        <label for="">Имя пользователя</label>
        <input type="text" name="database_username" id="" placeholder="DatabaseUser">
        <label for="">Пароль</label>
        <input type="password" name="database_password" id="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
        <label for="">Префикс</label>
        <input type="text" name="database_prefix" id="" placeholder="grvs">
      </form>
      <div class="section__block section__block_console-log">
        <h2 class="block__title">Database Console Log</h2>
        <div class="block__content">
          <div class="content__container">
            <p>GIRVAS. All rights reserved. 2022. [ Version {CMS_VERSION} ]</p>
          </div>
        </div>
      </div>
    </div>
    <div class="section__panel">
      <button class="button panel__item" data-stage-event="last">&lt; Назад</button>
      <button class="button panel__item" data-stage-event="database-generate">Генерация</button>
      <button class="button panel__item" data-stage-event="next">Далее &gt;</button>
    </div>
  </div>
</section>