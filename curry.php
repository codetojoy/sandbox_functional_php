
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f;

// curryN

$foo = f\curryN(2, function($x, $y) {
    return $x + $y;
});

$bar = $foo(10);
$x = $bar(11);

print_r($x);

// ...func_get_args()

function boo() {
    return f\curryN(1, function($x) {return $x-1;}) (...func_get_args());
}

$y = boo(10,20,20);
print_r($y);

?>
