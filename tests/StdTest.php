<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\Utils\Stderr;
use Luoyecb\Utils\Stdout;

class StdTest extends TestCase {

	public function testStdout() {
		Stdout::printf("Stdout test %s: %s.", "hello", "stdout");
		Stdout::println();
		$this->assertTrue(true);
	}

	public function testStderr() {
		Stderr::printf("Stdout test %s: %s.", "hello", "stdout");
		Stderr::println();
		$this->assertTrue(true);
	}

}
