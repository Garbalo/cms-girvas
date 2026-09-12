<?php

/**
 * CMS «ГИРВАС»
 * 
 * Включена в Реестр российского программного обеспечения Минцифры РФ
 * Реестровый номер: №25012 от 27.11.2024
 * 
 * @link        https://gitflic.ru/project/garbalo/cms-girvas Репозиторий продукта
 * @link        https://cms-girvas.ru Сайт продукта
 * 
 * @copyright   Copyright (c) 2021 - 2026, ИП Шестаков А.Р., «Карельский разработчик» (https://карельский-разработчик.рф/)
 * Все права защищены.
 * 
 * @license     https://gitflic.ru/project/garbalo/cms-girvas/LICENSE.md
 * @author      Андрей Шестаков <andrey.shestakov@karelian-developer.ru>
 * 
 * @support     support@karelian-developer.ru
 */

namespace core\PHPLibrary\PageStatic;

use \core\PHPLibrary\CoreInterface as CoreInterface;
use \core\PHPLibrary\SystemCore as CMSCore;
use \core\PHPLibrary\Database\QueryBuilder as DatabaseQueryBuilder;
use \core\PHPLibrary\Database\DatabaseManagementSystem as CMSDMS;
use \PDOException as PDOException;

#[\AllowDynamicProperties]
class Version
{
  private bool $isDataFullyInitialized = false;
  private array $initializedColumns = [];

  /**
   * __construct
   *
   * @param CoreInterface $CMSCore
   * @param int $id
   * 
   * @return void
   */
  public function __construct(
    private CoreInterface $CMSCore,
    private int $id
  ) {}

  /**
   * Инициализация данных из БД
   *
   * @param array $columns
   * @return void
   */
  public function initData(array $columns = ['*']) : void
  {
    if ($this->isDataFullyInitialized) {
      return;
    }

    if ($columns !== ['*'] && empty(array_diff($columns, $this->initializedColumns))) {
      return;
    }

    $columnsToLoad = $this->isDataFullyInitialized
      ? array_diff($columns, $this->initializedColumns)
      : $columns;

    $columnsData = $this->getDatabaseColumnsData($columnsToLoad);

    if ($columnsData !== null) {
      foreach ($columnsData as $name => $data) {
        $this->{$name} = $data;
      }

      if ($columns === ['*']) {
        $this->isDataFullyInitialized = true;
      } else {
        $this->initializedColumns = array_merge($this->initializedColumns, $columns);
      }
    }
  }

  /**
   * Получить ID версии
   *
   * @return int
   */
  public function getID() : int
  {
    return $this->id;
  }

  /**
   * Получить ID статической страницы
   *
   * @return int
   */
  public function getPageStaticID() : int
  {
    return $this->pageStaticID ?? 0;
  }

  /**
   * Получить номер версии
   *
   * @return string
   */
  public function getVersion() : string
  {
    return $this->version ?? '';
  }

  /**
   * Получить локаль
   *
   * @return string
   */
  public function getLocale() : string
  {
    return $this->locale ?? '';
  }

  /**
   * Получить тексты (снимок на момент версии)
   *
   * @return array
   */
  public function getTexts() : array
  {
    if (property_exists($this, 'texts')) {
      return json_decode($this->texts, true) ?? [];
    }

    return [];
  }

  /**
   * Получить заголовок версии
   *
   * @param string $localeName
   * @return string
   */
  public function getTitle(string $localeName = 'en_US') : string
  {
    $texts = $this->getTexts();

    if (isset($texts[$localeName]['title'])) {
      return $texts[$localeName]['title'];
    }

    return '';
  }

  /**
   * Получить содержимое версии
   *
   * @param string $localeName
   * @return string
   */
  public function getContent(string $localeName = 'en_US') : string
  {
    $texts = $this->getTexts();

    if (isset($texts[$localeName]['content'])) {
      return $texts[$localeName]['content'];
    }

    return '';
  }

