
<?php 

require 'vendor/autoload.php';

use Widmogrod\Functional as f;
use Widmogrod\Monad\State as s;

class Bid {
    private $card; 
    private $player;

    function __construct($card, $player) {
        $this->card = $card;    
        $this->player = $player;    
    }

    public function getCard() { return $this->card; }
    public function getPlayer() { return $this->player; }

    public static function nullBid() { return new Bid(-1, 'Dr. Null'); }
}

class Hand {
    private $cards;

    function __construct($cards) {
        $this->cards = $cards;
    }

    public function selectCard() {
        $card = $this->cards[0];
        $newHand = new Hand(array_slice($this->cards,1));
        return [$card, $newHand];
    } 
}

class Player {
    private $name;
    private $hand; 
    private $roundsWon;
    private $total;

    function __construct($name, $hand, $roundWon = 0, $total = 0) {
        $this->name = $name;
        $this->hand = $hand;
        $this->roundsWon = $roundWon;
        $this->total = $total;
    }

    public function getName() { return $this->name; }

    public function logState() {
        echo "name: ".$this->name." roundsWon: ".$this->roundsWon." total: ".$this->total."\n";
    }

    public function clonePlayer() {
        $newPlayer = new Player($this->name, $this->hand, $this->roundsWon, $this->total);
        return $newPlayer;
    }

    public function getBid() { 
        list($card, $newHand) = $this->hand->selectCard();
        $newPlayer = $this->clonePlayer();
        $newPlayer->hand = $newHand;
        return [$card, $newPlayer];
    }
    
    public function winsRound($prizeCard) {
        $newPlayer = $this->clonePlayer();
        $newPlayer->roundsWon = $this->roundsWon + 1;
        $newPlayer->total = $this->total + $prizeCard;
        return $newPlayer;
    }
}

class GameState {
    private $players;

    function __construct() {
        $p1 = new Player('Mozart',new Hand(array(1,4,5,8)));
        $p2 = new Player('Chopin',new Hand(array(2,3,6,7)));

        $this->players = array();
        $this->players[$p1->getName()] = $p1;
        $this->players[$p2->getName()] = $p2;
    }

    public function logState() {
        foreach($this->players as $p) {
            $p->logState();
        };
    }

    private function updateGameState($newPlayers) {
        $instance = new self();
        $instance->players = $newPlayers;
        return $instance;
    }

    public function getPlayers() { return $this->players; }

    public function playRound($prizeCard) {
        $newPlayers = array();

        $bids = array_map(function ($p) use(&$newPlayers) { 
            $name = $p->getName();
            list($card, $newPlayer) = $p->getBid();
            $bid = new Bid($card, $newPlayer);
            $newPlayers[$name] = $newPlayer;
            return $bid;
        }, $this->players);        


        $winningBid = array_reduce($bids, function ($carry, $item) {
            $leader = $carry;

            if ($item->getCard() > $carry->getCard()) {
                $leader = $item;
            } 

            return $leader;
        }, Bid::nullBid());

        $winner = $winningBid->getPlayer()->winsRound($prizeCard);

        $newPlayers[$winner->getName()] = $winner;

        return $this->updateGameState($newPlayers);
    }
}

//------------- main --------------

function updateState($prizeCard, $gameState, $dummy = []) {
    $newGameState = $gameState->playRound($prizeCard);

    return [$prizeCard, $newGameState];
}

function buildGameState($prizeCard, $dummy = [])
{ 
    return s\state(function($gameState) use ($prizeCard, $dummy) {
        return updateState($prizeCard, $gameState, $dummy);
    }); 
} 

// TODO: nigh impossible to remove `dummy` var 

function playRound($prizeCard, $dummy = [])
{ 
    return f\curryN(2, 'buildGameState')(...func_get_args()); 
} 

list($x, $finalGameState) = s\runState( 
  playRound(10, [])
    ->bind(playRound(20))
    ->bind(playRound(30))
    ->bind(playRound(40)),
  new GameState()
); 

$finalGameState->logState();

/*
$gs = new GameState();
list($x, $gs) = updateState(10, $gs);
list($x, $gs) = updateState(20, $gs);
list($x, $gs) = updateState(30, $gs);
list($x, $gs) = updateState(40, $gs);
$gs->logState();
*/

?>
