<?php

class Config implements \ArrayAccess {
	private const DB_KEYS_WHITELIST = ['host','port','user','password','database'];
	private static $DEFAULT_CONFIG_PATH = './config.ini';
	private const MAIL_KEYS_WHITELIST = ['host', 'port', 'src', 'password'];

	private static $app_config;

	private $config;
	private $dbconfig;

	public function __construct(string $path) {
		if (!$this->config = self::parse_file($path)) {
			throw new Exception($path);
		}
	}

	public static function set_default_config_location(string $path) : void {
		self::$DEFAULT_CONFIG_PATH = $path;
	}

	public static function get_default() : self {
		if (!self::$app_config)
			self::$app_config = new self(self::$DEFAULT_CONFIG_PATH);
		return self::$app_config;
	}

	public function get_db_config() : ?array {
		if (isset($this->dbconfig))
			return $this->dbconfig;

		if (!isset($this->config['database']))
			return null;

		$dbconf = $this->config['database'];
		$ret = [];
		foreach (self::DB_KEYS_WHITELIST as $key) {
			if (isset($dbconf[$key])) {
				$ret[$key] = $dbconf[$key];
			}
		}

		$ret['database'] = preg_replace('/[^a-zA-Z\_]/', '', $ret['database']); // filter
		return $this->dbconfig = $ret;
	}

	public function get_dt_config(string $database = null) : ?array {
		$conf = $this->get_db_config();
		return [
			'host' => $conf['host'],
			'port' => $conf['port'] ?? null,
			'user' => $conf['user'],
			'pass' => $conf['password'],
			'db' => $database ?? $conf['database'],
		];
	}

	/* Implement ArrayAccess methods */
	public function offsetExists ($offset) : bool {
		return isset($this->config[$offset]);
	}

	public function offsetGet ($offset) {
		return $this->config[$offset];
	}

	public function offsetSet ($offset, $value ) : void {
		throw new \Exception("Configuration files are read-only.");
	}

	public function offsetUnset ($offset) : void {
		throw new \Exception("Configuration files are read-only.");
	}

	protected function parse_file(string $path) : ?array {
		return parse_ini_file($path, true, INI_SCANNER_TYPED);
	}
}
