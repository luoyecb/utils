<?php
namespace Luoyecb\Ini;

use \Exception;

/**
 * Not process sections
 */
class IniParserNoSection implements IniParserInterface {
	private $cfgs = [];

	public function __construct($iniStr) {
		$this->cfgs = parse_ini_string($iniStr, false);
	}

	public function get(string $key, $section = NULL) {
		return $this->cfgs[$key] ?? NULL;
	}

	public function getSection(string $section): array {
		throw new Exception('Operation not supported.');
	}

	public function getAll(): array {
		return $this->cfgs;
	}

	public function set(string $key, $val, $section = NULL) {
		$this->cfgs[$key] = $val;
	}

	public function saveAsString(): string {
		$ini_str = '';
		foreach ($this->cfgs as $k => $v) {
			$ini_str .= "$k = $v" . PHP_EOL;
		}
		return $ini_str;
	}

	public function dotSplit(string $key): array {
		return [$key, NULL]; // [key, section]
	}

	public function exists(string $key, $section): bool {
		return isset($this->cfgs[$key]);
	}
}
