
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f;
use Widmogrod\Monad\State as s;

function logArray($a, $msg) {
    echo "begin: ".$msg."\n";
    print_r($a);
    echo "end: ".$msg."\n";
}

function logArrays($a1, $msg1, $a2, $msg2) {
    logArray($a1, $msg1);
    logArray($a2, $msg2);
}

function updateState($id, $current, $cache) {
    echo "TRACER updateState id: ".$id."\n";
    // redundant: logArrays($current, 'current', $cache, 'cache');
    if(! isset($cache[$id])) { 
        echo "TRACER updateState cache miss id: ".$id."\n";
        $cache[$id] = "user #$id"; 
    } else {
        echo "TRACER updateState cache hit id: ".$id."\n";
    }

    return [f\append($current, $cache[$id]), $cache]; 
}

function buildState($id, $current = []) 
{ 
    echo "TRACER buildState id: ".$id."\n";
    logArray($current, 'current');
    return s\state(function($cache) use ($id, $current) { 
        echo "TRACER inside s id: ".$id."\n";
        logArrays($current, 'current', $cache, 'cache');
        return updateState($id, $current, $cache);
    }); 
} 

function getUser($id, $current = []) 
{ 
    echo "TRACER getUser id: ".$id."\n";
    logArray($current, 'current');
    return f\curryN(2, 'buildState')(...func_get_args()); 
} 

// Notes:
// 'seedX' is passed into the first State function as $cache
// 'seedY' is passed into getUser as $current
// $current is a cursor for the state, and has duplicates
// $cache is passed as parameter, no dupes
 
list($users, $cache) = s\runState( 
  getUser(1, ['seedY']) 
    ->bind(getUser(2)) 
    ->bind(getUser(1)) 
    ->bind(getUser(3)), 
  ['seedX'] 
); 
 
// print_r($users); 
// print_r($cache); 

?>
