<?php

namespace cron\library {
	class UserSession {
		public function __construct(int $user_id, int $type_id = 0) {
			$this->user_id = $user_id;
			$this->type_id = $type_id;
			
			$data = $this->get_data();
			if (!is_null($data)) {
				$this->id = $data['id'];
				$this->token = $data['token'];
			}
		}
		
		public function get_user() {
			return new User($this->user_id);
		}
		
		public function remove() {
			$database = new Database();
			$database_query = $database->connect->prepare('DELETE FROM public.users_sessions WHERE id = :id;');
			$database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public function token_verify(string $token) {
			return ($token == $this->token) ? true : false;
		}
		
		public static function create(int $user_id, string $token, int $type_id = 0) {
			$self_class = get_called_class();
			
			$database = new Database();
			$database_query = $database->connect->prepare('INSERT INTO public.users_sessions (user_id, type_id, token) VALUES (:user_id,:type_id,:token) RETURNING id;');
			$database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
			$database_query->bindParam(':type_id', $type_id, \PDO::PARAM_INT);
			$database_query->bindParam(':token', $token, \PDO::PARAM_STR);
			$execute = $database_query->execute();
			
			if ($execute) {
				$result = $database_query->fetch(\PDO::FETCH_ASSOC);
				return new $self_class($user_id, $type_id);
			}
			
			return null;
		}
		
		public static function update_token(int $user_id, string $token, int $type_id = 0) {
			$database = new Database();
			$database_query = $database->connect->prepare('UPDATE public.users_sessions SET token = :token WHERE user_id = :user_id AND type_id = :type_id;');
			$database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
			$database_query->bindParam(':type_id', $type_id, \PDO::PARAM_INT);
			$database_query->bindParam(':token', $token, \PDO::PARAM_STR);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public static function exists(int $user_id, int $type_id = 0) {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users_sessions WHERE user_id = :user_id AND type_id = :type_id LIMIT 1;');
			$database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
			$database_query->bindParam(':type_id', $type_id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return true;
			}
			
			return false;
		}
		
		public static function get(int $user_id, int $type_id = 0) {
			$self_class = get_called_class();
			
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users_sessions WHERE user_id = :user_id AND type_id = :type_id LIMIT 1;');
			$database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
			$database_query->bindParam(':type_id', $type_id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return new $self_class($user_id, $type_id);
			}
			
			return null;
		}
		
		private function get_data() {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users_sessions WHERE user_id = :user_id AND type_id = :type_id LIMIT 1;');
			$database_query->bindParam(':user_id', $this->user_id, \PDO::PARAM_INT);
			$database_query->bindParam(':type_id', $this->type_id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return $row_target;
			}
			
			return false;
		}
	}
}

?>