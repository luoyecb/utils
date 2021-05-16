<?php
namespace Luoyecb\Utils;

trait Singleton {
	private static $ins = NULL;

	private function __clone() {}

	private function __construct() {}

	public static function getInstance() {
		if (self::$ins === NULL) {
			self::$ins = new self();
		}
		return self::$ins;
	}
}
