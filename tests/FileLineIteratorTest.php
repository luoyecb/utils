<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\Utils\FileLineIterator;

class FileLineIteratorTest extends TestCase {

	public function testNormal() {
		$testfile = __DIR__ . '/FileLineIteratorTest.php';

		$expected = file($testfile);

		$lines = [];
		$iter = new FileLineIterator($testfile);
		foreach ($iter as $row) {
			$lines[] = $row;
		}

		$this->assertSame($expected, $lines);
	}

}
