
<?php 

require 'vendor/autoload.php';

use Widmogrod\Monad\Reader;
use Widmogrod\Functional as f;

class Container 
{ 
    public $userEntityManager; 
    public $emailService; 

    public function configure() {
        $this->userEntityManager = new class() { 
            public function getUser() { 
              return new class() { 
                  public $email = 'john.doe@email.com'; 
              }; 
            } 
        }; 
         
        $this->emailService = new class() { 
            public function send($title, $content, $email) { 
                echo "Sending '$title' to $email"; 
            } 
        }; 
    }
} 

function controller(array $post) 
{ 
    return Reader::of(function(Container $container) use($post) { 
        getUserEmail($post['username']) 
            ->bind(f\curry('sendEmail', ['Welcome', '...'])) 
            ->runReader($container); 
 
        return "<h1>Welcome !</h1>"; 
    }); 
} 

function getUser(string $username) 
{ 
    return Reader::of(function(Container $container) use($username) { 
        return $container->userEntityManager->getUser($username); 
    }); 
} 
 
function getUserEmail($username) 
{ 
    return getUser($username)->map(function($user) { 
        return $user->email; 
    }); 
} 
 
function sendEmail($title, $content, $email) 
{ 
    return Reader::of(
        function(Container $container) use($title, $content, $email) { 
        return $container->emailService->send($title, $content, $email); 
    }); 
} 

class MyReader {
    public function foo() {
        $container = new Container(); 
        $container->configure();

        $content = controller(['username' => 'john.doe'])->runReader($container); 
   
        return $content; 
    }
}

?>
