<?php declare(strict_types=1);
namespace PurpleHexagon\Puzzle;

use LogicException;
use RuntimeException;
use NumPHP\Core\NumArray;

class PuzzleEngine
{
    /**
     * The size of the puzzle, i.e. 9 or 16
     * @var int
     */
    protected $puzzleSize;

    /**
     * The size of one dimension of the puzzle, a puzzle of size 9 will have
     * a dimension of 3 for example
     * @var int
     */
    protected $dimension;

    /**
     * Instance of NumArray representing the puzzle tile positions as a matrix
     * which will be initialised with a multidimentional array
     *
     * Example of a solved puzzle:
     * [
     *     [1, 1, 1],
     *     [1, 1, 1],
     *     [1, 1, 0],
     * ]
     *
     * @var array
     */
    protected $puzzleMatrix;

    /**
     * A shuffled array of tile indexes to be mutated as the puzzle is played
     * @var array
     */
    protected $tiles = [];

    /**
     * Holds the solution for the puzzle, i.e. the tiles in the correct order
     * @var array
     */
    protected $solution = [];

    /**
     * Whether the puzzle has been solved
     * @var boolean
     */
    protected $isSolved = false;

    /**
     * Ensures the puzzle size is a square number. If it is then initialise tiles
     * and solution properties to an array of indexes. Then mutates the tiles
     * property using shuffle for psuedo random puzzle start point.
     *
     * Then initialises a matrix with the final element set to 0 as the last
     * tile will always be empty.
     *
     * @param int $puzzleSize
     */
    public function __construct(int $puzzleSize)
    {
        $this->ensureIsSquareGuard($puzzleSize);
        $this->tiles = $this->solution = range(1, $puzzleSize);
        shuffle($this->tiles);

        $this->puzzleSize = $puzzleSize;
        $this->dimension = (int) sqrt($puzzleSize);

        foreach (range(1, $this->dimension) as $x) {
            $puzzleRow = array_fill(0, $this->dimension, 1);
            if ($x == $this->dimension) $puzzleRow[$x - 1] = 0;
            $puzzleAsMultiDimensionalArray[] = $puzzleRow;
        }

        $this->puzzleMatrix = new NumArray(
            $puzzleAsMultiDimensionalArray
        );
    }

    /**
     * Return the tiles
     * @return array
     */
    public function getTiles()
    {
        return $this->tiles;
    }

    /**
     * Return the tiles
     * @return array
     */
    public function getPuzzleMatrix()
    {
        return $this->puzzleMatrix;
    }

    /**
     * Update the puzzle matrix and tile properties which represents a move
     * @param  int      $from
     * @param  int      $to
     * @return NumArray
     */
    public function move(int $from, int $to): NumArray
    {
        $move = $this->createMoveMatrix($from, $to);
        $newPuzzleMatrix = $this->puzzleMatrix->add($move);
        $this->ensureMoveValid($newPuzzleMatrix->getData());
        $this->puzzleMatrix = $newPuzzleMatrix;

        $fromValue = $this->tiles[$from - 1];
        $toValue = $this->tiles[$to - 1];
        $this->tiles[$from - 1] = $toValue;
        $this->tiles[$to - 1] = $fromValue;

        if ($this->tiles === $this->solution) {
            $this->isSolved = true;
        }

        return $newPuzzleMatrix;
    }

    /**
     * Check if puzzleSize is a square number and throw exception if not
     * @param  int    $puzzleSize
     * @throws LogicException
     */
    protected function ensureIsSquareGuard(int $puzzleSize)
    {
        $squareRootOfPuzzleSize = sqrt($puzzleSize);
        $remainder = fmod($squareRootOfPuzzleSize, 1);

        // $remainder will be set to float(0) if the puzzle size is a square number
        // and want to use strict type checking
        if ($remainder !== 0.0) {
            throw new LogicException(
                sprintf(
                    "%s::%s - Puzzles must be a square, please provide a valid puzzle size",
                    __CLASS__,
                    __FUNCTION__
                )
            );
        }
    }

    /**
     * Step through the multidimensional array and ensure there are no elements
     * with a value of 2, as this would mean the tile being moved to is occupied
     * @param array $puzzle    Puzzle matrix in multidimentional array form
     */
    protected function ensureMoveValid(array $puzzle)
    {
        array_walk_recursive($puzzle, function ($item, $key) {
            if ($item === 2 || $item === -1) {
                throw new RuntimeException(
                    sprintf(
                        "%s::%s - Invalid move made, please ensure tiles are orthogonally adjacent",
                        __CLASS__,
                        __FUNCTION__
                    )
                );
            }
        });
    }

    /**
     * Returns an array where the first element is the row position and the
     * second element is the index in that row
     * @param  int    $moveValue
     * @return array
     */
    protected function getPosition(int $moveValue): array
    {
      $rowPositionMagnitude = (int) ($moveValue / $this->dimension);

      if ($moveValue % $this->dimension !== 0) $rowPosition = $rowPositionMagnitude + 1;
      else $rowPosition = $rowPositionMagnitude;

      $indexPosition = (int) ($moveValue - (($rowPosition - 1) * $this->dimension));

      return [$rowPosition, $indexPosition];
    }

    /**
     * Initialises and returns a matrix which represents a move
     *
     * Example move from tile 9 to tile 6:
     *
     *   0  0  0
     *   0  0 -1
     *   0  0  1
     *
     * @param  array    $from         A tile index to move from
     * @param  array    $to           A tile index to move to
     * @return NumArray $moveMatrix   Move represented as matrix
     */
    protected function createMoveMatrix(int $from, int $to): NumArray
    {
        $moveArray = [];
        $fromPosition = $this->getPosition($from);
        $toPosition = $this->getPosition($to);

        // Iterate by demension to generate each puzzle row, check when
        // the iteration is for a row that has been updated and then
        // set the correct element/s in row for from and to move
        foreach (range(1, $this->dimension) as $rowIndex) {
            $puzzleRow = array_fill(0, $this->dimension, 0);
            if ($rowIndex === $fromPosition[0]) $puzzleRow[$fromPosition[1] - 1] = -1;
            if ($rowIndex === $toPosition[0]) $puzzleRow[$toPosition[1] - 1] = 1;

            $moveArray[] = $puzzleRow;
        }

        $moveMatrix = new NumArray(
            $moveArray
        );

        return $moveMatrix;
    }

    /**
     * Guard against being able to make illegal moves across more than one tile
     * @param  array $fromPosition
     * @param  array $toPosition
     * @throws RuntimeException
     */
    protected function adjacestTileGuard(array $fromPosition, array $toPosition)
    {
        // If move is in the same row the position indexes should only be one apart
        if ($fromPosition[0] === $toPosition[0]) {
            if (abs($fromPosition[1] - $toPosition[1]) !== 1) {
                throw new RuntimeException(
                    sprintf(
                        "%s::%s - Invalid move made, those squares arent together!",
                        __CLASS__,
                        __FUNCTION__
                    )
                );
            }
        }

        if (abs($fromPosition[0] - $toPosition[0]) === 1) {
            if ($fromPosition[1] !== $toPosition[1]) {
                throw new RuntimeException(
                    sprintf(
                        "%s::%s - Invalid move made, those squares arent together!",
                        __CLASS__,
                        __FUNCTION__
                    )
                );
            }
        }
    }

    /**
     * Getter for isSolved class property
     * @return bool    True if the puzzle is solved
     */
    public function getIsSolved(): bool
    {
        return $this->isSolved;
    }
}
