<?php

namespace cron\library {
	
	if (!defined('IS_NOT_HACKED')) {
		die('Unauthorized access attempt detected!');
	}
	
	class User {
		public int $id;
		public string $ip;
		public string $login;
		public string $email;
		public string $hash;
		private string $token;
		private string $salt;
		
		public function __construct(int $id = 0) {
			//$this->database = new Database();
			$this->id = $id;
			$this->ip = $_SERVER['REMOTE_ADDR'];
			
			if ($this->id == 0) {
				if ($this->is_logged()) {
					$this->id = (is_numeric($_COOKIE['_uid'])) ? (int)$_COOKIE['_uid'] : 0;
				}
			}
			
			if ($this->id != 0) {
				$data = $this->get_data();
				$this->login = $data['login'];
				$this->email = $data['email'];
				$this->email_is_verified = $data['email_verified'];
				$this->hash = $data['hash'];
				//$this->token = $data['token'];
				$this->registration_datetime = $data['registration_datetime'];
				$this->registration_timeunix = strtotime($data['registration_datetime']);
				$this->last_active_datetime = $data['last_active_datetime'];
				$this->last_active_unix = strtotime($data['last_active_datetime']);
				$this->profile_avatar_url = $data['profile_avatar_url'];
				$this->profile_background_id = $data['profile_background_id'];
				$this->surname = $data['surname'];
				$this->name = $data['name'];
				$this->patronymic = $data['patronymic'];
				$this->birthdate = $data['birthdate'];
				$this->group_id = $data['group_id'];
				$this->salt = $data['salt'];
			}
		}
		
		public static function gen_salt($length = 20) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}

		public function get_birthdate() {
			return $this->birthdate;
		}

		public function get_birthdate_transformed() {
			$date = strtotime($this->birthdate);
			return date('d.m.Y', $date);
		}

		public function get_registration_date() {
			return $this->registration_datetime;
		}

		public function get_registration_date_transformed() {
			$date = strtotime($this->registration_datetime);
			return date('d.m.Y', $date);
		}

		public function get_last_active_date() {
			return $this->last_active_datetime;
		}

		public function get_last_active_date_transformed() {
			$date = strtotime($this->last_active_datetime);
			return date('d.m.Y', $date);
		}

		public function get_fullname() {
			$array = [];

			if (!empty($this->surname)) array_push($array, $this->surname);
			if (!empty($this->name)) array_push($array, $this->name);
			if (!empty($this->patronymic)) array_push($array, $this->patronymic);
			
			if (empty($array)) {
				return $this->login;
			}

			return implode(' ', $array);
		}

		public function get_personal_salt() {
			return $this->salt;
		}

		public function get_group() : UserGroup {
			return new UserGroup($this->group_id);
		}
		
