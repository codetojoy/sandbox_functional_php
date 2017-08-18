
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f;
use Widmogrod\Monad\State as s;

class Registry {
    private $users;

    function __construct() {
        $this->users = array();
    }

    public function register($user) {
        $instance = new self();
        $instance->users = $this->users;
        array_push($instance->users, $user);
        return $instance;
    }
}

function updateState($id, $registry, $dummy) {
    $newRegistry = $registry->register($id);
    return [$id, $newRegistry];
}

function buildState($id, $dummy = [])
{ 
    return s\state(function($registry) use ($id, $dummy) { 
        return updateState($id, $registry, $dummy);
    }); 
} 

// TODO: dummy is not used but trying to excise it has been brutal

function registerUser($id, $dummy = [])
{ 
    return f\curryN(2, 'buildState')(...func_get_args()); 
} 

list($id, $registry) = s\runState( 
  registerUser(5150, [])
    ->bind(registerUser(6160)) 
    ->bind(registerUser(7170)) 
    ->bind(registerUser(8180)),
    new Registry() 
); 

print_r($registry);
 
?>
