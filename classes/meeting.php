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


class Meeting {
	private const TABLE = 'meeting';
	private $id;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->title($data['title']);
			$this->description($data['description']);
			$this->date($data['date']);
			$this->start($data['start']);
			$this->end($data['end']);
			$this->record($data['record']);
			$this->responsable($data['responsable']);
		} else {
			$this->title = '';
			$this->description = '';
			$this->date = '';
			$this->start = '';
			$this->end = '';
			$this->record = '';
			$this->responsable = '';
		}

	}

	public static function insert_meeting($title, $description, $date, $start, $end, $record, $responsable) {
		$sql = "INSERT INTO `".self::TABLE."`
				(title, description, date, start, end, record, responsable)
				VALUES ('$title', '$description', '$date', '$start', '$end', '$record', '$responsable'))";
		$res = self::query($sql);
		return $res;
	}

	public static function get_meeting_by_id(string $id) : ?Meeting {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `id` = '$id'");
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$meeting = new Meeting($data);
		return $meeting;
	}

	public function id() : int {
		return $this->id;
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

	public function date(?string $date = null) : ?string {
		if (isset($date)) {
			$this->date = $date;
		}
		return strval($this->date);
	}

	public function start(?string $start = null) : ?string {
		if (isset($start)) {
			$this->start = $start;
		}
		return strval($this->start);
	}

	public function end(?string $end = null) : ?string {
		if (isset($end)) {
			$this->end = $end;
		}
		return strval($this->end);
	}

	public function record(?string $record = null) : ?string {
		if (isset($record)) {
			$this->record = $record;
		}
		return $this->record;
	}

	public function responsable(?string $responsable = null) : int {
		if (isset($responsable)) {
			$this->responsable = $responsable;
		}
		return $this->responsable;
	}

	private static function query($sql, ...$vars) {
		$conn = get_db_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
