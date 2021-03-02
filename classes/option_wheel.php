<?php
require_once("controller.php");

class Option_Wheel {
	private const TABLE = 'wheel';
	private $id;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->id_user($data['id_user']);
			$this->name($data['name']);
			$this->image($data['image']);
		} else {
			$this->id_user = '';
			$this->name = '';
			$this->image = '';
		}
	}

	public static function insert_option($id_user, $name, $image) {
		$sql = "INSERT INTO `".self::TABLE."`
				(id_user, name, image)
				VALUES ('$id_user', '$name', '$image')";
		$res = self::query($sql);
		return $res;
	}

	public static function update_option($id, $name, $image) {
		$sql = "UPDATE `".self::TABLE."`
			SET `name` = '$name',
				`image` = '$image'
			WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function get_option_by_id(string $id) : ?Option_Wheel {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `id` = '$id'");
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$option = new Option_Wheel($data);
		return $option;
	}

	public static function get_wheel_by_iduser(string $id_user) : ?array {
		$sql = "SELECT * FROM `".self::TABLE."` WHERE `id_user` = '$id_user'";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		$wheel = [];
		foreach ($data as $key => $value) {
			$wheel[] = new Option_Wheel($value);
		}
		return $wheel;
	}

	public static function delete_option($id) {
		$sql = "DELETE
			FROM `".self::TABLE."`
			WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public function id() : int {
		return $this->id;
	}

	public function id_user(?int $id_user = null) : int {
		$this->id_user = $id_user;
		return $this->id_user;
	}

	public function name(?string $name = null) : ?string {
		if (isset($name)) {
			$this->name = $name;
		}
		return $this->name;
	}

	public function image(?string $image = null) : ?string {
		if (isset($image)) {
			$this->image = $image;
		}
		return $this->image;
	}

	private static function query($sql, ...$vars) {
		$conn = Controller::get_global_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
