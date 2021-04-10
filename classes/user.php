<?php
require_once("controller.php");

class User {
	private const TABLE = 'users';
	private const TABLE_TUTORS = 'tutors';
	private const TABLE_MEETING = 'meeting';
	private const TABLE_STOP = 'stop';
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

	public static function insert_user($type, $user=null, $email=null, $name=null, $surnames=null, $password=null, $password_confirm=null, $tutor=null, $age = null, $image = null) {

		if (!self::validate_user_adult($user)) {
			throw new InvalidArgumentException('Usuario no válido');
		}

		$educator = self::educator($type);

		if ($educator) {
			if (!self::validate_string_with_especial_characters($name)) {
				throw new InvalidArgumentException('Nombre no válido');
			}
			if (!self::validate_string_with_especial_characters($surnames)) {
				throw new InvalidArgumentException('Apellidos no válidos');
			}
			if (!self::validate_email($email)) {
				throw new InvalidArgumentException('Correo no válido');
			}

			$result = self::get_user('user', $user);
			if ($result) {
				throw new InvalidArgumentException('El usuario '. $user .' ya existe');
			}
			$result = self::get_user('email', $email);
			if ($result) {
				throw new InvalidArgumentException('El correo '. $user .' ya está registrado');
			}

			$pwd = self::hash($password);

			$sql = "INSERT INTO `".self::TABLE."`
					(name, surnames, user, email, password, educator, image, age)
					VALUES ('$name', '$surnames', '$user', '$email', '$pwd', '$educator', '$image', $age)";
			$res = self::query($sql);

			$result = self::get_user('user', $user);
			if ($result) {
				$id_tutor = $result->id();
				$sql =  "INSERT INTO `".self::TABLE_STOP."` (`id_user`, `type`, `name`, `link`, `position`) VALUES
						('$id_tutor', 'youtube', 'Relax', '1ZYbU82GVz4', '1')";
				$res = self::query($sql);
			}
		} else {
			$user_child = $tutor . "_" . $image;

			$result = self::get_user('user', $tutor);
			if (!$result) {
				throw new InvalidArgumentException('El usuario tutor '. $user .' no existe');
			} else {
				$id_tutor = $result->id;
			}

			$result = self::get_user('user', $user_child);
			if ($result) {
				throw new InvalidArgumentException('La foto ya está escogida');
			}
			$user = $user_child;
			$password = $image;

			$sql = "INSERT INTO `".self::TABLE."`
					(name, surnames, user, email, password, educator, image, age)
					VALUES ('$name', '$surnames', '$user', '$email', '$password', '$educator', '$image', $age)";
			$res = self::query($sql);

			$result = self::get_user('user', $user_child);
			if ($result) {
				$id_child = $result->id();
				$sql = "INSERT INTO `".self::TABLE_TUTORS."`
						(parent, child)
						VALUES ('$id_tutor', '$id_child')";
				$res = self::query($sql);

				// insert default wheel options
				$sql =  "INSERT INTO `wheel` (`id_user`, `name`, `image`) VALUES
						('$id_child', 'Contar hasta 10 y respirar', 'count.jpg'),
						('$id_child', 'Relajarse', 'relax.png'),
						('$id_child', 'Dibujar sentimientos', 'draw.jpg'),
						('$id_child', 'Escuchar música', 'listen_music.png'),
						('$id_child', 'Hablar sentimientos', 'speak_feelings.png'),
						('$id_child', 'Estar sol@ para pensar', 'think.png')";
				$res = self::query($sql);
			}
		}

		return $res;
	}

	public static function get_parent(string $id_user) {
		$sql = "SELECT parent
				from `tutors`
				WHERE `child` = '$id_user'";
		$result = self::query($sql);
		return $result->fetch(PDO::FETCH_ASSOC)['parent'];
	}

	public static function get_user_from_user(string $user) {
		if (!self::validate_user($user))
			throw new InvalidArgumentException('Usuario no válido');
		return self::get_user('user', $user);
	}

	public static function get_user_from_email(string $email) {
		// if (!self::validate_email($email) && $email != " ")
		// 	throw new InvalidArgumentException('Correo no válido');
		$sql = "SELECT * FROM `".self::TABLE."` WHERE email = '$email'";
		$result = self::query($sql);
		if (!$result){
			throw new InvalidArgumentException("Correo '$email' no existe");
		} else if ($result->rowCount() !== 1) {
			throw new InvalidArgumentException("Correo '$email' no existe");
		}
		return self::get_user('email', $email);
	}

	public static function get_user_from_tutor_img($tutor, $image){
		if (!self::validate_user_adult($tutor))
			throw new InvalidArgumentException('Tutor no válido');
		$user_child = $tutor . "_" . $image;

		$result = self::get_user('user', $user_child);
		if (!$result) {
			throw new InvalidArgumentException("El usuario con tutor '$tutor' e imagen '$img' no existe");
		}
		return $result;
	}

	public static function get_user_from_id(int $id) {
		return self::get_user('id', strval($id));
	}

