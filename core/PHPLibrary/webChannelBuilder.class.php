<?php

namespace core\PHPLibrary {
  use \DOMDocument as DOMDocument;
  use \core\PHPLibrary\WebChannelBuilder\EnumWebChannel as EnumWebChannel;
  use \core\PHPLibrary\WebChannelBuilder\InterfaceWebChannel as InterfaceWebChannel;
  use \core\PHPLibrary\WebChannelBuilder\WebChannel\RSS1_0 as WebChannelRSS1_0;
  use \core\PHPLibrary\WebChannelBuilder\WebChannel\RSS2_0 as WebChannelRSS2_0;
  use \core\PHPLibrary\WebChannelBuilder\WebChannel\Atom as WebChannelAtom;

  final class WebChannelBuilder {
    public DOMDocument $document;
    private SystemCore $system_core;
    public $web_channel;
    private array $items = [];
    private string $language = 'en-us';
    public string $assembled = '';

    /**
     * __construct
     *
     * @param  SystemCore $system_core
     * @return void
     */
    public function __construct(SystemCore $system_core, EnumWebChannel $web_channel_type) {
      $this->system_core = $system_core;
      $this->document = new DOMDocument('1.0');

      $this->web_channel = match ($web_channel_type) {
        EnumWebChannel::RSS1_0 => new WebChannelRSS1_0($system_core, $this),
        EnumWebChannel::RSS2_0 => new WebChannelRSS2_0($system_core, $this),
        EnumWebChannel::Atom => new WebChannelAtom($system_core, $this),
      };
    }
    
    /**
     * Назначит язык RSS-ленты
     *
     * @param  mixed $locale_name
     * @return void
     */
    public function set_language(string $locale_name) : void {
      $this->language = str_replace('_', '-', strtolower($locale_name));
    }
    
    /**
     * Получить язык RSS-ленты
     *
     * @return string
     */
    public function get_language() : string {
      return $this->language;
    }

    public static function get_type_enum(int $type_id) : EnumWebChannel|null {
      switch ($type_id) {
        case 1: return EnumWebChannel::RSS1_0;
        case 2: return EnumWebChannel::RSS2_0;
        case 3: return EnumWebChannel::Atom;
      }

      return null;
    }

    public static function get_type_title(int $type_id) : string {
      switch ($type_id) {
        case 1: return WebChannelRSS1_0::TYPE_TITLE;
        case 2: return WebChannelRSS2_0::TYPE_TITLE;
        case 3: return WebChannelAtom::TYPE_TITLE;
      }

      return '';
    }

    public static function get_type_name(int $type_id) : string {
      switch ($type_id) {
        case 1: return WebChannelRSS1_0::TYPE_NAME;
        case 2: return WebChannelRSS2_0::TYPE_NAME;
        case 3: return WebChannelAtom::TYPE_NAME;
      }

      return '';
    }

    public function assembly() : void {
      $this->web_channel->assembly();
    }
  }
}

?>