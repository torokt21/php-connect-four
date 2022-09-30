<?php
require_once("map.php");
require_once("gameStateLoader.php");

enum CellValue
{
    case Red;
    case Yellow;
    case Empty;
}

enum Player
{
    case Red;
    case Yellow;
}

class Game
{
    public $Map;
    private $loader;

    function __construct(GameStateLoader $loader)
    {
        $this->Map = new Map();
        $this->loader = $loader;

        if ($cells = $this->loader->LoadCells())
            $this->Map->SetCells($cells);
        else ($this->Map->Clear());
    }

    function Restart()
    {
        $this->Map->Clear();

        $this->loader->SaveCells($this->Map);
    }

    function MakeMove(Player $player, int $column)
    {
        // Column full
        if ($this->Map->GetCell($column, 0) !== CellValue::Empty)
            return FALSE;

        if ($player == Player::Red)
            $cellVal = CellValue::Red;

        if ($player == Player::Yellow)
            $cellVal = CellValue::Yellow;

        $topEmptyCell = $this->Map->GetTopEmptyCell($column);

        if ($topEmptyCell !== FALSE) {
            $this->Map->SetCell($column, $topEmptyCell, $cellVal);
            $this->loader->SaveCells($this->Map);
            return TRUE;
        }

        return FALSE;
    }

    public function CheckForWinner()
    {
        for ($col = 0; $col < Map::MAP_WIDTH; $col++) {
            for ($row = $this->Map->GetTopEmptyCell($col) + 1; $row < Map::MAP_HEIGHT; $row++) {
                for ($colDir = -1; $colDir <= 1; $colDir++) {
                    for ($rowDir = -1; $rowDir <= 1; $rowDir++) {
                        if ($rowDir === 0 && $colDir === 0)
                            continue;

                        $cell = $this->Map->GetCell($col, $row);
                        if ($this->CheckWinnerRecursive($col, $row, $colDir, $rowDir, $cell, 0)) {
                            return $cell == CellValue::Red ? Player::Red : Player::Yellow;
                        }
                    }
                }
            }
        }

        return FALSE;
    }

    private function CheckWinnerRecursive(int $col, int $row, int $dirCol, int $dirRow, CellValue $player, int $count)
    {
        $currentCell = $this->Map->GetCell($col, $row);
        if ($currentCell !== CellValue::Empty && $currentCell === $player) {
            // If 4
            if (++$count == 4)
                return $player;

            // Checking out of bounds
            $nextCol = $col + $dirCol;
            $nextRow = $row + $dirRow;
            if ($nextCol < 0 || $nextCol >= Map::MAP_WIDTH || $nextRow < 0 || $nextRow >= Map::MAP_HEIGHT)
                return FALSE;

            return $this->CheckWinnerRecursive($nextCol, $nextRow, $dirCol, $dirRow, $player, $count);
        } else {
            return FALSE;
        }
    }
}