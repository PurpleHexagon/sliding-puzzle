<?php
require __DIR__ . '/vendor/autoload.php';
use NumPHP\Core\NumArray;

class Puzzle
{
  protected $puzzleSize;
  protected $dimension;
  protected $puzzle = [];
  protected $tiles = [];
  protected $solution = [];
  protected $isSolved = false;

  public function getIsSolved()
  {
      return $this->isSolved;
  }

  public function __construct($puzzleSize)
  {
      $this->checkIsSquare($puzzleSize);

      $this->tiles = range(1, $puzzleSize);
      $this->solution = range(1, $puzzleSize);
      shuffle($this->tiles);

      $this->puzzleSize = $puzzleSize;
      $this->dimension = sqrt($puzzleSize);

      foreach (range(1, 3) as $x) {
          $puzzleRow = array_fill(0, $this->dimension, 1);
          if ($x == $this->dimension) $puzzleRow[$x - 1] = 0;
          $this->puzzle[] = $puzzleRow;
      }

      $this->puzzleMatrix = new NumArray(
          $this->puzzle
      );
  }

  protected function checkIsSquare(int $puzzleSize)
  {
      $squareRootOfPuzzleSize = 0 + sqrt($puzzleSize);
      $remainder = fmod($squareRootOfPuzzleSize, 1);

      if ($remainder !== 0.0) {
          throw new \Exception("Puzzles must be a square, sorry bruh!");
      }
  }

  public function move($from, $to)
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

      var_dump($this->tiles);
      var_dump($this->puzzleMatrix->getData());

      return $newPuzzleMatrix;
  }

  protected function ensureMoveValid($puzzle)
  {
    array_walk_recursive($puzzle, function ($item, $key) {
      if ($item === 2) {
        throw new \Exception("Invalid move made, cannot move to a occupied square!");
      }
    });
  }

  protected function getPosition($moveValue)
  {
    $rowPositionMagnitude = (int) ($moveValue / $this->dimension);

    if ($moveValue % $this->dimension !== 0) $rowPosition = $rowPositionMagnitude + 1;
    else $rowPosition = $rowPositionMagnitude;

    $indexPosition = (int) ($moveValue - (($rowPosition - 1) * $this->dimension));

    return [$rowPosition, $indexPosition];
  }

  protected function createMoveMatrix($from, $to)
  {
      $moveArray = [];
      $fromPosition = $this->getPosition($from);
      $toPosition = $this->getPosition($to);

      if ($fromPosition[0] === $toPosition[0]) {
          if (abs($fromPosition[1] - $toPosition[1]) !== 1) {
              throw new \Exception("Invalid move made, those squares arent together!");
          }
      }

      if (abs($fromPosition[0] - $toPosition[0]) === 1) {
          if ($fromPosition[1] !== $toPosition[1]) {
              throw new \Exception("Invalid move made, those squares arent together!");
          }
      }

      $count = 0;
      while ($count < $this->dimension) {
        $count++;
        $puzzleRow = array_fill(0, $this->dimension, 0);
        if ($count == $fromPosition[0]) $puzzleRow[$fromPosition[1] - 1] = -1;
        if ($count == $toPosition[0]) $puzzleRow[$toPosition[1] - 1] = 1;

        $moveArray[] = $puzzleRow;
      }
      $moveMatrix = new NumArray(
          $moveArray
      );

      return $moveMatrix;
  }
}

$puzzle = new Puzzle(9);
$isSolved = false;

while ($puzzle->getIsSolved() === false) {
  $from = readline("from: ");
  $to = readline("to: ");
  $isSolved = $puzzle->move($from, $to);
}
