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

    function __construct(GameStateLoader $loader) {
        $this->Map = new Map();
        $this->loader = $loader;
        $this->Map->SetCells = $this->loader->LoadCells();
    }

    function Restart()
    {
        $this->Map->Clear();

        $this->loader->SaveCells($this->Map);
    }

    function MakeMove(Player $player, int $column) {
        // Column full
        if($this->Map->GetCell($column, 0) !== CellValue::Empty)
            return FALSE;
        
        if($player == Player::Red)
            $cellVal = CellValue::Red;

        if($player == Player::Yellow)
            $cellVal = CellValue::Yellow;

        for ($y=0; $y < Map::MAP_HEIGHT; $y++) { 
            if(($y == Map::MAP_HEIGHT - 1 && $this->Map->GetCell($column, $y + 1) == CellValue::Empty) || $this->Map->GetCell($column, $y + 1) != CellValue::Empty)
            {
                $this->Map->SetCell($column, $y, $cellVal);
            }
        }

        $this->loader->SaveCells($this->Map);
    }
}