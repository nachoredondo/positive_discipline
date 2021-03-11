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


class Option_Stop {
	private const TABLE = 'stop';
	private $id;

	function __construct(?array $data = null) {
		$this->id = 0;
		$this->id_user = 0;

		if (isset($data)) {
			$this->id($data['id']);
			$this->id_user($data['id_user']);
			$this->type($data['type']);
			$this->name($data['name']);
			$this->link($data['link']);
			$this->position($data['position']);
		} else {
			$this->type = '';
			$this->name = '';
			$this->link = '';
			$this->position = '';
		}

	}

	public static function insert_stop($id_user, $type, $name, $link, $position) {
		$sql = "INSERT INTO `".self::TABLE."`
				(id_user, type, name, link, position)
				VALUES ('$id_user', '$type', '$name', '$link', '$position')";
		$res = self::query($sql);

		return $res;
	}

	public static function update_stop($id, $id_user, $type, $name, $link, $position, $position_old) {
		if ($position != $position_old) {
			$sql = "SELECT *
					FROM `".self::TABLE."`
					WHERE `id_user` = '$id_user'
						and `position` = $position_old";
			$result = self::query($sql);
			if ($result){
				$sql = "UPDATE `".self::TABLE."`
					SET `position` = '$position_old'
					WHERE `id_user` = '$id_user'
						and `position` = $position";
				$res = self::query($sql);
			}
		}

		$sql = "UPDATE `".self::TABLE."`
			SET `type` = '$type',
				`name` = '$name',
				`link` = '$link',
				`position` = '$position'
			WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function delete_stop($id, $id_user, $position_old) {
		$sql = "SELECT *
					FROM `".self::TABLE."`
					WHERE `id_user` = '$id_user'
						and `position` > $position_old";
		$result = self::query($sql);
		if ($result){
			$data = $result->fetchAll();
			$stop = [];
			foreach ($data as $key => $value) {
				$new_position = $value['position'] - 1;
				$position = $value['position'];
				$sql = "UPDATE `".self::TABLE."`
					SET `position` = '$new_position'
					WHERE `id_user` = '$id_user'
						and `position` = '$position'";
				$res = self::query($sql);
			}
		}

		$sql = "DELETE
			FROM `".self::TABLE."`
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

	public static function get_youtube_option_by_iduser(string $id_user) : ?Option_Stop {
		return self::get_option_by_iduser($id_user, 'youtube');
	}

	public static function get_image_option_by_iduser(string $id_user) : ?Option_Stop {
		return self::get_option_by_iduser($id_user, 'image');
	}

	public static function get_audio_option_by_iduser(string $id_user) : ?Option_Stop {
		return self::get_option_by_iduser($id_user, 'audio');
	}

	public static function get_video_option_by_iduser(string $id_user) : ?Option_Stop {
		return self::get_option_by_iduser($id_user, 'video');
	}

	private static function get_option_by_iduser(string $id_user, string $option) : ?Option_Stop {
		$sql = "SELECT *
				FROM `".self::TABLE."`
				WHERE `id_user` = '$id_user'
					and `type` = '$option'";
		$result = self::query($sql);
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$option_stop = new Option_Stop($data);
		return $option_stop;
	}

	public static function get_stop_by_iduser(string $id_user) : ?array {
		$sql = "SELECT *
				FROM `".self::TABLE."`
				WHERE `id_user` = '$id_user'
				ORDER BY `position`";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		$stop = [];
		foreach ($data as $key => $value) {
			$stop[] = new Option_Stop($value);
		}
		return $stop;
	}

	public function id(?string $id = null) : string {
		if (isset($id)) {
			$this->id = $id;
		}
		return $this->id;
	}

	public function id_user(?int $id_user = null) : string {
		if (isset($id_user)) {
			$this->id_user = $id_user;
		}
		return $this->id_user;
	}

	public function type(?string $type = null) : string {
		$this->type = $type;
		return $this->type;
	}

	public function name(?string $name = null) : ?string {
		if (isset($name)) {
			$this->name = $name;
		}
		return $this->name;
	}

	public function link(?string $link = null) : ?string {
		if (isset($link)) {
			$this->link = $link;
		}
		return $this->link;
	}

	public function position(?string $position = null) : ?string {
		if (isset($position)) {
			$this->position = $position;
		}
		return $this->position;
	}

	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
