<section class="section" data-stage-index="5">
  <div class="container">
    <h1 class="section__title">Этап 5: Создание административной учетной записи</h1>
    <div class="section__content">
      <form action="/cron/api.php?query=install&event=admin-create" class="form" data-not-handler="data-not-handler">
        <label for="">Логин</label>
        <input type="text" name="user_login" id="">
        <label for="">E-Mail</label>
        <input type="email" name="user_email" id="">
        <label for="">Пароль</label>
        <input type="password" name="user_password" id="">
        <label for="">Повторите пароль</label>
        <input type="password" name="user_password_repeat" id="">
      </form>
    </div>
    <div class="section__panel">
      <button class="button panel__item" data-stage-event="last">&lt; Назад</button>
      <button class="button panel__item" data-stage-event="admin-create">Генерация</button>
      <button class="button panel__item" data-stage-event="next">Далее &gt;</button>
    </div>
  </div>
</section>