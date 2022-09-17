<?php

namespace cron\library {
    
    class Template {
        
        const REGEX_TEMPLATE_PATTERN_CODE = '/\{([a-zA-Z0-9_]+)\}/';

        public $core;
        public $surl;

        protected $name;
        protected $path_to_template_folder;

        private array $styles = [];
        private array $javascripts = [];

        private string $path_root;
        private string $path_styles;
        private string $path_javascripts;

        protected string $assembled = '{L:CORE_LIB_TEMPLATE:NOT_ASSEBMLED}';

        public function __construct() {
            $this->surl = new SimpleURL();

            //$this->connect_core($name, $this->get_core_path($name, $path));
        }
        
        public function add_style(array $data) : void {
            array_push($this->styles, $data);
        }

        public function add_javascript(array $data) : void {
            array_push($this->javascripts, $data);
        }

        public function get_ready_assembly() : string {
            return $this->get_filal_assembly($this->assembled);
        }

        public static function assembly(string $template_string, array $template_replaces) : string {
			$self_class = get_called_class();
			
			$template_transformed = $template_string;
			foreach($template_replaces as $template_name => $template_value) {
				if (preg_match($self_class::REGEX_TEMPLATE_PATTERN_CODE, $template_transformed)) {
					$template_transformed = str_replace("{{$template_name}}", $template_value, $template_transformed);
				}
			}
			
			return $template_transformed;
		}

        public static function connect_core($template_name, $core_path, $adding_path = '') {
            $template_path = self::get_template_path($template_name, $core_path);
            include_once(self::get_core_path($template_path));

            $class_path = (empty($adding_path)) ? sprintf('templates\\%s\\Core', $template_name) : sprintf('%s\\templates\\%s\\Core', $adding_path, $template_name);
            return new $class_path($template_name, $template_path);
        }

        protected static function get_template_path($template_name, $templates_path) : string {
            return sprintf('%s/%s', $templates_path, $template_name);
        }

        private static function get_core_path($templates_path) : string {
            return sprintf('%s/core.class.php', $templates_path);
        }

        private function get_filal_assembly() : string {

            require(sprintf('%s/cron/config.php', DOCUMENT_ROOT));

            $site_styles = [];
            $site_javascripts = [];

            foreach ($this->styles as $style) {
                $attrs = [];

                if (isset($style['attr'])) {
                    foreach ($style['attr'] as $attr_name => $attr_val) {
                        array_push($attrs, sprintf('%s="%s"', $attr_name, $attr_val));
                    }
                }
                
                array_push($site_styles, self::assembly(
                    '<link href="{STYLE_URL}" {STYLE_ATTR}>', [
                    'STYLE_URL' => $style['url'],
                    'STYLE_ATTR' => implode(' ', $attrs)
                ]));
            }

            foreach ($this->javascripts as $javascript) {
                array_push($site_javascripts, self::assembly(
                    '<script src="{SCRIPT_SRC}"></script>', [
                    'SCRIPT_SRC' => $javascript['src']
                ]));
            }

            return self::assembly($this->assembled, [
                'SITE_STYLES' => implode($site_styles),
                'SITE_JAVASCRIPTS' => implode($site_javascripts),
                'SITE_TEMPLATE_NAME' => $this->name,
                'SITE_COPYRIGHT' => sprintf('Powered by &laquo;%s&raquo;', $_CMS['metadata']['title']),
                'CMS_VERSION' => $_CMS['metadata']['version']
            ]);
        }

    }

}

?>