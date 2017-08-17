
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f;
use Widmogrod\Monad\State as s;

function updateState($id, $current, $cache) {
    if(! isset($cache[$id])) { 
        $cache[$id] = "user #$id"; 
    } 

    return [f\append($current, $cache[$id]), $cache]; 
}

function buildState($id, $current = []) 
{ 
    return s\state(function($cache) use ($id, $current) { 
        return updateState($id, $current, $cache);
    }); 
} 

function getUser($id, $current = []) 
{ 
    return f\curryN(2, 'buildState')(...func_get_args()); 
} 
 
list($users, $cache) = s\runState( 
  getUser(1, []) 
    ->bind(getUser(2)) 
    ->bind(getUser(1)) 
    ->bind(getUser(3)), 
  [] 
); 
 
print_r($users); 
 
print_r($cache); 

?>
