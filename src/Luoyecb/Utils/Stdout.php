<?php
namespace Luoyecb\Utils;

class Stdout {

	public static function println(string $msg = '') {
		echo $msg;
		echo PHP_EOL;
	}

	public static function printf(string $format, ...$values) {
		echo sprintf($format, ...$values);
	}

}

/*
Stdout::println();
Stdout::printf("Stdout test %s: %s.", "hello", "stdout");
*/
