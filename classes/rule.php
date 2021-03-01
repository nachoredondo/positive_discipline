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


class Rule {
	private const TABLE = 'rules';
	private const TABLE_CHILD = 'rules_children';
	private $id;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->id_educator($data['id_educator']);
			$this->title($data['title']);
			$this->description($data['description']);
			$this->consequences($data['consequences']);
			$this->img_consequences($data['img_consequences']);
		} else {
			$this->id_educator = '';
			$this->title = '';
			$this->description = '';
			$this->consequences = '';
			$this->img_consequences = '';
		}

	}

	public static function insert_rule($id_educator, $id_user_child, $title, $description, $consequences, $fileName) {
		$sql = "INSERT INTO `".self::TABLE."`
				(id_educator, title, description, consequences, img_consequences)
				VALUES ('$id_educator', '$title', '$description', '$consequences', '$fileName')";
		$res = self::query($sql);

		$sql = "SELECT MAX(id) AS id FROM `".self::TABLE."`";
		$res = self::query($sql);
		$id_rule = $res->fetch(PDO::FETCH_ASSOC)['id'];

		foreach ($id_user_child as $key => $value) {
			$sql = "INSERT INTO `".self::TABLE_CHILD."`
				(id_rule, id_user)
				VALUES ('$id_rule', '$value')";
			$res = self::query($sql);
		}
		return $res;
	}

	public static function update_rule($id, $id_educator, $id_user_child, $title, $description, $consequences, $fileName) {
		$sql = "UPDATE `".self::TABLE."`
			SET `id_educator` = '$id_educator',
				`title` = '$title',
				`description` = '$description',
				`consequences` = '$consequences',
				`img_consequences` = '$fileName'
			WHERE `id` = '$id'";
		$res = self::query($sql);

		// delete rule to child
		$children = self::get_children($id);
		foreach ($children as $key_children => $value_children) {
			$search = false;
			foreach ($id_user_child as $key => $value) {
				if ($value_children['id_user'] == $value) {
					$search = true;
					break;
				}
			}
			if (!$search) {
				$val = $value_children['id_user'];
				$sql = "DELETE FROM `".self::TABLE_CHILD."` WHERE `id_user` = '$val' AND `id_rule`= '$id'";
				$result = self::query($sql);
				if (!$result){
					return null;
				}
			}
		}

		// asign rule to child
		foreach ($id_user_child as $key => $value) {
			$sql = "SELECT * FROM `".self::TABLE_CHILD."` WHERE `id_user` = '$value'  AND `id_rule`= '$id'";
			$result = self::query($sql);
			if (!$result){
				return null;
			}
			if ($result->rowCount() !== 1){
				$sql = "INSERT INTO `".self::TABLE_CHILD."`
					(id_rule, id_user)
					VALUES ('$id', '$value')";
				$res = self::query($sql);
			}
		}

		return $res;
	}

	public static function delete_rule($id) {
		$sql = "DELETE
			FROM `".self::TABLE."`
			WHERE `id` = '$id'";
		$res = self::query($sql);

		$sql = "DELETE
			FROM `".self::TABLE_CHILD."`
			WHERE `id_rule` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function get_rule(string $id) : ?Rule {
		$sql = "SELECT * FROM `".self::TABLE."` WHERE `id` = '$id'";
		$result = self::query($sql);
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$rule = new Rule($data);
		return $rule;
	}

	public static function get_children(string $id) {
		$sql = "SELECT id_user FROM `".self::TABLE_CHILD."` WHERE `id_rule` = '$id'";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		return $data;
	}

	public function id() : int {
		return $this->id;
	}

	public function id_educator(?int $id_educator = null) : int {
		if (isset($id_educator)) {
			$this->id_educator = $id_educator;
		}
		return $this->id_educator;
	}

	public function title(?string $title = null) : string {
		$this->title = $title;
		return $this->title;
	}

	public function description(?string $description = null) : ?string {
		if (isset($description)) {
			$this->description = $description;
		}
		return $this->description;
	}

	public function consequences(?string $consequences = null) : ?string {
		if (isset($consequences)) {
			$this->consequences = $consequences;
		}
		return strval($this->consequences);
	}

	public function img_consequences(?string $img_consequences = null) : ?string {
		if (isset($img_consequences)) {
			$this->img_consequences = $img_consequences;
		}
		return $this->img_consequences;
	}

	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
