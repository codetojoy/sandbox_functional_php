<?php

require 'vendor/autoload.php';

require 'MyReader.php';

use PHPUnit\Framework\TestCase;

class MyReaderTest extends TestCase
{
    public function test_foo()
    {
        $myReader = new MyReader();

        // test
        $result = $myReader->foo();

        $this->assertEquals('<h1>Welcome !</h1>', $result);

        // TODO: assert that email was sent
    }
}
