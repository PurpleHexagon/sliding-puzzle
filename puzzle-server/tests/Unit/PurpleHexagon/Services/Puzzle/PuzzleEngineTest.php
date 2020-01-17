<?php declare(strict_types=1);
namespace Tests\Unit\PurpleHexagon\Services\Puzzle;

use NumPHP\Core\NumArray;
use PHPUnit\Framework\TestCase;
use PurpleHexagon\Services\Puzzle\PuzzleEngine;

/**
 * Class PuzzleEngineTest
 * @package Tests\Unit\PurpleHexagon\Services\Puzzle
 */
class PuzzleEngineTest extends TestCase
{
    /**
     * @var PuzzleEngine
     */
    protected $serviceUnderTest;

    /**
     * @inheritDoc
     */
    public function setUp()
    {
        parent::setUp();
        $this->serviceUnderTest = new PuzzleEngine(9);
    }

    /**
     * Given PuzzleEngine has been constructed
     * When getTiles is called
     * Then an array should be returned
     */
    public function testTilesPropertyIsAnArrayAfterConstruction()
    {
        $this->assertInternalType(
            'array',
            $this->serviceUnderTest->getTiles(),
            'getTiles should always return an array'
        );
    }

    /**
     * Given PuzzleEngine has been constructed
     * When getTiles is called
     * Then an array should be returned with the same number of elements as the puzzle size
     */
    public function testTilesPropertyIsCorrectSizeAfterConstruction()
    {
        $this->assertCount(
            9,
            $this->serviceUnderTest->getTiles(),
            'getTiles should have a count the same as puzzle size'
        );
    }

    /**
     * Given PuzzleEngine has been constructed
     * When the tiles are initialised
     * Then they should not be already solved
     */
    public function testTilesPropertyIsNotSolvedAfterConstruction()
    {
        $this->assertNotEquals(
            [1, 2, 3, 4, 5, 6, 7, 8, 9],
            $this->serviceUnderTest->getTiles(),
            'Puzzle tiles should be shuffled during construction'
        );
    }

    /**
     * Given PuzzleEngine has been constructed
     * When the tiles are initialised
     * Then the last tile should always be nine as this is the "empty" square to move to
     */
    public function testTheLastTileIsAlwaysNine()
    {
        $this->assertEquals(
            9,
            $this->serviceUnderTest->getTiles()[8],
            'Last puzzle tile should always be 9'
        );
    }

    /**
     * Given PuzzleEngine has been constructed
     * When the puzzleMatrix is initialised
     * Then PuzzleEngine::getPuzzleMatrix should return NumArray
     */
    public function testPuzzleMatrixPropertyIsInitialisedAsInstance()
    {
        $this->assertInstanceOf(
            NumArray::class,
            $this->serviceUnderTest->getPuzzleMatrix(),
            'The puzzle matrix should be instance of NumArray'
        );
    }

    /**
     * Given PuzzleEngine has been constructed
     * When the isSolved method is called
     * Then a boolean should be returned
     */
    public function testIsSolvedReturnsABoolean()
    {
        $this->assertInternalType(
            'bool',
            $this->serviceUnderTest->getIsSolved(),
            'isSolved should be a boolean'
        );
    }

    /**
     * Given PuzzleEngine has been constructed
     * When isSolved is initialised
     * Then it should be false
     */
    public function testIsSolvedIsFalseAfterConstruction()
    {
        $this->assertFalse($this->serviceUnderTest->getIsSolved(), "isSolved should be false");
    }

    /**
     * Given an instance of PuzzleEngine
     * When move method is called with a valid to and from position 
     * Then the tiles should switch place
     */
    public function testMoveToEmptyTileIsSuccessful()
    {
        $tiles = $this->serviceUnderTest->getTiles();
        $tileIndexToMove = 5;
        $toIndex = 8;
        $tileToMove = $tiles[$tileIndexToMove];
        $tileNine = $tiles[$toIndex];

        $this->serviceUnderTest->move($tileIndexToMove + 1, $toIndex + 1);

        $tilesAfterMove = $this->serviceUnderTest->getTiles();

        $this->assertEquals(
            $tileToMove,
            $tilesAfterMove[$toIndex]
        );

        $this->assertEquals(
            $tileNine,
            $tilesAfterMove[$tileIndexToMove]
        );
    }
}
