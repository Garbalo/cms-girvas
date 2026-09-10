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

namespace core\PHPLibrary\Page\Admin\Reports;

use \core\PHPLibrary\SystemCore as CMSCore;
use \core\PHPLibrary\SystemCore\Report as CMSReport;
use \core\PHPLibrary\SystemCore\Reports as CMSReports;
use \core\PHPLibrary\CoreInterface as CoreInterface;
use \core\PHPLibrary\Template as Template;
use \core\PHPLibrary\Template\Collector as ThemeCollector;
use \core\PHPLibrary\User as User;
use \core\PHPLibrary\Entry as Entry;
use \core\PHPLibrary\Page as Page;

/**
 * Class ReportsSecurity
 * 
 * Отчеты по безопасности за последние 7 дней
 */
class ReportsSecurity implements ReportsPageInterface
{
  const FORM_PATH = 'templates/page/reports';

  public string $title;
  public string $description;
  public string $assembled = '';
  protected ?User $viewer = null;
  protected array $localeData = [];

  /**
   * __construct
   * 
   * @param CoreInterface $CMSCore
   * @param string $name
   * 
   * @return void
   */
  public function __construct(
    public CoreInterface $CMSCore,
    public string $name
  ) {}

  /**
   * Установить заголовок
   * 
   * @param string $value
   * 
   * @return void
   */
  public function setTitle(string $value) : void
  {
    $this->title = $value;
  }

  /**
   * Установить описание
   * 
   * @param string $value
   * 
   * @return void
   */
  public function setDescription(string $value) : void
  {
    $this->description = $value;
  }

  /**
   * Получить заголовок
   * 
   * @return string
   */
  public function getTitle() : string
  {
    return $this->title;
  }

  /**
   * Получить описание
   * 
   * @return string
   */
  public function getDescription() : string
  {
    return $this->description;
  }

  /**
   * Установить пользователя для расшифровки ПДн
   * 
   * @param User $viewer
   * @return void
   */
  public function setViewer(User $viewer) : void
  {
    $this->viewer = $viewer;
  }

  /**
   * Получить объекты отчетов за период
   * 
   * @param array $typeIDs
   * @return array
   */
  private function getReportsByTypes(array $typeIDs) : array
  {
    $startPeriodUnix = time() - 604800;
    $endPeriodUnix = time();

    $reports = CMSReports::getAllByPeriod(
      $this->CMSCore,
      $startPeriodUnix,
      $endPeriodUnix,
      ['id', 'metadata', 'variables', 'createdUnixTimestamp']
    );

    $filtered = [];
    foreach ($reports as $report) {
      $typeID = $report->getTypeID();
      if (in_array($typeID, $typeIDs, true)) {
        $filtered[] = $report;
      }
    }

    return $filtered;
  }

  /**
   * Фильтровать отчеты по типам
   */
  private function filterReports(array $reportsObjects, array $typeIDs) : array
  {
    $filtered = [];
    foreach ($reportsObjects as $report) {
      $typeID = $report->getTypeID();
      if (in_array($typeID, $typeIDs, true)) {
        $filtered[] = $report;
      }
    }
    return $filtered;
  }

  /**
   * Получить имя типа отчета
   */
  private function getReportTypeName(int $typeID): string
  {
    $reflectionClass = new \ReflectionClass('\core\PHPLibrary\SystemCore\Report');
    $constants = $reflectionClass->getConstants();

    foreach ($constants as $name => $value) {
      if ($value === $typeID) {
        return $name;
      }
    }

    return 'UNKNOWN';
  }

  /**
   * Получить короткое название типа отчета для отображения
   */
  private function getReportTypeLabel(int $typeID): string
  {
    $typeName = $this->getReportTypeName($typeID);
    $shortTypeName = str_replace('REPORT_TYPE_ID_', '', $typeName);
    $labelKey = 'REPORT_TYPE_NAME_' . $shortTypeName;
    
    return $this->localeData[$labelKey] ?? $typeName;
  }

  /**
   * Получить логин пользователя по ID
   */
  private function getUserLogin(int $userID): string
  {
    if ($userID <= 0) {
      return 'system';
    }

    try {
      $user = new User($this->CMSCore, $userID);
      $user->initData(['login']);
      return $user->getLogin();
    } catch (\Exception $e) {
      return 'unknown';
    }
  }

  /**
   * Получить название записи по ID
   */
  private function getEntryTitle(int $entryID): string
  {
    if ($entryID <= 0) {
      return '';
    }

    try {
      $entry = new Entry($this->CMSCore, $entryID);
      $entry->initData(['texts']);
      $localeName = $this->CMSCore->locale->getName();
      return $entry->getTitle($localeName);
    } catch (\Exception $e) {
      return 'unknown';
    }
  }

