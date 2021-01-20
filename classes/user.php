<?php
require_once("controller.php");

/*
 * Get a connection to the MySQL.
 * $dbname: name of the database. Default app db if null.
 */
function get_db_connection($dbname = null) {
	$conn = Controller::get_global_connection();
	return $conn;
}


class User {
	private const TABLE = 'users';
	private $id;
	private $type;
	private $user;
	private $email;
	private $name;
	private $surnames;
	private $password;
	private $age;
	private $image;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->type($data['educator']);
			$this->user($data['user']);
			$this->email($data['email']);
			$this->name($data['name']);
			$this->surnames($data['surnames']);
			$this->password($data['password']);
			$this->age($data['age']);
			$this->image($data['image']);
		} else {
			$this->type = '';
			$this->user = '';
			$this->email = '';
			$this->name = '';
			$this->surnames = '';
			$this->password = '';
			$this->age = '';
			$this->image = '';
		}

	}

	public static function insert_user($type, $user, $email, $name, $surnames, $password, $password_confirm, $age = null, $image = null) {
		if (!self::validate_string_with_especial_characters($name)) {
			throw new InvalidArgumentException('Nombre no válido');
		}
		if (!self::validate_string_with_especial_characters($surnames)) {
			throw new InvalidArgumentException('Apellidos no válidos');
		}
		if (!self::validate_stringid($user)) {
			throw new InvalidArgumentException('Usuario no válido');
		}
		if (!self::validate_email($email)) {
			throw new InvalidArgumentException('Correo no válido');
		}

		$educator = self::educator($type);

		$result = self::get_user('user', $user);
		if ($result) {
			throw new InvalidArgumentException('El usuario '. $user .' ya existe');
		}

		$result = self::get_user('email', $email);
		if ($result) {
			throw new InvalidArgumentException('El usuario '. $user .' ya existe');
		}

		$pwd = self::hash($password);
		$sql = "INSERT INTO `".self::TABLE."`
					(name, surnames, user, email, password, educator, image, age)
					VALUES ('$name', '$surnames', '$user', '$email', '$pwd', '$educator', '$image', $age)";

		$res = self::query($sql);

		return $res;
	}


	public static function delete_user($user) {
		$sql = "DELETE
					FROM `".self::TABLE."`
					WHERE `user` = '$user'";

		$res = self::query($sql);
	}

	public static function get_user_from_user(string $user) {
		if (!self::validate_user($user))
			throw new InvalidArgumentException('Usuario no válido');
		return self::get_user('user', $user);
	}

	public static function get_user_from_email(string $email) {
		if (!self::validate_email($email))
			throw new InvalidArgumentException('Correo no válido');
		$sql = "SELECT * FROM `".self::TABLE."` WHERE email = '$email'";
		$result = self::query($sql);
		if (!$result){
			throw new InvalidArgumentException("Correo '$email' no existe");
		} else if ($result->rowCount() !== 1) {
			throw new InvalidArgumentException("Correo '$email' no existe");
		}
		$data = $result->fetch(PDO::FETCH_ASSOC);
		return self::get_user('email', $email);
	}

	public static function get_user_from_id(int $id) {
		return self::get_user('id', strval($id));
	}

	private static function get_user(string $key, string $value) : ?User {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `$key` = '$value'");
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}
		$data = $result->fetch(PDO::FETCH_ASSOC);
		$user = new User($data);
		$user->id = intval($data['id']);
		$user->password = $data['password']; // Set hash instead of hashing the hash
		return $user;
	}

	public function id() : int {
		return $this->id;
	}

	public function type(?string $type = null) : bool {
		if (isset($type)) {
			$this->type = $type;
		}
		return $this->type;
	}

	public function user(?string $user = null) : string {
		if (isset($user)) {
			if (!self::validate_stringid($user))
				throw new InvalidArgumentException('Usuario no válido');
			$this->user = $user;
		}
		return $this->user;
	}

	public function email(?string $email = null) : string {
		if (isset($email)) {
			if (!self::validate_email($email))
				throw new InvalidArgumentException('Correo no válido');
			$this->email = $email;
		}
		return $this->email;
	}

	public function name(?string $name = null) : string {
		if (isset($name)) {
			$this->name = $name;
		}
		return $this->name;
	}

	public function surnames(?string $surnames = null) : string {
		if (isset($surnames)) {
			$this->surnames = $surnames;
		}
		return $this->surnames;
	}

	public function password(string $newpass, ?string $oldpass = '') : void {
		if (!self::validate_password($newpass))
			throw new InvalidArgumentException('Contraseña no válida');

		// The password cannot be the same as the old one
		if ($this->password_verify($newpass))
			throw new InvalidArgumentException('Las contraseñas coinciden');

		$hash = self::hash($newpass);
		$this->password = $hash;
	}

	public function age(?string $age = null) : string {
		if (isset($age)) {
			$this->age = $age;
		}
		return strval($this->age);
	}

	public function image(?string $image = null) : string {
		if (isset($image)) {
			$this->image = $image;
		}
		return $this->image;
	}

	public function password_verify($password) {
		return password_verify($password, $this->password);
	}

	public function update_passwd($oldpass, $newpass) {
		if (!self::validate_password($newpass))
			throw new InvalidArgumentException("Contraseña incorrecta");

		// The password cannot be the same as the old one
		if ($this->password_verify($newpass))
			throw new InvalidArgumentException("La nueva contraseña no puede ser la misma que la anterior");

		// The new password cannot be less than 8 characters
		if (strlen($newpass) < 8)
			throw new InvalidArgumentException("El tamaño mínimo de la contraseña es de 8 caracteres");

		$hash = self::hash($newpass);
		$sql = "UPDATE `".self::TABLE."` SET password = '$hash' WHERE user = '".$this->user."'";
		self::query($sql);
	}


	public function fullname() : string {
		$name = $this->name;
		$surnames = $this->surnames;
		return empty($surnames) ? $name : $surnames . ', ' . $name;
	}

	// Centralize password hashing
	private static function hash(string $password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public static function validate_user(?string $user = '') {
		return preg_match('/^[\w]+$/', $use) ? $user : false;
	}

	public static function validate_email(string $email) : bool {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function validate_stringid(string $id) : bool {
		return preg_match('/^[\w\-]+$/', $id);
	}

	public static function validate_string_with_especial_characters(string $id) : bool {
		return preg_match('/^[a-zA-ZñÑäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙ\ ]+$/', $id);
	}

	private static function validate_password(string $passwd) : bool {
		return true;
	}

	private static function educator(string $type) : bool {
		if ($type == "adult") {
			return 1;
		} else {
			return 0;
		}
	}

	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
