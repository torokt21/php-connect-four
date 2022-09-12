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
}