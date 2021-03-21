<?php
require_once("controller.php");

class Task {
	private const TABLE = 'task';
	private const TABLE_CHILD = 'task_children';
	private $id_task;

	function __construct(?array $data = null) {
		$this->id_task = 0;

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

	public static function insert_task($parent, $name, $description, $date_start, $date_end, $date_modification, $time_start, $time_end, $frecuency, $children, $position_children) {
		$date_start = inverse_date($date_start);
		$date_end = inverse_date($date_end);
		$date_modification = inverse_date($date_modification);
		$daily = $frecuency['daily'];
		$weekly = $frecuency['weekly'];
		$monthly = $frecuency['monthly'];
		$monday = $frecuency['monday'];
		$thursday = $frecuency['thursday'];
		$wenesday = $frecuency['wenesday'];
		$tuesday = $frecuency['tuesday'];
		$friday = $frecuency['friday'];
		$saturday = $frecuency['saturday'];
		$sunday = $frecuency['sunday'];

		$sql = "INSERT INTO `".self::TABLE."`
				(parent, name, description, date_start, date_end, date_modification, time_start, time_end, daily, weekly, monthly, monday, thursday, wenesday, tuesday, friday, saturday, sunday)
				VALUES ('$parent', '$name', '$description', '$date_start', '$date_end', '$date_modification', '$time_start', '$time_end', '$daily', '$weekly', '$monthly', '$monday', '$thursday', '$wenesday', '$tuesday', '$friday', '$saturday', '$sunday')";
		$res = self::query($sql);

		$sql = "SELECT MAX(id_task) AS id_task FROM `".self::TABLE."`";
		$res = self::query($sql);
		$id_task = $res->fetch(PDO::FETCH_ASSOC)['id_task'];

		$count = 0;
		foreach ($children as $key => $value) {
			$position = $position_children[$count];
			$sql = "INSERT INTO `".self::TABLE_CHILD."`
				(id_task, id_user, position)
				VALUES ('$id_task', '$value', '$position')";
			$res = self::query($sql);
			$count += 1;
		}
		return $res;
	}

	public static function update_task($id_task, $parent, $name, $description, $date_start, $date_end, $date_modification, $time_start, $time_end, $frecuency, $children, $position_children) {
		$date_start = inverse_date($date_start);
		$date_end = inverse_date($date_end);
		$date_modification = inverse_date($date_modification);
		$daily = $frecuency['daily'];
		$weekly = $frecuency['weekly'];
		$monthly = $frecuency['monthly'];
		$monday = $frecuency['monday'];
		$thursday = $frecuency['thursday'];
		$wenesday = $frecuency['wenesday'];
		$tuesday = $frecuency['tuesday'];
		$friday = $frecuency['friday'];
		$saturday = $frecuency['saturday'];
		$sunday = $frecuency['sunday'];

		$sql = "UPDATE `".self::TABLE."`
			SET `parent` = '$parent',
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
			WHERE `id_task` = '$id_task'";
		$res = self::query($sql);

		// delete task to child
		$children_saved = self::get_children($id_task);
		foreach ($children_saved as $key_children => $value_children) {
			$search = false;
			foreach ($children as $key => $value) {
				if ($value_children['id_user'] == $value) {
					$search = true;
					break;
				}
			}
			if (!$search) {
				$val = $value_children['id_user'];
				$sql = "DELETE FROM `".self::TABLE_CHILD."` WHERE `id_user` = '$val' AND `id_task`= '$id_task'";
				$result = self::query($sql);
				if (!$result){
					return null;
				}
			}
		}

		// asign task to child
		$count = 0;
		foreach ($children as $key => $value) {
			$sql = "SELECT * FROM `".self::TABLE_CHILD."` WHERE `id_user` = '$value'  AND `id_task`= '$id_task'";
			$result = self::query($sql);
			if (!$result){
				return null;
			}
			$position = $position_children[$count];
			if ($result->rowCount() !== 1){
				$sql = "INSERT INTO `".self::TABLE_CHILD."`
					(id_task, id_user, position)
					VALUES ('$id_task', '$value', '$position')";
				$res = self::query($sql);
			} else {
				$sql = "UPDATE `".self::TABLE_CHILD."`
					SET `position` = '$position'
					WHERE `id_task` = '$id_task'
						AND `id_user` = '$id_user'";
				$res = self::query($sql);
			}
			$count += 1;
		}
		return $res;
	}

	public static function delete_task($id_task) {
		$sql = "DELETE
			FROM `".self::TABLE."`
			WHERE `id_task` = '$id_task'";
		$res = self::query($sql);
	}

	public static function get_children(string $id_task) {
		$sql = "SELECT id_user FROM `".self::TABLE_CHILD."` WHERE `id_task` = '$id_task'";
		$result = self::query($sql);
		if (!$result){
			return null;
		}

		$data = $result->fetchAll();
		return $data;
	}

	public static function get_task_by_id(string $id_task) : ?Task {
		$result = self::query("SELECT * FROM `".self::TABLE."` WHERE `id_task` = '$id_task'");
		if (!$result){
			return null;
		} else if ($result->rowCount() !== 1) {
			return null;
		}

		$data = $result->fetch(PDO::FETCH_ASSOC);
		$task = new Task($data);
		return $task;
	}

	public function id_task() : int {
		return $this->id_task;
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
