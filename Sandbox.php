
<?php

require 'vendor/autoload.php';

use Widmogrod\Monad\Maybe as m; 

class Sandbox {
    public function safeUpperCase($ms) {
        $default = 'Hello';
        return m\maybe($default, 'strtoupper', $ms); 
    }
}
 
?>
