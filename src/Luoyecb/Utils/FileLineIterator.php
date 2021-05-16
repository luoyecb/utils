<?php
namespace Luoyecb\Utils;

use \IteratorAggregate;
use \Traversable;

// 
class FileLineIterator implements IteratorAggregate {
	private $filename;
	private $mode;

	public function __construct($filename, $mode = 'r') {
		if (empty($filename) || !is_file($filename)) {
			throw new Exception("${filename} not exists.");
		}

		$this->filename = $filename;
		$this->mode = $mode;
	}

	public function getIterator(): Traversable {
		return $this->getFileGenerator();
	}

	private function getFileGenerator() {
		$fh = fopen($this->filename, $this->mode);
		while (($line = fgets($fh)) !== false) {
			yield $line;
		}
		fclose($fh);
	}
}
