<?php
namespace Luoyecb\Utils;

use \IteratorAggregate;
use \Traversable;

class Stdin implements IteratorAggregate {

	public static function readAll(): string {
		$buf = '';
		foreach (self::getGenerator() as $row) {
			$buf .= $row;
		}
		return $buf;
	}

	public function getIterator(): Traversable {
		return self::getGenerator();
	}

	private static function getGenerator() {
		while (($line = fgets(STDIN)) !== false) {
			yield $line;
		}
	}
}

/*
// $str = Stdin::readAll();
// var_dump($str);

$stdin = new Stdin;
foreach ($stdin as $row) {
	var_dump($row);
}
*/
