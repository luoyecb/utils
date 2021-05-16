<?php
namespace Luoyecb\Ini;

use \ArrayAccess;
use \Exception;

/**
 * Parser for .ini file
 */
class Config implements ArrayAccess {
	private $parser;
	private $process_sections;

	public function __construct(string $iniStr, bool $process_sections = true) {
		$this->process_sections = $process_sections;
		$this->parser = $process_sections ? new IniParser($iniStr) : new IniParserNoSection($iniStr);
	}

	public static function fromString(string $iniStr, bool $process_sections = true): Config {
		return new Config($iniStr, $process_sections);
	}

	public static function fromFile(string $filename, bool $process_sections = true): Config {
		if (empty($filename) || !is_file($filename)) {
			throw new Exception("{$filename} not exist.");
		}
		return new Config(file_get_contents($filename), $process_sections);
	}

	public function get(string $key, $section = NULL) {
		return $this->parser->get($key, $section);
	}

	public function getSection(string $section): array {
		return $this->parser->getSection($section);
	}

	public function getAll(): array {
		return $this->parser->getAll();
	}

	public function set(string $key, $val, $section = NULL) {
		$this->parser->set($key, $val, $section);
	}

	public function saveAsString(): string {
		return $this->parser->saveAsString();
	}

	public function saveAsFile(string $filename) {
		file_put_contents($filename, $this->saveAsString());
	}

	public function offsetExists($offset) {
		list($key, $section) = $this->parser->dotSplit($offset);
		return $this->parser->exists($key, $section);
	}

	public function offsetGet($offset) {
		list($key, $section) = $this->parser->dotSplit($offset);
		return $this->parser->get($key, $section);
	}

	public function offsetSet($offset, $value) {
		list($key, $section) = $this->parser->dotSplit($offset);
		$this->parser->set($key, $value, $section);
	}

	public function offsetUnset($offset) {
		throw new Exception('Operation not supported.');
	}
}