  /**
   * Получить название страницы по ID
   */
  private function getPageTitle(int $pageID): string
  {
    if ($pageID <= 0) {
      return '';
    }

    try {
      $page = new Page($this->CMSCore, $pageID);
      $page->initData(['texts']);
      $localeName = $this->CMSCore->locale->getName();
      return $page->getTitle($localeName);
    } catch (\Exception $e) {
      return 'unknown';
    }
  }

  /**
   * Форматировать описание отчета с использованием локализации
   */
  private function formatReportDescription(CMSReport $report): string
  {
    $typeID = $report->getTypeID();
    $variables = $this->viewer !== null
      ? $report->getVariables($this->viewer)
      : $report->getVariables();

    $typeName = $this->getReportTypeName($typeID);
    $template = $this->localeData[$typeName] ?? '';

    if (empty($template)) {
      return $typeName . ' (ID: ' . ($variables['id'] ?? '?') . ')';
    }

    $replacements = [
      '{ENTRY_TITLE}' => $variables['entryTitle'] ?? $this->getEntryTitle($variables['entryID'] ?? $variables['id'] ?? 0),
      '{PAGE_TITLE}' => $variables['pageTitle'] ?? $this->getPageTitle($variables['pageID'] ?? $variables['id'] ?? 0),
      '{CATEGORY_TITLE}' => $variables['categoryTitle'] ?? $variables['name'] ?? '',
      '{FORM_TITLE}' => $variables['formTitle'] ?? $variables['name'] ?? '',
      '{BLOCK_TITLE}' => $variables['blockTitle'] ?? $variables['name'] ?? '',
      '{SAMPLE_TITLE}' => $variables['sampleTitle'] ?? $variables['name'] ?? '',
      '{FILE_NAME}' => $variables['fileName'] ?? $variables['name'] ?? '',
      '{CLIENT_IP}' => $variables['ip'] ?? $variables['clientIP'] ?? '0.0.0.0',
      '{USER_LOGIN}' => $this->getUserLogin($variables['userID'] ?? 0),
      '{CREATOR_LOGIN}' => $this->getUserLogin($variables['createdByID'] ?? 0),
      '{UPDATER_LOGIN}' => $this->getUserLogin($variables['updatedByID'] ?? 0),
      '{DELETER_LOGIN}' => $this->getUserLogin($variables['deletedByID'] ?? 0),
      '{TARGET_USER_LOGIN}' => $this->getUserLogin($variables['targetUserID'] ?? 0),
      '{VIEWER_LOGIN}' => $this->getUserLogin($variables['viewedByID'] ?? 0),
      '{COUNT}' => $variables['count'] ?? 0,
    ];

    // Специальная обработка для просмотра ПДн
    if ($typeID === CMSReport::REPORT_TYPE_ID_BASE_USER_PERSONAL_DATA_VIEWED) {
      if (isset($variables['action']) && $variables['action'] === 'list_view') {
        $template = $this->localeData['REPORT_TYPE_ID_BASE_USER_PERSONAL_DATA_VIEWED_LIST'] ?? $template;
        $replacements['{COUNT}'] = $variables['count'] ?? 0;
      }
    }

    return str_replace(
      array_keys($replacements),
      array_values($replacements),
      $template
    );
  }

