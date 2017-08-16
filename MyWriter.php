<?php

require 'vendor/autoload.php';

use Widmogrod\Monad\Writer as W;
use Widmogrod\Functional as f;
use Widmogrod\Primitive\Stringg as S;

class MyWriter 
{
    public function foo($data)
    {
        $filter = function ($i) {
            if ($i % 2 == 1) {
                return W::of(false, S::of("Reject odd number $i.\n"));
            } elseif ($i > 15) {
                return W::of(false, S::of("Reject $i because it is bigger than 15\n"));
            }

            return W::of(true);
        };

        return f\filterM($filter, $data)->runWriter();
    }
}
