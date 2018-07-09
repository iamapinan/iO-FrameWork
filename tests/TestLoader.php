<?php
namespace Test;

use PHPUnit\Framework\TestCase;

class Test extends TestCase {

    public function testSystemLoad() {
        $load = new IOFramework\Loader;
        $this->assertNotEmpty($load->version());
    }
}