  /**
   * Собрать шаблон
   * 
   * @param array $templateValues
   * 
   * @return void
   */
  public function assembly(array $templateValues = []) : void
  {
    $templatePath = 'templates/page/reports/' . $this->name . '.tpl';
    $this->localeData = $this->CMSCore->locale->getData();

    // ============================================================
    // ТИПЫ ОТЧЕТОВ ПО БЕЗОПАСНОСТИ
    // ============================================================
    
    $securityTypeIDs = [
      CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_SUCCESS,
      CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_FAIL,
      CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_SUCCESS,
      CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_FAIL,
      CMSReport::REPORT_TYPE_ID_BASE_USER_BANNED,
      CMSReport::REPORT_TYPE_ID_BASE_USER_UNBANNED,
      CMSReport::REPORT_TYPE_ID_BASE_USER_PERSONAL_DATA_VIEWED,
      CMSReport::REPORT_TYPE_ID_AP_VIEWING_LOGS,
    ];

    $reports = $this->getReportsByTypes($securityTypeIDs);

    // ============================================================
    // СТАТИСТИКА БЕЗОПАСНОСТИ
    // ============================================================
    
    $stats = [
      'auth_success_admin' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_SUCCESS])),
      'auth_fail_admin' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_FAIL])),
      'auth_success_site' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_SUCCESS])),
      'auth_fail_site' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_FAIL])),
      'banned' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_BASE_USER_BANNED])),
      'unbanned' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_BASE_USER_UNBANNED])),
      'personal_data_views' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_BASE_USER_PERSONAL_DATA_VIEWED])),
      'logs_views' => count($this->filterReports($reports, [CMSReport::REPORT_TYPE_ID_AP_VIEWING_LOGS])),
    ];

    // Уникальные IP-адреса
    $uniqueIpsSuccess = [];
    $uniqueIpsFail = [];

    foreach ($reports as $report) {
      $variables = $this->viewer !== null
        ? $report->getVariables($this->viewer)
        : $report->getVariables();
      
      $ip = $variables['ip'] ?? $variables['clientIP'] ?? null;
      $typeID = $report->getTypeID();

      if ($ip) {
        if (in_array($typeID, [
          CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_SUCCESS,
          CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_SUCCESS,
        ])) {
          $uniqueIpsSuccess[] = $ip;
        } elseif (in_array($typeID, [
          CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_FAIL,
          CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_FAIL,
        ])) {
          $uniqueIpsFail[] = $ip;
        }
      }
    }

    $uniqueIpsSuccess = array_unique($uniqueIpsSuccess);
    $uniqueIpsFail = array_unique($uniqueIpsFail);

    // ============================================================
    // ПОСЛЕДНИЕ СОБЫТИЯ БЕЗОПАСНОСТИ
    // ============================================================
    
    $recentItems = [];
    $recentReports = array_slice($reports, 0, 20);

    usort($recentReports, function($a, $b) {
      return $b->getCreatedUnixTimestamp() <=> $a->getCreatedUnixTimestamp();
    });

    foreach ($recentReports as $report) {
      $typeLabel = $this->getReportTypeLabel($report->getTypeID());
      $description = $this->formatReportDescription($report);
      $createdDate = date('d.m.Y H:i:s', $report->getCreatedUnixTimestamp());
      
      $variables = $this->viewer !== null
        ? $report->getVariables($this->viewer)
        : $report->getVariables();

      // Определяем статус события
      $statusClass = 'info';
      if (in_array($report->getTypeID(), [
        CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_SUCCESS,
        CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_SUCCESS,
        CMSReport::REPORT_TYPE_ID_BASE_USER_UNBANNED,
      ])) {
        $statusClass = 'success';
      } elseif (in_array($report->getTypeID(), [
        CMSReport::REPORT_TYPE_ID_AP_AUTHORIZATION_FAIL,
        CMSReport::REPORT_TYPE_ID_BASE_AUTHORIZATION_FAIL,
        CMSReport::REPORT_TYPE_ID_BASE_USER_BANNED,
      ])) {
        $statusClass = 'danger';
      }

      $recentItems[] = ThemeCollector::assemblyFileContent(
        $this->CMSCore->theme,
        'templates/page/reports/item.tpl',
        [
          'REPORT_TYPE' => $typeLabel,
          'REPORT_DESCRIPTION' => $description,
          'REPORT_DATE' => $createdDate,
          'REPORT_IP' => $variables['ip'] ?? $variables['clientIP'] ?? '0.0.0.0',
          'REPORT_STATUS_CLASS' => $statusClass
        ]
      );
    }

    // ============================================================
    // СБОРКА ШАБЛОНА
    // ============================================================

    $this->assembled = ThemeCollector::assemblyFileContent(
      $this->CMSCore->theme,
      $templatePath,
      [
        // Статистика безопасности
        'TOTAL_SUCCESS_AUTH_ADMIN' => $stats['auth_success_admin'],
        'TOTAL_FAIL_AUTH_ADMIN' => $stats['auth_fail_admin'],
        'TOTAL_SUCCESS_AUTH_SITE' => $stats['auth_success_site'],
        'TOTAL_FAIL_AUTH_SITE' => $stats['auth_fail_site'],
        'TOTAL_BANNED' => $stats['banned'],
        'TOTAL_UNBANNED' => $stats['unbanned'],
        'TOTAL_PERSONAL_DATA_VIEWS' => $stats['personal_data_views'],
        'TOTAL_LOGS_VIEWS' => $stats['logs_views'],
        'UNIQUE_IPS_SUCCESS' => !empty($uniqueIpsSuccess) ? implode(', ', $uniqueIpsSuccess) : '-',
        'UNIQUE_IPS_FAIL' => !empty($uniqueIpsFail) ? implode(', ', $uniqueIpsFail) : '-',
        'RECENT_EVENTS' => implode("\n", $recentItems)
      ]
    );
  }
}