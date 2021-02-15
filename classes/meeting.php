<?php
require_once("controller.php");

class Meeting {
	private const TABLE = 'meeting';
	private $id;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->title($data['title']);
			$this->description($data['description']);
			$this->topics($data['topics']);
			$this->date($data['date']);
			$this->start($data['start']);
			$this->end($data['end']);
			$this->responsable($data['responsable']);
			$this->img_act($data['img_act']);
		} else {
			$this->title = '';
			$this->description = '';
			$this->topics = '';
			$this->date = '';
			$this->start = '';
			$this->end = '';
			$this->responsable = '';
			$this->img_act = '';
		}
	}

	public static function insert_meeting($title, $description, $topics, $date, $start, $end, $responsable, $img_act) {
		$date = inverse_date($date);
		$sql = "INSERT INTO `".self::TABLE."`
				(title, description, topics, date, start, end, responsable, img_act)
				VALUES ('$title', '$description', '$topics', '$date', '$start', '$end', '$responsable', '$img_act')";
		$res = self::query($sql);
		return $res;
	}

	public static function update_meeting($id, $title, $description, $topics, $date, $start, $end, $responsable, $img_act) {
		$date = inverse_date($date);
		$sql = "UPDATE `".self::TABLE."`
			SET `title` = '$title',
				`description` = '$description',
				`topics` = '$topics',
				`date` = '$date',
				`start` = '$start',
				`end` = '$end',
				`responsable` = '$responsable',
				`img_act` = '$img_act'
			WHERE `id` = '$id'";
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

	public function topics(?string $topics = null) : ?string {
		if (isset($topics)) {
			$this->topics = $topics;
		}
		return $this->topics;
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

	public function responsable(?string $responsable = null) : int {
		if (isset($responsable)) {
			$this->responsable = $responsable;
		}
		return $this->responsable;
	}

	public function img_act(?string $img_act = null) : ?string {
		if (isset($img_act)) {
			$this->img_act = $img_act;
		}
		return $this->img_act;
	}

	private static function query($sql, ...$vars) {
		$conn = Controller::get_global_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
