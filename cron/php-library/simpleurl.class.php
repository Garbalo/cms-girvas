<?php

namespace cron\library {
	
	if (!defined('IS_NOT_HACKED')) {
		die('Unauthorized access attempt detected!');
	}
	
	class SimpleURL {
		function __construct() {
			$this->data = $this->get_parse_path();
		}
		
		public function get_path(int $id) {
			if (isset($this->data['path'][$id])) {
				return $this->data['path'][$id];
			}
			
			return null;
		}
		
		public function get_query(string $name) {
			if (isset($this->data['query'][$name])) {
				return $this->data['query'][$name];
			}
			
			return null;
		}
		
		private function get_parse_path() {
			$result = [];
			
			$current_path = parse_url($_SERVER['REQUEST_URI']);
			if (isset($current_path['path'])) {
				$path_array = explode('/', $current_path['path']);
				$result['path'] = [];
				
				foreach ($path_array as $path_part) {
					if ($path_part != '') {
						$path_part = (is_numeric($path_part)) ? (int)$path_part : $path_part;
						array_push($result['path'], $path_part);
					}
				}
			}
			
			if (isset($current_path['query'])) {
				$query_array = explode('&', $current_path['query']);
				$result['query'] = [];
				
				foreach ($query_array as $query_part) {
					preg_match('/([a-z0-9]*)\=([a-z0-9\-]*)/i', $query_part, $query_matches);
					if (array_key_exists(1, $query_matches) && array_key_exists(2, $query_matches)) {
						$query_value = (is_numeric($query_matches[2])) ? (int)$query_matches[2] : $query_matches[2];
						$result['query'][$query_matches[1]] = $query_value;
					}
				}
			}
			
			return $result;
		}
	}
}

?>