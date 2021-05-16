<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\Ini\Config;

class IniConfigTest extends TestCase {

	public $testConfigFile = __DIR__ . '/app.ini';
	public $config;
	public $configNoSection;

	public function setUp() {
		$this->config = Config::fromFile($this->testConfigFile);
		$this->configNoSection = Config::fromFile($this->testConfigFile, false);
	}

	public function testCreate() {
		$cfg1 = Config::fromFile($this->testConfigFile);
		$cfg2 = Config::fromString(file_get_contents($this->testConfigFile));

		$this->assertTrue($cfg1 instanceof Config);
		$this->assertTrue($cfg2 instanceof Config);
	}

	public function testCreate_NoSection() {
		$cfg1 = Config::fromFile($this->testConfigFile, false);
		$cfg2 = Config::fromString(file_get_contents($this->testConfigFile), false);

		$this->assertTrue($cfg1 instanceof Config);
		$this->assertTrue($cfg2 instanceof Config);
	}

	public function testGet() {
		$this->assertSame($this->config->get('host'), "local.glc.com");
		$this->assertSame($this->config->get('port'), "80");

		$this->assertSame($this->config->get('host', 'dev'), "dev.glc.com");
		$this->assertSame($this->config->get('port', 'dev'), "8080");

		$this->assertSame($this->config->get('host', 'production'), "glc.com");
		$this->assertSame($this->config->get('port', 'production'), "8088");

		$this->assertNull($this->config->get('unexists_key'));
		$this->assertNull($this->config->get('unexists_key', 'production'));
	}

	public function testGet_NoSection() {
		$this->assertSame($this->configNoSection->get('host'), 'glc.com');
		$this->assertSame($this->configNoSection->get('port'), '8088');

		$this->assertNull($this->configNoSection->get('unexists_key'));
	}

	public function testGetSection() {
		$this->assertSame($this->config->getSection('dev'), ['host'=>'dev.glc.com', 'port'=>'8080']);
		$this->assertSame($this->config->getSection('production'), ['host'=>'glc.com', 'port'=>'8088']);
		$this->assertSame($this->config->getSection('unexists_key'), []);
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetSection_NoSection() {
		$this->configNoSection->getSection('dev');
	}

	public function testSet() {
		$this->config->set('port', '1111');
		$this->assertSame($this->config->get('port'), '1111');

		$this->config->set('port', '1111', 'dev');
		$this->assertSame($this->config->get('port', 'dev'), '1111');

		$this->assertSame($this->config->get('port', 'production'), '8088');
	}

	public function testSet_NoSection() {
		$this->configNoSection->set('port', '6666');
		$this->assertSame($this->configNoSection->get('port'), '6666');

		$this->configNoSection->set('host', 'test.glc.com');
		$this->assertSame($this->configNoSection->get('host'), 'test.glc.com');
	}

	public function testSaveAsString() {
		$iniStr = $this->config->saveAsString();
		// var_dump($iniStr);

		$cfg = Config::fromString($iniStr);
		$this->assertSame($this->config->getAll(), $cfg->getAll());
	}

	public function testSaveAsString_NoSection() {
		$iniStr = $this->configNoSection->saveAsString();
		// var_dump($iniStr);

		$cfg = Config::fromString($iniStr, false);
		$this->assertSame($this->configNoSection->getAll(), $cfg->getAll());
	}

	public function testArrayAccessGet() {
		$config = $this->config;

		$this->assertSame($config['host'], 'local.glc.com');
		$this->assertSame($config['port'], '80');

		$this->assertSame($config['dev.host'], 'dev.glc.com');
		$this->assertSame($config['dev.port'], '8080');

		$this->assertSame($config['production.host'], 'glc.com');
		$this->assertSame($config['production.port'], '8088');

		$this->assertNull($config['unexists_key']);
		$this->assertNull($config['dev.unexists_key']);
		$this->assertNull($config['unexists_section.unexists_key']);
	}

	public function testArrayAccessGet_NoSection() {
		$config = $this->configNoSection;

		$this->assertSame($config['host'], 'glc.com');
		$this->assertSame($config['port'], '8088');

		$this->assertNull($config['unexists_key']);
		$this->assertNull($config['dev.unexists_key']);
		$this->assertNull($config['unexists_section.unexists_key']);
	}

	public function testArrayAccessSet() {
		$config = $this->config;

		$config['port'] = '1111';
		$this->assertSame($config['port'], '1111');
		$this->assertSame($config['dev.port'], '8080');

		$config['dev.port'] = '1111';
		$this->assertSame($config['dev.port'], '1111');
		$this->assertSame($config['production.port'], '8088');
	}

	public function testArrayAccessSet_NoSection() {
		$config = $this->configNoSection;

		$config['port'] = '6666';
		$this->assertSame($config['port'], '6666');

		$config['host'] = 'test.glc.com';
		$this->assertSame($config['host'], 'test.glc.com');
	}

	/**
	 * @expectedException Exception
	 */
	public function testArrayAccessUnset() {
		unset($this->config['host']);
	}

	public function testArrayAccessExists() {
		$config = $this->config;

		$this->assertTrue(isset($config['host']));
		$this->assertTrue(isset($config['dev.host']));
		$this->assertTrue(isset($config['production.host']));

		$this->assertFalse(isset($config['unexists_key']));
		$this->assertFalse(isset($config['dev.unexists_key']));
		$this->assertFalse(isset($config['unexists_section.unexists_key']));
	}

	public function testArrayAccessExists_NoSection() {
		$config = $this->configNoSection;

		$this->assertTrue(isset($config['port']));
		$this->assertTrue(isset($config['host']));

		$this->assertFalse(isset($config['unexists_key']));
		$this->assertFalse(isset($config['dev.unexists_key']));
		$this->assertFalse(isset($config['unexists_section.unexists_key']));
	}

	public function testIterator() {
		$cfgs = [];
		foreach ($this->config as $key => $val) {
			// var_dump($key);
			// var_dump($val);
			$cfgs[$key] = $val;
		}

		$this->assertSame($this->config->getAll(), $cfgs);
	}

}
