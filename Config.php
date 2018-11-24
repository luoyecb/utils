<?php
// 
class Config implements ArrayAccess
{
	protected $filename;
	protected $process_sections = true;
	protected $cfgs = [];

	public function __construct($filename, $process_sections = true) {
		if (empty($filename) || !is_file($filename)) {
			throw new FileNotFoundException("{$filename}, file does not exist.");
		}
		$this->filename = $filename;
		$this->process_sections = $process_sections;
		$this->cfgs = parse_ini_file($filename, $process_sections);
	}

	// get config value
	public function get($key) {
		if (isset($this->cfgs[$key])) {
			return $this->cfgs[$key];
		} elseif ($this->isDotSplit($key)) {
			if (!$this->process_sections) {
				throw new Exception('Unsupported syntax.');
			}
			$keys = explode('.', $key);
			$ret = $this->cfgs;
			foreach ($keys as $k) {
				$k = trim($k);
				if (isset($ret[$k])) {
					$ret = $ret[$k];
				} else {
					return NULL;
				}
			}
			return $ret;
		}
		return NULL;
	}

	public function set($key, $val) {
		if ($this->isDotSplit($key)) {
			$keys = explode('.', $key);
			if (count($keys) > 2 || !$this->process_sections) {
				throw new Exception('Unsupported syntax.');
			}
			$this->cfgs[trim($keys[0])][trim($keys[1])] = $val;
		} else {
			$this->cfgs[$key] = $val;
		}
	}

	protected function isDotSplit($str) {
		return preg_match('/^\s*\w+\s*(\.\s*\w+\s*)*$/', $str) ? true : false;
	}

	public function getAllConfigs() {
		return $this->cfgs;
	}

	// return an generator
	public function getIterator() {
		foreach ($this->cfgs as $k => $val) {
			yield $k => $val;
		}
	}

	public function saveAsFile($otherFile = NULL) {
		if (empty($otherFile)) {
			$otherFile = $this->filename;
		}
		file_put_contents($otherFile, $this->saveAsString());
	}

	public function saveAsString() {
		$string = '';
		$iter = $this->getIterator();
		if (!$this->process_sections) {
			foreach ($iter as $k => $v) {
				$string .= "$k = $v\n";
			}
		} else {
			foreach ($iter as $k => $v) {
				if (is_array($v)) {
					$string .= "[$k]\n";
					foreach ($v as $kk => $vv) {
						$string .= "$kk = $vv\n";
					}
					$string .= "\n";
				} else {
					$string .= "$k = $v\n";
				}
			}
		}
		return $string;
	}

	public function offsetExists($offset) {
		return isset($this->cfgs[$offset]);
	}

	public function offsetGet($offset) {
		return $this->get($offset);
	}

	public function offsetSet($offset, $value) {
		$this->set($offset, $value);
	}

	public function offsetUnset($offset) {
		unset($this->cfgs[$offset]);
	}

	public function __toString() {
		return $this->saveAsString();
	}
}

// file does not exists exception
class FileNotFoundException extends Exception
{
	public $message;

	public function __construct($message) {
		$this->message = $message;
	}
}