	public static function get_responsable(string $user) {
		$sql = "SELECT *
				from users
				WHERE user = '$user'";
		$result = self::query($sql);
		$data = $result->fetch(PDO::FETCH_ASSOC);


		if ($data['educator'] == "0") {
			$responsable = array($data);
			$id_user = $data['id'];
			$sql = "SELECT user.*
					FROM `tutors` as tutor
					INNER JOIN `users` as user
					ON tutor.parent = user.id
					WHERE tutor.child = '$id_user'";
			$result = self::query($sql);
			$data = $result->fetch(PDO::FETCH_ASSOC);
			$responsable = array($data);
		} else {
			$responsable = array($data);
		}

		$id_educator = $responsable[0]['id'];
		$sql = "SELECT user.* FROM `tutors` as tutor
				INNER JOIN `users` as user
				ON tutor.child = user.id
				WHERE tutor.parent = '$id_educator'
					AND user.age > 9";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		foreach ($data as $key => $value) {
			array_push($responsable, $value);
		}
		return $responsable;
	}

	private static function get_user(string $id, string $value) {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `$id` = '$value'");
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

	public function type(?bool $type = null) : bool {
		if (isset($type)) {
			$this->type = $type;
		}
		return $this->type;
	}

	public function user(?string $user = null) : string {
		if (isset($user)) {
			if (!self::validate_user($user))
				throw new InvalidArgumentException('Usuario no válido');
			$this->user = $user;
		}
		return $this->user;
	}

	public function email(?string $email = null) : ?string {
		if (isset($email)) {
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

	public function update_user_adult($id, $user, $email, $name, $surnames) {
		if (!self::validate_user_adult($user)) {
			throw new InvalidArgumentException('Usuario no válido');
		}
		if (!self::validate_string_with_especial_characters($name)) {
			throw new InvalidArgumentException('Nombre no válido');
		}
		if (!self::validate_string_with_especial_characters($surnames)) {
			throw new InvalidArgumentException('Apellidos no válidos');
		}
		if (!self::validate_email($email)) {
			throw new InvalidArgumentException('Correo no válido');
		}

		$result_user = User::get_user_from_id($id);
		if ($result_user->user() != $name){
			$result = self::get_user('user', $user);
			if ($result) {
				throw new InvalidArgumentException('El usuario '. $user .' ya existe');
			}
		}
		$result_email = User::get_user_from_email($email);
		if ($result_email->email() != $email){
			$result = self::get_user('email', $email);
			if ($result) {
				throw new InvalidArgumentException('El correo '. $user .' ya está registrado');
			}
		}

		$sql = "UPDATE `".self::TABLE."`
				SET `user` = '$user',
					`email` = '$email',
					`name` = '$name',
					`surnames` = '$surnames'
				WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public function update_user_child($id, $tutor, $name, $age, $image) {
		$user_child = $tutor . "_" . $image;

		$result_user = User::get_user_from_id($id);
		if ($result_user->user() != $user_child){
			$result = self::get_user('user', $user_child);
			if ($result) {
				throw new InvalidArgumentException('La foto ya está escogida');
			}
		}

		$sql = "UPDATE `".self::TABLE."`
				SET `user` = '$user_child',
					`name` = '$name',
					`age` = '$age',
					`image` = '$image',
					`password` = '$password'
				WHERE `id` = '$id'";
		$res = self::query($sql);
		return $res;
	}

	public static function delete_tutor($id) {
		$sql = "DELETE
					FROM `".self::TABLE."`
					WHERE `id` = '$id'";
		$res = self::query($sql);

		$sql = "DELETE
			FROM `".self::TABLE_TUTORS."`
			WHERE `child` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public function delete_child($id) {
		$sql = "DELETE
			FROM `".self::TABLE."`
			WHERE `id` = '$id'";
		$res = self::query($sql);

		$sql = "DELETE
			FROM `".self::TABLE_TUTORS."`
			WHERE `child` = '$id'";
		$res = self::query($sql);

		$sql = "UPDATE `".self::TABLE_MEETING."`
					SET `responsable` = NULL
					WHERE `responsable` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function password_update(int $id, string $password, string $password_confirm) {
		// The new password cannot be less than 8 characters
		if (strlen($password) < 8)
			throw new InvalidArgumentException("El tamaño mínimo de la contraseña es de 8 caracteres");

		if ($password != $password_confirm)
			throw new InvalidArgumentException('Las contraseñas no coinciden');

		$hash = self::hash($password);
		$sql = "UPDATE `".self::TABLE."`
					SET `password` = '$hash'
					WHERE id = '$id'";

		$result = self::query($sql);

		return $result;
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

	public static function validate_user(string $user) {
		return preg_match('/^[\w.\- ]+$/', $user);
	}

	public static function validate_user_adult(string $user) {
		return preg_match('/^[a-zA-Z0-9ñÑ\-. ]+$/', $user);
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
		$conn = Controller::get_global_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
