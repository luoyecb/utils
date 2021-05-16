<?php
namespace Luoyecb\Utils;

class Stderr {

	public static function println(string $msg = '') {
		$output = self::collectOuput(function() use ($msg) {
			Console::red($msg);
			echo PHP_EOL;
		});
		fwrite(STDERR, $output);
	}

	public static function printf(string $format, ...$values) {
		$output = self::collectOuput(function() use ($format, $values){
			Console::red(sprintf($format, ...$values));
		});
		fwrite(STDERR, $output);
	}

	private static function collectOuput($callback): string {
		ob_start();
		$callback();
		return ob_get_clean();
	}

}

/*
Stderr::println();
Stderr::printf("Stdout test %s: %s.", "hello", "stdout");
*/
