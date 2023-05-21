<?php

namespace core\PHPLibrary\Template {
  use \core\PHPLibrary\Template as Template;

  final class Collector {
    private const TEMPLATE_TAG_PATTERN = '/\{([a-zA-Z0-9_]+)\}/';
    private Template $template;
    
    /**
     * __construct
     *
     * @param  mixed $template
     * @return void
     */
    public function __construct(Template $template) {
      $this->template = $template;
    }

    public static function assembly_styles(Template $template, array $styles_array) : string {
      /** @var array $styles_assembled Массив стилей страницы, прошедших сборку */
      $styles_assembled = [];

      foreach ($styles_array as $style) {
        if (array_key_exists('href', $style) && array_key_exists('rel', $style)) {
          /** @var string $style_assembled Собранный DOM-элемент LINK для добавления стиля */
          $style_assembled = self::assembly('<link href="{STYLE_HREF}" rel="{STYLE_RELATIONSHIP}">', [
            'STYLE_HREF' => sprintf('/templates/%s/%s', $template->get_name(), $style['href']),
            'STYLE_RELATIONSHIP' => $style['rel']
          ]);

          array_push($styles_assembled, $style_assembled);
        }
      }

      return implode($styles_assembled);
    }

    public static function assembly_scripts(Template $template, array $scripts_array) : string {
      /** @var array $styles_assembled Массив стилей страницы, прошедших сборку */
      $scripts_assembled = [];

      foreach ($scripts_array as $script) {
        if (array_key_exists('src', $script)) {
          /** @var string $script_assembled Собранный DOM-элемент SCRIPT для добавления скрипта */
          $script_assembled = self::assembly('<script src="{SCRIPT_SOURCE}"></script>', [
            'SCRIPT_SOURCE' => sprintf('/templates/%s/%s', $template->get_name(), $script['src'])
          ]);

          array_push($scripts_assembled, $script_assembled);
        }
      }

      return implode($scripts_assembled);
    }

    /**
     * Сборка шаблона на основе строки
     *
     * @param  mixed $template_string Содержимое шаблона
     * @param  mixed $template_replaces Массив с тегами шаблона и их значениями
     * @return string
     */
    public static function assembly(string $template_string, array $template_replaces) : string {
      $template_transformed = $template_string;

      foreach($template_replaces as $template_name => $template_value) {
        if (preg_match(self::TEMPLATE_TAG_PATTERN, $template_transformed)) {
          $template_transformed = str_replace("{{$template_name}}", $template_value, $template_transformed);
        }
      }

      return $template_transformed;
    }
    
    /**
     * Сборка шаблона на основе содержимого файла
     *
     * @param  mixed $file_path Полный путь до файла
     * @param  mixed $template_replaces Массив с тегами шаблона и их значениями
     * @return string
     */
    public static function assembly_file_content(Template $template, string $file_path, array $template_replaces) : string {
      /** @var string $file_path Полный путь до шаблона */
      $file_path = sprintf('%s/%s', $template->get_path(), $file_path);

      if (file_exists($file_path)) {
        $file_content = file_get_contents($file_path);
        return self::assembly($file_content, $template_replaces);
      }

      return sprintf('{ERROR:FILE_IS_NOT_EXISTS=%s}', $file_path);
    }

  }

}

?>