  /**
   * Получить SEO-заголовок версии
   *
   * @param string $localeName
   * @return string
   */
  public function getSEOTitle(string $localeName = 'en_US') : string
  {
    $texts = $this->getTexts();

    if (isset($texts[$localeName]['SEOTitle'])) {
      return $texts[$localeName]['SEOTitle'];
    }

    return '';
  }

  /**
   * Получить описание версии
   *
   * @param string $localeName
   * @return string
   */
  public function getDescription(string $localeName = 'en_US') : string
  {
    $texts = $this->getTexts();

    if (isset($texts[$localeName]['description'])) {
      return $texts[$localeName]['description'];
    }

    return '';
  }

  /**
   * Получить SEO-описание версии
   *
   * @param string $localeName
   * @return string
   */
  public function getSEODescription(string $localeName = 'en_US') : string
  {
    $texts = $this->getTexts();

    if (isset($texts[$localeName]['SEODescription'])) {
      return $texts[$localeName]['SEODescription'];
    }

    return '';
  }

  /**
   * Получить ключевые слова версии
   *
   * @param string $localeName
   * @return array
   */
  public function getKeywords(string $localeName = 'en_US') : array
  {
    $texts = $this->getTexts();

    if (isset($texts[$localeName]['keywords']) && is_array($texts[$localeName]['keywords'])) {
      return $texts[$localeName]['keywords'];
    }

    return [];
  }

  /**
   * Получить дату вступления в силу
   *
   * @return int
   */
  public function getEffectiveFrom() : int
  {
    return $this->effectiveFrom ?? 0;
  }

  /**
   * Получить дату создания версии
   *
   * @return int
   */
  public function getCreatedUnixTimestamp() : int
  {
    return $this->createdUnixTimestamp ?? 0;
  }

  /**
   * Получить ID создателя версии
   *
   * @return int
   */
  public function getCreatedByID() : int
  {
    return $this->createdByID ?? 0;
  }

  /**
   * Является ли версия актуальной
   *
   * @return bool
   */
  public function isCurrent() : bool
  {
    return (bool) ($this->isCurrent ?? false);
  }