		public function set_email(string $email) {
			$database = new Database();
			$database_query = $database->connect->prepare('UPDATE public.users SET email = :email WHERE id = :id;');
			$database_query->bindParam(':email', $email, \PDO::PARAM_STR);
			$database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public function get_avatar_url(int $size) {
			return sprintf('https://himg-s2j4u2.garbalo.com/avatars/%s/%s.jpg', $size, $this->profile_avatar_url);
		}
		
		public function set_email_verified_status(bool $status) {
			$database = new Database();
			$database_query = $database->connect->prepare('UPDATE public.users SET email_verified = :email_verified WHERE id = :id;');
			$database_query->bindParam(':email_verified', $status, \PDO::PARAM_STR);
			$database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public function set_activated_status(bool $status) {
			$database = new Database();
			$database_query = $database->connect->prepare('UPDATE public.users SET activated = :activated WHERE id = :id;');
			$database_query->bindParam(':activated', $status, \PDO::PARAM_STR);
			$database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public function set_avatar_url(string $url) {
			$database = new Database();
			$database_query = $database->connect->prepare('UPDATE public.users SET profile_avatar_url = :profile_avatar_url WHERE id = :id;');
			$database_query->bindParam(':profile_avatar_url', $url, \PDO::PARAM_STR);
			$database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public function get_wallet_value() : int {
			$database_query = $GLOBALS['database']->connect->prepare('SELECT * FROM public.users_wallets WHERE owner_id=? LIMIT 1;');
			$database_query->bindParam(1, $this->id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return $row_target['value'];
			}
			
			return 0;
		}
		
		public function set_admin_code(array $codes) {
			$user_id = $this->id;
			
			$code_alpha = md5('{GAPI2022}__&a__' . $codes[0]);
			$code_beta = md5('{GAPI2022}__&b__' . $codes[1]);
			$code_gamma = md5('{GAPI2022}__&g__' . $codes[2]);
			
			$database = new Database();
			if (!$this->exists_admin_code()) {
				$database_query = $database->connect->prepare('INSERT INTO public.admin_codes (user_id,alpha,beta,gamma) VALUES (:user_id,:alpha,:beta,:gamma);');
				$database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
				$database_query->bindParam(':alpha', $code_alpha, \PDO::PARAM_STR);
				$database_query->bindParam(':beta', $code_beta, \PDO::PARAM_STR);
				$database_query->bindParam(':gamma', $code_gamma, \PDO::PARAM_STR);
			} else {
				$database_query = $database->connect->prepare('UPDATE public.admin_codes SET alpha = :alpha, beta = :beta, gamma = :gamma WHERE user_id = :user_id;');
				$database_query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
				$database_query->bindParam(':alpha', $code_alpha, \PDO::PARAM_STR);
				$database_query->bindParam(':beta', $code_beta, \PDO::PARAM_STR);
				$database_query->bindParam(':gamma', $code_gamma, \PDO::PARAM_STR);
			}
			
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public function exists_admin_code() {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.admin_codes WHERE user_id = :user_id LIMIT 1;');
			$database_query->bindParam(':user_id', $this->id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return true;
			}
			
			return false;
		}
		
		public function get_admin_code() {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.admin_codes WHERE user_id = :user_id LIMIT 1;');
			$database_query->bindParam(':user_id', $this->id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return $row_target;
			}
			
			return null;
		}
		
		public function admin_code_verify(array $codes) {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.admin_codes WHERE user_id = :user_id LIMIT 1;');
			$database_query->bindParam(':user_id', $this->id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				if (
					md5('{GAPI2022}__&a__' . $codes[0]) === $row_target['alpha'] &&
					md5('{GAPI2022}__&b__' . $codes[1]) === $row_target['beta'] &&
					md5('{GAPI2022}__&g__' . $codes[2]) === $row_target['gamma']
				) {
					return true;
				}
			}
			
			return false;
		}
		
		public function is_logged($method = 0) {
			$self_class = get_called_class();
			
			if ($method == 0) {
				if (isset($_COOKIE['_uid']) && isset($_COOKIE['_token'])) {
					$user_id = (is_numeric($_COOKIE['_uid'])) ? $_COOKIE['_uid'] : 0;
					$user_token = $_COOKIE['_token'];
					
					if ($user_id != 0) {
						if ($self_class::exists($user_id, $user_token)) {
							return true;
						}
					}
				}
			}
			
			if ($method == 1) {
				if ($this->is_logged() && isset($_COOKIE['_atoken'])) {
					$user_id = (is_numeric($_COOKIE['_uid'])) ? $_COOKIE['_uid'] : 0;
					$admin_token = $_COOKIE['_atoken'];
					
					if ($user_id != 0) {
						$user_session = new UserSession($_COOKIE['_uid'], 1);
						if (isset($user_session->token)) {
							if ($user_session->token_verify($_COOKIE['_atoken'])) {
								return true;
							}
						}
					}
				}
			}
			
			return false;
		}
		
		public function update_active_date() {
			$date_now = gmdate("Y-m-d H:i:s", time());
			
			$database_query = $GLOBALS['database']->connect->prepare('UPDATE public.users SET last_active_datetime=? WHERE id=?;');
			$database_query->bindParam(1, $date_now, \PDO::PARAM_STR);
			$database_query->bindParam(2, $this->id, \PDO::PARAM_INT);
			$database_query->execute();
		}
		
		private function get_data() {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE id=? LIMIT 1;');
			$database_query->bindParam(1, $this->id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return $row_target;
			}
			
			return false;
		}
		
		public function remove() {
			$database = new Database();
			$database_query = $database->connect->prepare('DELETE FROM public.users WHERE id = :id;');
			$database_query->bindParam(':id', $this->id, \PDO::PARAM_INT);
			$execute = $database_query->execute();
			
			if ($execute) {
				return true;
			}
			
			return false;
		}
		
		public static function is_activated() {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE login = :login LIMIT 1;');
			$database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
			$database_query->execute();
		}
		
		public static function get_by_id(int $user_id) {
			$self_class = get_called_class();
			
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE id=? LIMIT 1;');
			$database_query->bindParam(1, $user_login, \PDO::PARAM_INT);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return new $self_class($row_target['id']);
			}
			
			return false;
		}
		
		public static function get_by_login(string $user_login) {
			$self_class = get_called_class();
			
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE login=? LIMIT 1;');
			$database_query->bindParam(1, $user_login, \PDO::PARAM_STR);
			$database_query->execute();
			
			$row_target = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($row_target) {
				return new $self_class($row_target['id']);
			}
			
			return false;
		}
		
		public static function exists_by_id(int $user_id) {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE id = :id LIMIT 1;');
			$database_query->bindParam(':id', $user_id, \PDO::PARAM_INT);
			$database_query->execute();
			
			$result = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($result) {
				return true;
			}
			
			return false;
		}
		
		public static function exists_by_login(string $user_login) {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE login = :login LIMIT 1;');
			$database_query->bindParam(':login', $user_login, \PDO::PARAM_STR);
			$database_query->execute();
			
			$result = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($result) {
				return true;
			}
			
			return false;
		}
		
		public static function exists_by_email(string $user_email) {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE email = :email LIMIT 1;');
			$database_query->bindParam(':email', $user_email, \PDO::PARAM_STR);
			$database_query->execute();
			
			$result = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($result) {
				return true;
			}
			
			return false;
		}
		
		public static function exists(int $user_id, string $user_token) {
			$database = new Database();
			$database_query = $database->connect->prepare('SELECT * FROM public.users WHERE id=? AND token=? LIMIT 1;');
			$database_query->bindParam(1, $user_id, \PDO::PARAM_INT);
			$database_query->bindParam(2, $user_token, \PDO::PARAM_STR);
			$database_query->execute();
			
			$result = $database_query->fetch(\PDO::FETCH_ASSOC);
			if ($result) {
				return true;
			}
			
			return false;
		}
		
		public static function create(array $user_data) {
			$self_class = get_called_class();
			
			$user_salt = self::gen_salt();

			$database = new Database();
			$database_query = $database->connect->prepare('INSERT INTO public.users (login,email,hash,name,surname,patronymic,birthdate,salt) VALUES (:login,:email,:hash,:name,:surname,:patronymic,:birthdate,:salt) RETURNING id;');
			$database_query->bindParam(':login', $user_data['login'], \PDO::PARAM_STR);
			$database_query->bindParam(':email', $user_data['email'], \PDO::PARAM_STR);
			$database_query->bindParam(':hash', $user_data['hash'], \PDO::PARAM_STR);
			$database_query->bindParam(':name', $user_data['name'], \PDO::PARAM_STR);
			$database_query->bindParam(':surname', $user_data['surname'], \PDO::PARAM_STR);
			$database_query->bindParam(':patronymic', $user_data['patronymic'], \PDO::PARAM_STR);
			$database_query->bindParam(':birthdate', $user_data['birthdate'], \PDO::PARAM_STR);
			$database_query->bindParam(':salt', $user_salt, \PDO::PARAM_STR);
			$execute = $database_query->execute();
			
			$result = $database_query->fetch(\PDO::FETCH_ASSOC);
			return new $self_class($result['id']);
		}
	}
}

?>