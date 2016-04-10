<?php

namespace Game;

class Chess
{
    const WHITE = 'white';
    const BLACK = 'black';
    const BOARDHEIGHT = 8;
    const BOARDWIDTH  = 8;
    public $pgn;
    private $A = 0;
    private $B = 1;
    private $C = 2;
    private $D = 3;
    private $E = 4;
    private $F = 5;
    private $G = 6;
    private $H = 7;
    protected $game = 0;
    protected $move = '';
    protected $currentPosition = [['WR1','WN1','WB1','WQ','WK','WB2','WN2','WR2'],
                                 ['WP1','WP2','WP3','WP4','WP5','WP6','WP7','WP8'],
                                 ['','','','','','','',''],
                                 ['','','','','','','',''],
                                 ['','','','','','','',''],
                                 ['','','','','','','',''],
                                 ['BP1','BP2','BP3','BP4','BP5','BP6','BP7','BP8'],
                                 ['BR1','BN1','BB1','BQ','BK','BB2','BN','BR2']
                                 ];

    public function __construct($game, $move)
    {
        $this->game = $game;
        $this->move = $move;
        $database = new \DB\Database();

        $this->setUpGame($database->getGame($game));

    }
    public function getPositions()
    {
        return $this->currentPosition;
    }
    private function setUpGame($currentPosition)
    {
        //separate pgn into readable array
        $moves = preg_split("/[0-9]{1,2}+\./",$currentPosition, -1, PREG_SPLIT_NO_EMPTY);

        foreach($moves as $move){
            $moveSet = preg_split("/[\s,]+/",$move, -1, PREG_SPLIT_NO_EMPTY);

            $this->movePiece($moveSet[0], self::WHITE);
            $this->movePiece($moveSet[1], self::BLACK);
        }
    }
    private function movePiece($move,$color)
    {

        if(strlen($move) < 3){
            $this->movePawn($move,$color);
        } elseif (strpos($move,'N') > -1){
            $this->moveKnight($move,$color);
        } elseif (strpos($move,'B') > -1) {
            $this->moveBishop($move,$color);
        } elseif(strpos($move,'R') > -1){
            $this->moveRook($move,$color);
        } elseif (strpos($move,'Q')) {
            $this->moveQueen($move,$color);
        } elseif(strpos($move,'K')){
            $this->moveKing($move,$color);
        } elseif (strpos($move,'0-0')) {
            $this->castle($move,$color);
        } elseif (strpos($move,'x')){
            $this->takePiece($move,$color);
        }
    }
    private function movePawn($move,$color)
    {
        $value = strtoupper($move[0]);

        //echo $this->currentPosition[4][$this->$value];

        $boardPositionY = $move[1]-1;

        if($color=='white'){
            for($x=$boardPositionY; $x>=0; $x--){
                if(strpos($this->currentPosition[$x][$this->$value],'WP') > -1){
                    $piece = $this->currentPosition[$x][$this->$value];
                    $this->currentPosition[$x][$this->$value]='';
                    $this->currentPosition[$boardPositionY][$this->$value]=$piece;
                    break 1;
                }
            }
        } elseif ($color == 'black') {

            for($x = $boardPositionY; $x < 7; $x++){
                if(strpos($this->currentPosition[$x][$this->$value],'BP') > -1){

                    $piece = $this->currentPosition[$x][$this->$value];
                    $this->currentPosition[$x][$this->$value]='';
                    $this->currentPosition[$boardPositionY][$this->$value]=$piece;

                    break 1;
                }
            }
        }
    }
    private function moveKnight($move,$color)
    {
        $piece = ($color == 'white' ? 'WN' : 'BN' );

        for( $x = 0; $x < self::BOARDHEIGHT; $x++ ){
            for( $y = 0; $y < self::BOARDWIDTH; $y++){
                if(strpos( $this->currentPosition[$x][$y], $piece ) > -1){
                    //If defined position place logic header_remove
                    if( $this->isKnightmoveValid( [$x,$y] , $move)){

                        $newPiece = $this->currentPosition[$x][$y];
                        $this->currentPosition[$x][$y]='';

                        $value  = strtoupper($move[1]);
                        $xValue = $this->$value;
                        $yValue = $move[2]-1;
                        $this->currentPosition[$yValue][$xValue] = $newPiece;

                        break 2;
                    }
                }
            }
        }
    }
    private function  moveBishop($move,$color)
    {

        $value  = strtoupper($move[1]);
        $xValue = $this->$value;
        $yValue = $move[2]-1;
        $piece = ($color == 'white' ? 'WB' : 'BB' );

        $squareColor = $this->getSquareColor($xValue, $yValue);

        for($x = 0; $x < self::BOARDHEIGHT; $x++ ){

            for($y = 0; $y < self::BOARDWIDTH; $y++){

                if( strpos( $this->currentPosition[$x][$y], $piece ) > -1 &&

                    $this->getSquareColor($y, $x) == $squareColor ){

                        $newPiece = $this->currentPosition[$x][$y];
                        $this->currentPosition[$x][$this->$value]='';
                        $this->currentPosition[$yValue][$xValue] = $newPiece;
                }
            }
        }
    }
    // To do: move to a knight class and include as a static function
    private function isKnightMoveValid($position, $move)
    {
        $value  = strtoupper($move[1]);
        $xValue = $this->$value;
        $yValue = $move[2]-1;

        if($xValue +2 == $position[1] && $yValue -1 == $position[0] ||
           $xValue +2 == $position[1] && $yValue +1 == $position[0] ||
           $xValue +1 == $position[1] && $yValue +2 == $position[0] ||
           $xValue -1 == $position[1] && $yValue +2 == $position[0] ||
           $xValue -2 == $position[1] && $yValue +1 == $position[0] ||
           $xValue -2 == $position[1] && $yValue -1 == $position[0] ||
           $xValue -1 == $position[1] && $yValue -2 == $position[0] ||
           $xValue +1 == $position[1] && $yValue -2 == $position[0]
        ){
            return true;
        }
    }
    private function moveRook($move, $color)
    {
        $value  = strtoupper($move[1]);
        $xValue = $this->$value;
        $yValue = $move[2]-1;
        $piece = ($color == 'white' ? 'WR' : 'BR' );
        $found = false;
        for( $x = 0; $x < self::BOARDHEIGHT; $x++ ){

            if( strpos( $this->currentPosition[$x][$xValue], $piece) > -1){
                $found = true;
                $newPiece = $this->currentPosition[$x][$xValue];
                $this->currentPosition[$x][$xValue] = '';

                break;
            }
        }

        if(!$found){

            for( $y = 0; $y < self::BOARDWIDTH; $y++){
                if(strpos( $this->currentPosition[$yValue][$y], $piece) > -1){
                    $newPiece = $this->currentPosition[$yValue][$y];
                    $this->currentPosition[$yValue][$y] = '';
                    $found = true;

                    break;
                }
            }
        }
        $this->currentPosition[$yValue][$xValue] = $newPiece;

    }
    private function getSquareColor($xValue, $yValue)
    {
        if($xValue % 2 == 0 && $yValue %2 == 0){
            $squareColor = 'black';
        } elseif ($xValue % 2 != 0 && $yValue %2 == 0) {
            $squareColor = 'white';
        } elseif($xValue % 2 != 0 && $yValue %2 != 0) {
            $squareColor = 'white';
        } else {
            $squareColor = 'black';
        }

        return $squareColor;
    }
}

?>
