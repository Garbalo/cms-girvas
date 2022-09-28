<?php

namespace cron\library {
	use PDO;
	
	if (!defined('IS_NOT_HACKED')) {
		die('Unauthorized access attempt detected!');
	}
	
	class Database {
		private string $dbserver;
		private string $dbuser;
		private string $dbusername;
		private string $dbpassword;
		public $connect = null;
		
		function __construct() {
			if (file_exists(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT))) {
				require_once(sprintf('%s/cron/database.config.php', DOCUMENT_ROOT));
			} else {
				die('Database configuration is not created.');
			}

			$this->dbserver = $_CMS['database']['server'];
			$this->dbname = $_CMS['database']['name'];
			$this->dbusername = $_CMS['database']['username'];
			$this->dbpassword = $_CMS['database']['password'];
			
			try {
				$this->connect = new PDO(sprintf('pgsql:host=%s;dbname=%s', $this->dbserver, $this->dbname), $this->dbuser, $this->dbpassword);
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
		
		public function disconnect() {
			$this->connect = null;
		}
		
		public static function convertArrayForPHP(string $pgsql_array) {
			preg_match_all('/^\{(.*)\}$/', $pgsql_array, $matches_global);
			if (count($matches_global) > 0) {
				$result_array = [];
				
				preg_match_all('/([\w\W\&\;]+)/', $pgsql_array, $matches_values, PREG_PATTERN_ORDER);
				var_dump($matches_values);
				foreach($matches_values as $value) {
					array_push($result_array, $value);
				}
				
				return $result_array;
			}
			
			return [];
		}
		
		# https://stackoverflow.com/questions/5631387/php-array-to-postgres-array
		public static function to_pg_array(array $set) {
			settype($set, 'array');
			$result = array();
			foreach ($set as $t) {
				if (is_array($t)) {
					$result[] = to_pg_array($t);
				} else {
					$t = str_replace('"', '\\"', $t);
					if (! is_numeric($t)) {
						$t = sprintf('"%s"', $t);
					}
					$result[] = $t;
				}
			}
			
			return sprintf('{%s}', implode(',', $result)); // format
		}
	}
}

?>