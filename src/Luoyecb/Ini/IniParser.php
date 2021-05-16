<?php
namespace Luoyecb\Ini;

/**
 * Process sections
 */
class IniParser implements IniParserInterface {
	private $cfgs = [];

	public function __construct($iniStr) {
		$this->cfgs = parse_ini_string($iniStr, true);
	}

	public function get(string $key, $section = NULL) {
		if ($section === NULL) {
			return $this->cfgs[$key] ?? NULL;
		} else {
			return $this->cfgs[$section][$key] ?? NULL;
		}
	}

	public function getSection(string $section): array {
		return $this->cfgs[$section] ?? [];
	}

	public function getAll(): array {
		return $this->cfgs;
	}

	public function set(string $key, $val, $section = NULL) {
		if ($section === NULL) {
			$this->cfgs[$key] = $val;
		} else {
			$this->cfgs[$section][$key] = $val;
		}
	}

	public function saveAsString(): string {
		$ini_str = '';
		foreach ($this->cfgs as $k => $v) {
			if (is_array($v)) {
				$ini_str .= "[$k]" . PHP_EOL;
				foreach ($v as $sk => $sv) {
					$ini_str .= "$sk = $sv" . PHP_EOL;
				}
			} else {
				$ini_str .= "$k = $v" . PHP_EOL;
			}
		}
		return $ini_str;
	}

	public function dotSplit(string $key): array {
		$keys = explode('.', $key);
		if (count($keys) < 2) {
			return [$keys[0], NULL]; // [key, section]
		} else {
			return [$keys[1], $keys[0]]; // [key, section]
		}
	}

	public function exists(string $key, $section = NULL): bool {
		if ($section === NULL) {
			return isset($this->cfgs[$key]);
		} else {
			return isset($this->cfgs[$section][$key]);
		}
	}
}
