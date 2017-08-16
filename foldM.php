
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f; 
use Widmogrod\Monad\Maybe as m; 

function foldM(callable $f, $initial, $collection) 
{ 
    $monad = $f($initial, head($collection)); 
 
    $_foldM = function($acc, $collection) use($monad, $f, &$_foldM){ 
        if(count($collection) == 0) { 
            return $monad->of($acc); 
        } 
 
        $x = head($collection); 
        $xs = tail($collection); 
 
        return $f($acc, $x)->bind(function($result) use($acc,$xs,$_foldM) { 
            return $_foldM($result, $xs); 
        }); 
    }; 
 
    return $_foldM($initial, $collection); 
} 

// --------- main 

$divide = function($acc, $i) { 
    return $i == 0 ? m\nothing() : m\just($acc / $i); 
}; 
 
var_dump(f\foldM($divide, 100, [2])->extract()); 
var_dump(f\foldM($divide, 100, [2, 5])->extract()); 
var_dump(f\foldM($divide, 100, [2, 5, 2])->extract()); 
var_dump(f\foldM($divide, 100, [2, 0, 2])->extract()); 

?>
