<?php

require 'vendor/autoload.php';

require 'MyWriter.php';

use PHPUnit\Framework\TestCase;

class MyWriterTest extends TestCase
{
    public function test_it_should_filter_with_logs()
    {
        $myWriter = new MyWriter();
        $data = [1, 10, 15, 20, 25];

        // test
        list($result, $log) = $myWriter->foo($data);

        $this->assertEquals(
            [10],
            $result
        );

        $this->assertEquals(
'Reject odd number 1.
Reject odd number 15.
Reject 20 because it is bigger than 15
Reject odd number 25.
',
            $log->extract()
        );
    }
}
