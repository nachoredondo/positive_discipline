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
	private $id;
	private $id_user;
	private $title;
	private $description;
	private $consequences;
	private $img_consequences;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->id_user($data['id_user']);
			$this->title($data['title']);
			$this->description($data['description']);
			$this->consequences($data['consequences']);
			$this->img_consequences($data['img_consequences']);
		} else {
			$this->id_user = '';
			$this->title = '';
			$this->description = '';
			$this->consequences = '';
			$this->img_consequences = '';
		}

	}

	public static function insert_rule($id_user, $title, $description, $consequences, $fileName) {
		$sql = "INSERT INTO `".self::TABLE."`
				(id_user, title, description, consequences, img_consequences)
				VALUES ('$id_user', '$title', '$description', '$consequences', '$fileName')";
		$res = self::query($sql);
		return $res;
	}

	private static function get_rule(string $id) : ?Rule {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `id` = '$id'");
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$rule = new Rule($data);
		return $rule;
	}

	public function id() : int {
		return $this->id;
	}

	public function id_user(?bool $id_user = null) : int {
		if (isset($id_user)) {
			$this->id_user = $id_user;
		}
		return $this->id_user;
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
		// print($sql);
		// exit();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
