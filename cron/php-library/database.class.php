<?php

namespace cron\library {
	use PDO;
	
	if (!defined('IS_NOT_HACKED')) {
		die('Unauthorized access attempt detected!');
	}
	
	class Database {
		private $dbuser;
		private $dbpassword;
		public $connect = null;
		
		function __construct() {
			$this->dbuser = 'drelagas_user';
			$this->dbpassword = 'g3&t@Q3ak';
			$dbname = 'drelagas_www_base';
			
			try {
				$this->connect = new PDO(sprintf('pgsql:host=localhost;dbname=%s', $dbname), $this->dbuser, $this->dbpassword);
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