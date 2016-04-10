<?php

namespace Test;

require '../vendor/autoload.php';

use \Game\Chess;

class ChessTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ChessBoard */
    //private $_testSubject;
    protected $stub;
    protected $foo;

    public function setUp()
    {

    $this->stub = $this->getMockBuilder('\Game\Chess')
        ->setMethods(array('__construct'))
        ->setConstructorArgs(array('',''))
        ->disableOriginalConstructor()
        ->getMock();

        $this->foo = self::getMethod('setUpGame');
    }
    //
    // public function testHas_MaxBoardHeight_of_7()
    // {
    //     $this->assertEquals(8, Chess::BOARDHEIGHT);
    // }
    // public function testHas_MaxBoardWidth_of_7()
    // {
    //     $this->assertEquals(8, Chess::BOARDWIDTH);
    // }
    //
    public function test_set_up()
    {

    $this->foo->invokeArgs($this->stub, array('1. e4 Nc6'));

    $result = $this->stub->getPositions();

        $expected = [['WR1','WN1','WB1','WQ','WK','WB2','WN2','WR2'],
                    ['WP1','WP2','WP3','WP4','','WP6','WP7','WP8'],
                    ['','','','','','','',''],
                    ['','','','','WP5','','',''],
                    ['','','','','','','',''],
                    ['','','BN1','','','','',''],
                    ['BP1','BP2','BP3','BP4','BP5','BP6','BP7','BP8'],
                    ['BR1','','BB1','BQ','BK','BB2','BN2','BR2']];

        $this->assertEquals($expected,$result);

    }
    public function test_rook_advance()
    {

        $this->foo->invokeArgs($this->stub, array('1. a4 a5 2. Ra3 Ra6'));

        $result = $this->stub->getPositions();

            $expected = [['','WN1','WB1','WQ','WK','WB2','WN2','WR2'],
                        ['','WP2','WP3','WP4','WP5','WP6','WP7','WP8'],
                        ['WR1','','','','','','',''],
                        ['WP1','','','','','','',''],
                        ['BP1','','','','','','',''],
                        ['BR1','','','','','','',''],
                        ['','BP2','BP3','BP4','BP5','BP6','BP7','BP8'],
                        ['','BN1','BB1','BQ','BK','BB2','BN2','BR2']];

            $this->assertEquals($expected,$result);

    }
    public function test_queen_moves_forward(){

        $this->foo->invokeArgs($this->stub, array('1. d4 d5 2. Qd3 Qd6'));
        $result = $this->stub->getPositions();

            $expected = [['WR1','WN1','WB1','','WK','WB2','WN2','WR2'],
                        ['WP1','WP2','WP3','','WP5','WP6','WP7','WP8'],
                        ['','','','WQ','','','',''],
                        ['','','','WP4','','','',''],
                        ['','','','BP4','','','',''],
                        ['','','','BQ','','','',''],
                        ['BP1','BP2','BP3','','BP5','BP6','BP7','BP8'],
                        ['BR1','BN1','BB1','','BK','BB2','BN2','BR2']];

            $this->assertEquals($expected,$result);

    }
    public function test_queen_moves_diagonally(){

        $this->foo->invokeArgs($this->stub, array('1. e4 c5 2. Qf3 Qb6'));
        $result = $this->stub->getPositions();

            $expected = [['WR1','WN1','WB1','','WK','WB2','WN2','WR2'],
                        ['WP1','WP2','WP3','WP4','','WP6','WP7','WP8'],
                        ['','','','','','WQ','',''],
                        ['','','','','WP5','','',''],
                        ['','','BP3','','','','',''],
                        ['','BQ','','','','','',''],
                        ['BP1','BP2','','BP4','BP5','BP6','BP7','BP8'],
                        ['BR1','BN1','BB1','','BK','BB2','BN2','BR2']];

            $this->assertEquals($expected,$result);

    }

    // public function testPawn_advances()
    // {
    //
    // $result = $this->stub->getPositions();
    //
    //     $expected = [['WR1','WN1','WB1','WQ','WK','WB2','WN2','WR2'],
    //                 ['WP1','WP2','WP3','WP4','','WP6','WP7','WP8'],
    //                 ['','','','','','','',''],
    //                 ['','','','','WP5','','',''],
    //                                  ['','','','','','','',''],
    //                                  ['','','BN1','','','','',''],
    //                                  ['BP1','BP2','BP3','BP4','BP5','BP6','BP7','BP8'],
    //                                  ['BR1','','BB1','BQ','BK','BB2','BN','BR2']
    //                                  ];
    //
    //     $this->assertEquals($expected,$result);
    // }

    protected static function getMethod($name) {

        $class = new \ReflectionClass('\Game\Chess');
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}

?>