  /**
   * Получить данные колонок версии из БД
   *
   * @param array $columns
   * @return array|null
   */
  private function getDatabaseColumnsData(array $columns = ['*']) : array|null
  {
    $CMSConfigurator = $this->CMSCore->configurator;
    $CMSConfigDatabase = $CMSConfigurator->get('database');

    $queryBuilder = new DatabaseQueryBuilder($this->CMSCore, $CMSConfigDatabase['dms']);
    $queryBuilder->setStatementSelect();
    $queryBuilder->statement->addSelections($columns);
    $queryBuilder->statement->setClauseFrom();
    $queryBuilder->statement->clauseFrom->addTable('pages_static_versions');
    $queryBuilder->statement->clauseFrom->assembly();
    $queryBuilder->statement->setClauseWhere();
    $queryBuilder->statement->clauseWhere->addConditionAdaptive([
      'mysql' => '`id` = :id',
      'postgresql' => '"id" = :id'
    ]);
    $queryBuilder->statement->clauseWhere->assembly();
    $queryBuilder->statement->assembly();

    try {
      $databaseConnection = $this->CMSCore->databaseConnector->database->connection;
      $databaseQuery = $databaseConnection->prepare($queryBuilder->statement->assembled);
      $databaseQuery->bindParam(':id', $this->id, \PDO::PARAM_INT);
      $databaseQuery->execute();
    } catch (PDOException $exception) {
      die(json_encode([
        'message' => $exception->getMessage(),
        'statusCode' => 0,
        'outputData' => []
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    $result = $databaseQuery->fetch(\PDO::FETCH_ASSOC);
    return $result ? $result : null;
  }

  /**
   * Получить одну версию по условиям
   *
   * @param CMSCore $CMSCore
   * @param array $conditions
   * @return ?Version
   */
  private static function getOneBy(CMSCore $CMSCore, array $conditions) : ?Version
  {
    $CMSConfigurator = $CMSCore->configurator;
    $CMSConfigDatabase = $CMSConfigurator->get('database');

    $queryBuilder = new DatabaseQueryBuilder($CMSCore, $CMSConfigDatabase['dms']);
    $queryBuilder->setStatementSelect();
    $queryBuilder->statement->addSelections(['id']);
    $queryBuilder->statement->setClauseFrom();
    $queryBuilder->statement->clauseFrom->addTable('pages_static_versions');
    $queryBuilder->statement->clauseFrom->assembly();
    $queryBuilder->statement->setClauseWhere();

    $conditionPartsMysql = [];
    $conditionPartsPostgres = [];

    foreach (array_keys($conditions) as $key) {
      $conditionPartsMysql[] = '`' . $key . '` = :' . $key;
      $conditionPartsPostgres[] = '"' . $key . '" = :' . $key;
    }

    $queryBuilder->statement->clauseWhere->addConditionAdaptive([
      'mysql' => implode(' AND ', $conditionPartsMysql),
      'postgresql' => implode(' AND ', $conditionPartsPostgres)
    ]);
    $queryBuilder->statement->clauseWhere->assembly();
    $queryBuilder->statement->setClauseLimit(1);
    $queryBuilder->statement->assembly();

    try {
      $databaseConnection = $CMSCore->databaseConnector->database->connection;
      $databaseQuery = $databaseConnection->prepare($queryBuilder->statement->assembled);
      foreach ($conditions as $key => $value) {
        $type = is_int($value) ? \PDO::PARAM_INT : (is_bool($value) ? \PDO::PARAM_BOOL : \PDO::PARAM_STR);
        $databaseQuery->bindValue(':' . $key, $value, $type);
      }
      $databaseQuery->execute();
    } catch (PDOException $exception) {
      die(json_encode([
        'message' => $exception->getMessage(),
        'statusCode' => 0,
        'outputData' => []
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    $result = $databaseQuery->fetch(\PDO::FETCH_ASSOC);
    return $result ? new Version($CMSCore, (int)$result['id']) : null;
  }

  /**
   * Получить следующую доступную версию (если версия занята — инкрементирует последний компонент)
   *
   * @param CMSCore $CMSCore
   * @param int $pageStaticID
   * @param string $version
   * @param string $locale
   * @return string
   */
  public static function getNextVersion(CMSCore $CMSCore, int $pageStaticID, string $version, string $locale) : string
  {
    // Проверяем, свободна ли версия
    if (self::getByVersion($CMSCore, $pageStaticID, $version, $locale) === null) {
      return $version;
    }

    // Занята — инкрементим последний компонент
    $parts = explode('.', $version);
    $last = array_pop($parts);

    if (!is_numeric($last)) {
      // Не число — добавляем .1
      return self::getNextVersion($CMSCore, $pageStaticID, $version . '.1', $locale);
    }

    $parts[] = ((int)$last) + 1;
    $candidate = implode('.', $parts);

    // Рекурсивно проверяем
    return self::getNextVersion($CMSCore, $pageStaticID, $candidate, $locale);
  }

  /**
   * Получить версию по умолчанию для новой публикации
   *
   * @param CMSCore $CMSCore
   * @param int $pageStaticID
   * @param string $locale
   * @return string
   */
  public static function getDefaultVersion(CMSCore $CMSCore, int $pageStaticID, string $locale) : string
  {
    $current = self::getCurrent($CMSCore, $pageStaticID, $locale);

    if ($current === null) {
      // Нет актуальной версии — ищем последнюю по id
      $all = self::getAllByPage($CMSCore, $pageStaticID, $locale);
      if (empty($all)) {
        return '1.0';
      }
      
      // Берём первую (самую новую по createdUnixTimestamp DESC)
      $latest = $all[0];
      $latest->initData(['version']);
      $latestVersion = $latest->getVersion();
      
      if (empty($latestVersion)) {
        return '1.0';
      }
      
      $parts = explode('.', $latestVersion);
      $last = array_pop($parts);
      $parts[] = (is_numeric($last) ? (int)$last : 0) + 1;
      
      return implode('.', $parts);
    }

    $current->initData(['version']);
    $currentVersion = $current->getVersion();
    
    if (empty($currentVersion)) {
      return '1.0';
    }

    $parts = explode('.', $currentVersion);
    $last = array_pop($parts);
    $parts[] = (is_numeric($last) ? (int)$last : 0) + 1;

    return implode('.', $parts);
  }

  /**
   * Получить актуальную версию документа
   *
   * @param CMSCore $CMSCore
   * @param int $pageStaticID
   * @param string $locale
   * @return ?Version
   */
  public static function getCurrent(CMSCore $CMSCore, int $pageStaticID, string $locale) : ?Version
  {
    return self::getOneBy($CMSCore, [
      'pageStaticID' => $pageStaticID,
      'locale' => $locale,
      'isCurrent' => true
    ]);
  }

  /**
   * Получить конкретную версию документа
   *
   * @param CMSCore $CMSCore
   * @param int $pageStaticID
   * @param string $version
   * @param string $locale
   * @return ?Version
   */
  public static function getByVersion(CMSCore $CMSCore, int $pageStaticID, string $version, string $locale) : ?Version
  {
    return self::getOneBy($CMSCore, [
      'pageStaticID' => $pageStaticID,
      'version' => $version,
      'locale' => $locale
    ]);
  }

  /**
   * Получить все версии страницы
   *
   * @param CMSCore $CMSCore
   * @param int $pageStaticID
   * @param ?string $locale
   * @return array
   */
  public static function getAllByPage(CMSCore $CMSCore, int $pageStaticID, ?string $locale = null) : array
  {
    $CMSConfigurator = $CMSCore->configurator;
    $CMSConfigDatabase = $CMSConfigurator->get('database');

    $queryBuilder = new DatabaseQueryBuilder($CMSCore, $CMSConfigDatabase['dms']);
    $queryBuilder->setStatementSelect();
    $queryBuilder->statement->addSelections(['id']);
    $queryBuilder->statement->setClauseFrom();
    $queryBuilder->statement->clauseFrom->addTable('pages_static_versions');
    $queryBuilder->statement->clauseFrom->assembly();
    $queryBuilder->statement->setClauseWhere();

    $conditionMysql = '`pageStaticID` = :pageStaticID';
    $conditionPostgres = '"pageStaticID" = :pageStaticID';

    if ($locale !== null) {
      $conditionMysql .= ' AND `locale` = :locale';
      $conditionPostgres .= ' AND "locale" = :locale';
    }

    $queryBuilder->statement->clauseWhere->addConditionAdaptive([
      'mysql' => $conditionMysql,
      'postgresql' => $conditionPostgres
    ]);
    $queryBuilder->statement->clauseWhere->assembly();
    $queryBuilder->statement->setClauseOrderBy();
    $queryBuilder->statement->clauseOrderBy->setColumn('createdUnixTimestamp');
    $queryBuilder->statement->clauseOrderBy->setSortType('DESC');
    $queryBuilder->statement->assembly();

    try {
      $databaseConnection = $CMSCore->databaseConnector->database->connection;
      $databaseQuery = $databaseConnection->prepare($queryBuilder->statement->assembled);
      $databaseQuery->bindParam(':pageStaticID', $pageStaticID, \PDO::PARAM_INT);
      if ($locale !== null) {
        $databaseQuery->bindParam(':locale', $locale, \PDO::PARAM_STR);
      }
      $databaseQuery->execute();
    } catch (PDOException $exception) {
      die(json_encode([
        'message' => $exception->getMessage(),
        'statusCode' => 0,
        'outputData' => []
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    $result = $databaseQuery->fetchAll(\PDO::FETCH_ASSOC);
    $versions = [];
    foreach ($result as $row) {
      $versions[] = new Version($CMSCore, (int)$row['id']);
    }

    return $versions;
  }

  /**
   * Опубликовать новую версию
   *
   * @param CMSCore $CMSCore
   * @param int $pageStaticID
   * @param string $version
   * @param string $locale
   * @param array $texts
   * @param int $createdByID
   * @return ?Version
   */
  public static function publish(
    CMSCore $CMSCore,
    int $pageStaticID,
    string $version,
    string $locale,
    array $texts,
    int $createdByID = 0
  ) : ?Version {
    $CMSConfigurator = $CMSCore->configurator;
    $CMSConfigDatabase = $CMSConfigurator->get('database');

    $databaseConnection = $CMSCore->databaseConnector->database->connection;

    try {
      $databaseConnection->beginTransaction();

      // 1. Снимаем isCurrent со всех версий этой страницы+локали
      $queryBuilder = new DatabaseQueryBuilder($CMSCore, $CMSConfigDatabase['dms']);
      $queryBuilder->setStatementUpdate();
      $queryBuilder->statement->setTable('pages_static_versions');
      $queryBuilder->statement->setClauseSet();
      $queryBuilder->statement->clauseSet->addColumnAdaptive('isCurrent', [
        'mysql' => 'FALSE',
        'postgresql' => 'FALSE'
      ]);
      $queryBuilder->statement->clauseSet->assembly();
      $queryBuilder->statement->setClauseWhere();
      $queryBuilder->statement->clauseWhere->addConditionAdaptive([
        'mysql' => '`pageStaticID` = :pageStaticID AND `locale` = :locale',
        'postgresql' => '"pageStaticID" = :pageStaticID AND "locale" = :locale'
      ]);
      $queryBuilder->statement->clauseWhere->assembly();
      $queryBuilder->statement->assembly();

      $databaseQuery = $databaseConnection->prepare($queryBuilder->statement->assembled);
      $databaseQuery->bindParam(':pageStaticID', $pageStaticID, \PDO::PARAM_INT);
      $databaseQuery->bindParam(':locale', $locale, \PDO::PARAM_STR);
      $databaseQuery->execute();

      // 2. Создаём новую запись
      $queryBuilder = new DatabaseQueryBuilder($CMSCore, $CMSConfigDatabase['dms']);
      $queryBuilder->setStatementInsert();
      $queryBuilder->statement->setTable('pages_static_versions');
      $queryBuilder->statement->addColumn('pageStaticID');
      $queryBuilder->statement->addColumn('version');
      $queryBuilder->statement->addColumn('locale');
      $queryBuilder->statement->addColumn('texts');
      $queryBuilder->statement->addColumn('effectiveFrom');
      $queryBuilder->statement->addColumn('createdUnixTimestamp');
      $queryBuilder->statement->addColumn('createdByID');
      $queryBuilder->statement->addColumn('isCurrent');
      $queryBuilder->statement->setClauseReturning();
      $queryBuilder->statement->clauseReturning->addColumn('id');
      $queryBuilder->statement->assembly();

      $currentUnixTimestamp = time();
      $textsJSON = json_encode($texts, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

      $databaseQuery = $databaseConnection->prepare($queryBuilder->statement->assembled);
      $databaseQuery->bindParam(':pageStaticID', $pageStaticID, \PDO::PARAM_INT);
      $databaseQuery->bindParam(':version', $version, \PDO::PARAM_STR);
      $databaseQuery->bindParam(':locale', $locale, \PDO::PARAM_STR);
      $databaseQuery->bindParam(':texts', $textsJSON, \PDO::PARAM_STR);
      $databaseQuery->bindParam(':effectiveFrom', $currentUnixTimestamp, \PDO::PARAM_INT);
      $databaseQuery->bindParam(':createdUnixTimestamp', $currentUnixTimestamp, \PDO::PARAM_INT);
      $databaseQuery->bindParam(':createdByID', $createdByID, \PDO::PARAM_INT);
      $databaseQuery->bindValue(':isCurrent', true, \PDO::PARAM_BOOL);
      $databaseQuery->execute();

      $databaseConnection->commit();

      if ($CMSConfigDatabase['dms'] === CMSDMS::MySQL) {
        $lastID = $databaseConnection->lastInsertId();
        return new Version($CMSCore, (int)$lastID);
      }

      $result = $databaseQuery->fetch(\PDO::FETCH_ASSOC);
      return $result ? new Version($CMSCore, (int)$result['id']) : null;

    } catch (PDOException $exception) {
      $databaseConnection->rollBack();
      die(json_encode([
        'message' => $exception->getMessage(),
        'statusCode' => 0,
        'outputData' => []
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
  }
}