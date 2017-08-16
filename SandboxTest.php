<?php
declare(strict_types=1);

require 'vendor/autoload.php';
require 'Sandbox.php';

use Widmogrod\Monad\Maybe as m; 
use Widmogrod\Functional as f; 
use PHPUnit\Framework\TestCase;

class SandboxTest extends TestCase {
    public function testSafeUpperCase_Basic() {
        $sandbox = new Sandbox;
        $nothing = m\just('Mozart'); 

        // test
        $result = $sandbox->safeUpperCase($nothing);

        $this->assertEquals('MOZART', $result);
    }

    public function testSafeUpperCase_Nothing() {
        $sandbox = new Sandbox;
        $nothing = m\nothing(); 

        // test
        $result = $sandbox->safeUpperCase($nothing);

        $this->assertEquals('Hello', $result);
    }
}

?>
