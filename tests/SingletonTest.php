<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\Utils\Singleton;

class Foo {
	use Singleton;
}

class SingletonTest extends TestCase {

	public function testNormal() {
		$foo1 = Foo::getInstance();
		$foo2 = Foo::getInstance();

		$this->assertSame($foo1, $foo2);
		$this->assertTrue($foo1 === $foo2);
	}

}
