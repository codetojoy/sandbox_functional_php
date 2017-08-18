
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
        array_push($this->users, $user);
    }

    public function logUsers($msg) {
        // echo "begin: ".$msg."\n";
        // print_r($this->users);
    }
}

function updateState($id, $registry) {
    echo "TRACER updateState id: ".$id."\n";
    $registry->register($id);

    return [$registry, $registry];
}

function buildState($id)
{ 
    echo "TRACER buildState id: ".$id."\n";
    return s\state(function($registry) use ($id) { 
        echo "TRACER inside s id: ".$id."\n";
        $registry->logUsers("inside s");
        return updateState($id, $registry);
    }); 
} 

function registerUser($id)
{ 
    return f\curryN(1, 'buildState')(...func_get_args()); 
} 

list($registry) = s\runState( 
  registerUser(5150)
    ->bind(registerUser(6160)) 
    ->bind(registerUser(7170)) 
    ->bind(registerUser(8180)),
    new Registry() 
); 

print_r($registry);
 
?>
