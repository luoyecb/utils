<?php
namespace Luoyecb\Utils;

declare(ticks=1);

/**
 * Daemon process
 */
class Daemon {
	const F_NO_UMASK = 1;
	const F_NO_CHDIR = 2;
	const F_NO_REOPEN_STD_FDS = 4;

	private $workDir = '/tmp';
	private $flags = 0;
	private $stdFile = '/dev/null';
	private $ignoreSignals = [];

	public function __construct() {
		$this->check();
	}

	private function check() {
		if (php_sapi_name() != 'cli') {
			exit('Please run under the command line.');
		}
		if (!extension_loaded('pcntl')) {
			exit('PCNTL extension unloaded.');
		}
		if (!extension_loaded('posix')) {
			exit('POSIX extension unloaded.');
		}
	}

	public function setIgnoreSignal($sig) {
		$this->ignoreSignals[] = $sig;
		return $this;
	}

	public function setFlag($flag) {
		$this->flags |= $flag;
		return $this;
	}

	public function getFlag() {
		return $this->flags;
	}

	public function setWorkDir($dir) {
		$this->workDir = $dir;
		return $this;
	}

	public function getWorkDir() {
		return $this->workDir;
	}

	public function start() {
		switch (pcntl_fork()) {
			case -1: exit('pcntl_fork error.');
			case 0: break; // in child process
			default: exit(); // in parent process
		}

		// new session
		if (posix_setsid() == -1) {
			exit('posix_setsid error.');
		}

		switch(pcntl_fork()) {
			case -1: exit('pcntl_fork error.');
			case 0: break;
			default: exit();
		}

		// clear umask
		if (!($this->flags & self::F_NO_UMASK)) {
			umask(0);
		}

		// change work directory
		if (!($this->flags & self::F_NO_CHDIR)) {
			if (!chdir($this->workDir)) {
				exit('change work directory error.');
			}
		}

		// reopen stdin/stdout/stderr
		if (!($this->flags & self::F_NO_REOPEN_STD_FDS)) {
			fclose(STDIN);
			fclose(STDOUT);
			fclose(STDERR);
			fopen($this->stdFile, 'r'); // stdin
			fopen($this->stdFile, 'w'); // stdout
			fopen($this->stdFile, 'w'); // stderr
		}

		// ignore signals
		$this->ignoreSignals = array_unique($this->ignoreSignals, SORT_NUMERIC);
		foreach ($this->ignoreSignals as $sig) {
			pcntl_signal($sig, SIG_IGN);
		}
	}
}
