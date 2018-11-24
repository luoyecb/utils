<?php
// 
class FileIterator implements Iterator
{
	protected $handler;
	protected $filename;
	protected $lineno = 0;
	protected $cur_content = ''; // current row content
	protected $eof = false;
	protected $file_closed = true;

	public function __construct($filename, $mode) {
		$this->filename = $filename;
		$this->handler = fopen($filename, $mode);
		if (!$this->handler) {
			throw new RuntimeException('open file error.');
		}
		$this->file_closed = false;
	}

	public function __destruct() {
		$this->close();
	}

	// call file function
	public function __call($name, $args) {
		if (function_exists($name)) {
			if (strncmp($name, 'is_', 3) === 0) {
				return $name($this->filename);
			} else {
				array_unshift($args, $this->handler);
				return call_user_func_array($name, $args);
			}
		}
		throw new BadMethodCallException(sprintf('Nonexistent method %s', $name));
	}

	public function close() {
		if (!$this->file_closed) {
			fclose($this->handler);
			$this->file_closed = true;
		}
	}

	public function rewind() {
		rewind($this->handler);
		$this->lineno = 0;
		$this->eof = false;
	}

	public function valid() {
		return !$this->eof;
	}

	public function current() {
		if ($this->lineno == 0) {
			$this->lineno++;
			return $this->cur_content = fgets($this->handler);
		} else {
			$this->lineno++;
			return $this->cur_content;
		}
	}

	public function key() {
		return $this->lineno;
	}

	public function next() {
		if (!feof($this->handler)) {
			$this->cur_content = fgets($this->handler);
		} else {
			$this->eof = true; // end of file
		}
	}
}

// generator
function file_open($filename, $mode) {
	$file = fopen($filename, $mode);
	while (!feof($file)) {
		yield fgets($file);
	}
	fclose($file);
}
