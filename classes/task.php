<?php
require_once("controller.php");

class Task {
	private const TABLE = 'task';
	private $id;

	function __construct(?array $data = null) {
		$this->id = 0;

		if (isset($data)) {
			$this->parent($data['parent']);
			$this->name($data['name']);
			$this->description($data['description']);
			$this->date_start($data['date_start']);
			$this->date_end($data['date_end']);
			$this->date_modification($data['date_modification']);
			$this->time_start($data['time_start']);
			$this->time_end($data['time_end']);
			$this->daily($data['daily']);
			$this->weekly($data['weekly']);
			$this->monthly($data['monthly']);
			$this->monday($data['monday']);
			$this->thursday($data['thursday']);
			$this->wenesday($data['wenesday']);
			$this->tuesday($data['tuesday']);
			$this->friday($data['friday']);
			$this->saturday($data['saturday']);
			$this->sunday($data['sunday']);
		} else {
			$this->parent = '';
			$this->name = '';
			$this->description = '';
			$this->date_start = '';
			$this->date_end = '';
			$this->date_modification = '';
			$this->time_start = '';
			$this->time_end = '';
			$this->daily = '';
			$this->weekly = '';
			$this->monthly = '';
			$this->monday = '';
			$this->thursday = '';
			$this->wenesday = '';
			$this->tuesday = '';
			$this->friday = '';
			$this->saturday = '';
			$this->sunday = '';
		}
	}

	public static function insert_task($title, $parent, $name, $description, $date_start, $date_end, $date_modification, $time_start, $time_end, $frecuency, $children) {
		$date_start = inverse_date($date_start);
		$date_end = inverse_date($date_end);
		$date_modification = inverse_date($date_modification);
		var_dump($frecuency);
		var_dump($children);
		print("crear");
		exit();
		$sql = "INSERT INTO `".self::TABLE."`
				(title, parent, name, description, date_start, date_end, date_modification, time_start, time_end, daily, weekly, monthly, monday, thursday, wenesday, tuesday, friday, saturday, sunday)
				VALUES ('$title', '$parent', '$name', '$description', '$date_start', '$date_end', '$date_modification', '$time_start', '$time_end', '$frecuency', '$children')";
		$res = self::query($sql);
		return $res;
	}

	public static function update_task($id, $title, $parent, $name, $description, $date_start, $date_end, $date_modification, $time_start, $time_end, $frecuency, $children) {
		$date_start = inverse_date($date_start);
		$date_end = inverse_date($date_end);
		$date_modification = inverse_date($date_modification);
		var_dump($frecuency);
		var_dump($children);
		print("actualizar");
		exit();
		$sql = "UPDATE `".self::TABLE."`
			SET `title` = '$title',
				`parent` = '$parent',
				`name` = '$name',
				`description` = '$description',
				`date_start` = '$date_start',
				`date_end` = '$date_end',
				`date_modification` = '$date_modification',
				`time_start` = '$time_start',
				`time_end` = '$time_end',
				`daily` = '$daily',
				`weekly` = '$weekly',
				`monthly` = '$monthly',
				`monday` = '$monday',
				`thursday` = '$thursday',
				`wenesday` = '$wenesday',
				`tuesday` = '$tuesday',
				`friday` = '$friday',
				`saturday` = '$saturday',
				`sunday` = '$sunday'
			WHERE `id` = '$id'";
		$res = self::query($sql);

		return $res;
	}

	public static function delete_task($id) {
		$sql = "DELETE
			FROM `".self::TABLE."`
			WHERE `id` = '$id'";
		$res = self::query($sql);
	}

	public static function get_task_by_id(string $id) : ?Task {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `id` = '$id'");
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$task = new Task($data);
		return $task;
	}

	public function id() : int {
		return $this->id;
	}

	public function parent(?string $parent = null) : ?string {
		if (isset($parent)) {
			$this->parent = $parent;
		}
		return $this->parent;
	}

	public function name(?string $name = null) : ?string {
		if (isset($name)) {
			$this->name = $name;
		}
		return $this->name;
	}

	public function description(?string $description = null) : ?string {
		if (isset($description)) {
			$this->description = $description;
		}
		return $this->description;
	}

	public function date_start(?string $date_start = null) : ?string {
		if (isset($date_start)) {
			$this->date_start = $date_start;
		}
		return $this->date_start;
	}

	public function date_end(?string $date_end = null) : ?string {
		if (isset($date_end)) {
			$this->date_end = $date_end;
		}
		return $this->date_end;
	}

	public function date_modification(?string $date_end = null) : ?string {
		if (isset($date_end)) {
			$this->date_end = $date_end;
		}
		return $this->date_end;
	}

	public function time_start(?string $time_start = null) : ?string {
		if (isset($time_start)) {
			$this->time_start = $time_start;
		}
		return $this->time_start;
	}

	public function time_end(?string $time_end = null) : ?string {
		if (isset($time_end)) {
			$this->time_end = $time_end;
		}
		return $this->time_end;
	}

	public function daily(?string $daily = null) : ?string {
		if (isset($daily)) {
			$this->daily = $daily;
		}
		return $this->daily;
	}

	public function weekly(?string $weekly = null) : ?string {
		if (isset($weekly)) {
			$this->weekly = $weekly;
		}
		return strval($this->weekly);
	}

	public function monthly(?string $monthly = null) : ?string {
		if (isset($monthly)) {
			$this->monthly = $monthly;
		}
		return strval($this->monthly);
	}

	public function monday(?string $monday = null) : ?string {
		if (isset($monday)) {
			$this->monday = $monday;
		}
		return strval($this->monday);
	}

	public function thursday(?string $thursday = null) {
		if (isset($thursday)) {
			$this->thursday = $thursday;
		}
		return $this->thursday;
	}

	public function wenesday(?string $wenesday = null) : ?string {
		if (isset($wenesday)) {
			$this->wenesday = $wenesday;
		}
		return $this->wenesday;
	}

	public function tuesday(?string $tuesday = null) : ?string {
		if (isset($tuesday)) {
			$this->tuesday = $tuesday;
		}
		return strval($this->tuesday);
	}

	public function friday(?string $friday = null) : ?string {
		if (isset($friday)) {
			$this->friday = $friday;
		}
		return strval($this->friday);
	}

	public function saturday(?string $saturday = null) {
		if (isset($saturday)) {
			$this->saturday = $saturday;
		}
		return $this->saturday;
	}

	public function sunday(?string $sunday = null) : ?string {
		if (isset($sunday)) {
			$this->sunday = $sunday;
		}
		return $this->sunday;
	}

	private static function query($sql, ...$vars) {
		$conn = Controller::get_global_connection();
		$res = $conn->prepare($sql);
		$res->execute();
		return $res;
	}
}
