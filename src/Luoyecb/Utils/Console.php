<?php
namespace Luoyecb\Utils;

class Console {

	// colors
	const COLOR_RED          = 1;
	const COLOR_GREEN        = 2;
	const COLOR_YELLOW       = 3;
	const COLOR_BLUE         = 4;
	const COLOR_PURPLE       = 5;
	const COLOR_CYAN         = 6;
	const COLOR_LIGHT_PURPLE = 7;
	const COLOR_RESET        = 8;

	// colors => ascii mapping
	public static $colorMappings = [
		self::COLOR_RED          => "\033[31m", // 红色
		self::COLOR_GREEN        => "\033[32m", // 绿色
		self::COLOR_YELLOW       => "\033[33m", // 黄色
		self::COLOR_BLUE         => "\033[34m", // 蓝色
		self::COLOR_PURPLE       => "\033[35m", // 紫色
		self::COLOR_CYAN         => "\033[36m", // 青色
		self::COLOR_LIGHT_PURPLE => "\033[37m", // 淡紫色
		self::COLOR_RESET        => "\033[0m",  // 清除
	];

	private static function reset() {
		echo self::$colorMappings[self::COLOR_RESET];
	}

	public static function red(string $msg) {
		self::output(self::COLOR_RED, $msg);
	}

	public static function green(string $msg) {
		self::output(self::COLOR_GREEN, $msg);
	}

	public static function yellow(string $msg) {
		self::output(self::COLOR_YELLOW, $msg);
	}

	public static function blue(string $msg) {
		self::output(self::COLOR_BLUE, $msg);
	}

	public static function purple(string $msg) {
		self::output(self::COLOR_PURPLE, $msg);
	}

	public static function cyan(string $msg) {
		self::output(self::COLOR_CYAN, $msg);
	}

	public static function lightPurple(string $msg) {
		self::output(self::COLOR_LIGHT_PURPLE, $msg);
	}

	public static function output(int $color, string $msg) {
		if ($color >= self::COLOR_RED && $color <= self::COLOR_LIGHT_PURPLE) {
			echo self::$colorMappings[$color];
			echo $msg;
			self::reset();
		}
	}

}

/*
Console::red("Console color output."); echo PHP_EOL;
Console::green("Console color output."); echo PHP_EOL;
Console::yellow("Console color output."); echo PHP_EOL;
Console::blue("Console color output."); echo PHP_EOL;
Console::purple("Console color output."); echo PHP_EOL;
Console::cyan("Console color output."); echo PHP_EOL;
Console::lightPurple("Console color output."); echo PHP_EOL;
*/
