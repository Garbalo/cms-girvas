<main class="main">
  <section class="main__section section">
    <div class="main__container container">
      <div class="main__title-container title-container">
        <div class="title-container__step-container">
          <span class="title-container__step-label">Шаг</span>
          <span class="title-container__step-number">1</span>
        </div>
        <h2 class="main__title">Знакомство</h2>
      </div>
      <div class="main__page-container">
        <div class="main__page page" data-page-index="0">
          <p class="page__phar"><b>Спасибо за то, что выбрали CMS &laquo;GIRVAS&raquo;!</b></p>
          <p class="page__phar"><b>CMS&nbsp;&laquo;GIRVAS&raquo;</b>&nbsp;&dash;&nbsp;многопрофильная бесплатная система управления содержимым с открытым исходным кодом от карельских разработчиков &laquo;Garbalo&raquo;. Направление системы управления содержимым не является конкретным, поскольку при помощи данной CMS возможно практически все!</p>
          <p class="page__phar">Мастер-установщик поможет Вам правильно настроить и установить систему управления содержимым. Только хотим Вас предупредить о том, что CMS&nbsp;&laquo;GIRVAS&raquo; находится на стадии разразработки, поэтому она <b>может быть нестабильна</b>.</p>
        </div>
        <div class="main__page page" data-page-index="1">
          <p class="page__phar">Мастер-установщик проанализировал Вашу систему и вывел результат в таблице. В случае, если обнаружены ошибки, то рекомендуем их исправить для стабильной работы CMS&nbsp;&laquo;GIRVAS&raquo;.</p>
        </div>
        <div class="main__page page" data-page-index="2">
          <p class="page__phar">Мастер-установщик проанализировал корневую директорию, куда помещены файлы CMS и вывел результат в таблице. В случае, если обнаружены ошибки, то рекомендуем их исправить для стабильной работы системы управления содержимым.</p>
        </div>
        <div class="main__page page" data-page-index="3">
          <p class="page__phar">Мастер-установщик проанализировал права на некоторые директории, куда помещены файлы CMS и вывел результат в таблице. В случае, если обнаружены ошибки, то рекомендуем их исправить для стабильной работы системы управления содержимым.</p>
          <p class="page__phar"><b>Общие рекомендации по правам:</b><br>Для директорий (включая вложенных) <b>"./backups"</b>, <b>"./templates"</b>, <b>"./uploads"</b>, <b>"./modules"</b> необходимо установить права 0755, а для файлов, вложенных в директориях <b>"./modules"</b> и <b>"./templates"</b> 0644.</p>
        </div>
        <div class="main__page page" data-page-index="4">
          <p class="page__phar">Мастер-установщик проанализировал установленные СУБД и вывел результат в таблице. Если в таблице указана отрицательная оценка (помечена красным цветом) по отношению к предпочитаемой, то данная СУБД не была идентифицирована на Вашем сервере. Установите необходимую СУБД или выберите в следующем шаге доступную.</p>
        </div>
        <div class="main__page page" data-page-index="5">
          <p class="page__phar">Перед началом работы с базой данных, необходимо настроить CMS для работы с ней. Заполните все необходимые данные в форме ниже, после чего нажмите на кнопку "Создать конфигурации".</p>
          <p class="page__phar">
            <form class="form" role="form-database">
              <table class="table">
                <tr class="table__row">
                  <td class="table__cell cell">
                    <div class="cell__title">Префикс базы данных</div>
                    <div class="cell__description">
                      <div class="page__phar-block">Префикс базы данных позволит предотвратить конфликт наименований таблиц в том случае, если в Вашей базе данных уже имеются другие таблицы. Если префикс не нужен, то оставьте поле пустым.</div>
                    </div>
                  </td>
                  <td class="table__cell cell">
                    <input name="database_prefix" type="text" class="form__input" value="{CONFIGURATION_DATABASE_PREFIX}" placeholder="base">
                  </td>
                </tr>
                <tr class="table__row">
                  <td class="table__cell cell">
                    <div class="cell__title">Схема базы данных</div>
                    <div class="cell__description">
                      <div class="page__phar-block">Если Вы используете схемы, то укажите здесь ее наименование (обычно это "public"). Если не используете, то оставьте поле пустым.</div>
                    </div>
                  </td>
                  <td class="table__cell cell">
                    <input name="database_scheme" type="text" class="form__input" value="{CONFIGURATION_DATABASE_SCHEME}" placeholder="public">
                  </td>
                </tr>
                <tr class="table__row">
                  <td class="table__cell cell">
                    <div class="cell__title">Хост базы данных</div>
                  </td>
                  <td class="table__cell cell">
                    <input name="database_host" type="text" class="form__input" value="{CONFIGURATION_DATABASE_HOST}" placeholder="127.0.0.1">
                  </td>
                </tr>
                <tr class="table__row">
                  <td class="table__cell cell">
                    <div class="cell__title">Пользователь базы данных</div>
                  </td>
                  <td class="table__cell cell">
                    <input name="database_user" type="text" class="form__input" value="{CONFIGURATION_DATABASE_USER}" placeholder="db_user">
                  </td>
                </tr>
                <tr class="table__row">
                  <td class="table__cell cell">
                    <div class="cell__title">Пароль базы данных</div>
                  </td>
                  <td class="table__cell cell">
                    <input name="database_pass" type="password" class="form__input" value="{CONFIGURATION_DATABASE_PASSWORD}" placeholder="db_pass">
                  </td>
                </tr>
                <tr class="table__row">
                  <td class="table__cell cell">
                    <div class="cell__title">Наименование базы данных</div>
                  </td>
                  <td class="table__cell cell">
                    <input name="database_name" type="text" class="form__input" value="{CONFIGURATION_DATABASE_NAME}" placeholder="db_name">
                  </td>
                </tr>
              </table>
            </form>
          </p>
        </div>
        <div class="main__page page" data-page-index="6">
          <p class="page__phar">Выберите доступную СУБД, а мастер-установщик сгенерирует для Вас таблицы для подключенной базы данных. Это не займет много времени!</p>
          <p class="page__phar" role="cms-dms-selector"></p>
        </div>
        <div class="main__page-panel-container panel-container" role="installation-buttons-panel"></div>
      </div>
    </div>
  </section>
</main>