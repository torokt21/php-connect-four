<?php 

class Map 
{
    private $cells;

    public const MAP_HEIGHT = 6;
    public const MAP_WIDTH = 7;

    function __construct() 
    {
        $this->Clear();
    }

    public function Clear() 
    {
        for ($y=0; $y < self::MAP_HEIGHT; $y++) { 
            for ($x=0; $x < self::MAP_WIDTH; $x++) { 
                $this->cells[$x][$y] = CellValue::Empty;
            }
        }
    }

    public function GetCell(int $x, int $y) {
        return $this->cells[$x][$y];
    }

    
    public function SetCell(int $x, int $y, CellValue $cellValue) {
        $this->cells[$x][$y] = $cellValue;
    }

    public function SetCells(array $cells) {
        if(sizeof($cells) != self::MAP_HEIGHT) {
            throw new Error("Height mismatch in setcells");
        }

        if(sizeof($cells[0]) != self::MAP_WIDTH) {
            throw new Error("Width mismatch in setcells");
        }

        $this->cells = $cells;
    }
}