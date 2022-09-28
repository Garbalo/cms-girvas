<section class="section" data-stage-index="5">
  <div class="container">
    <h1 class="section__title">Этап 5: Создание административной учетной записи</h1>
    <div class="section__content">
      <div class="section__notice-container"></div>
      <form action="/cron/api.php?query=install&event=admin-create" class="form" data-not-handler="data-not-handler" method="POST">
        <label for="I1001000000">Логин</label>
        <input type="text" name="user_login" id="I1001000000">
        <label for="I1001000001">E-Mail</label>
        <input type="email" name="user_email" id="I1001000001">
        <label for="I1001000002">Пароль</label>
        <input type="password" name="user_password" id="I1001000002">
        <label for="I1001000003">Повторите пароль</label>
        <input type="password" name="user_password_repeat" id="I1001000003">
      </form>
    </div>
    <div class="section__panel">
      <button class="button panel__item" data-stage-event="last">&lt; Назад</button>
      <button class="button panel__item" data-stage-event="admin-create">Создать</button>
      <button class="button panel__item" data-stage-event="next">Далее &gt;</button>
    </div>
  </div>
</section>