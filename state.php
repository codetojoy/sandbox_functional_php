
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f;
use Widmogrod\Monad\State as s;

function getUser($id, $current = []) 
{ 
    return f\curryN(2, function($id, $current) { 
        return s\state(function($cache) use ($id, $current) { 
            if(! isset($cache[$id])) { 
                $cache[$id] = "user #$id"; 
            } 
 
            return [f\append($current, $cache[$id]), $cache]; 
        }); 
    })(...func_get_args()); 
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
