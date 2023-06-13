<?php

namespace core\PHPLibrary {
  use \core\PHPLibrary\Client\Session as ClientSession;

  class Client {
    private readonly SystemCore $system_core;
    private string $ip_address;

    public function __construct(SystemCore $system_core) {
      $this->system_core = $system_core;

      $this->set_ip_address($_SERVER['REMOTE_ADDR']);
    }
    private function set_ip_address(string $value) : void {
      $this->ip_address = $value;
    }
    public function get_ip_address() : string {
      return $this->ip_address;
    }
    public function get_session(int $session_type_id, array $data_init) : ClientSession {
      $session = ClientSession::get_by_ip($this->system_core, $this->ip_address, $session_type_id);
      $session->init_data($data_init);
      return $session;
    }
    public function is_logged(int $session_type_id) : bool {
      if (ClientSession::exists_by_ip($this->system_core, $this->ip_address, $session_type_id)) {
        $client_session = $this->get_session($session_type_id, ['updated_unix_timestamp', 'token']);
        if (!is_null($client_session)) {
          switch ($session_type_id) {
            case 2: $client_session_cookie_token_name = '_grv_atoken'; break;
            default: $client_session_cookie_token_name = '_grv_utoken';
          }

          $client_session_token = (isset($_COOKIE[$client_session_cookie_token_name])) ? $_COOKIE[$client_session_cookie_token_name] : '';
          if (isset($_COOKIE[$client_session_cookie_token_name])) {
            if ($client_session_token == $client_session->get_token()) {
              if ($client_session->get_updated_unix_timestamp() + $this->system_core->configurator->get('session_expires') > time()) {
                return true;
              }
            }
          }
        }
      }

      return false;
    }
  }
}

?>