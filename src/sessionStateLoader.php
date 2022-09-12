<?php
require_once('gameStateLoader.php');
session_start();


class SessionStateLoader extends GameStateLoader {
    private const RED_CHAR = "R";
    private const YELLOW_CHAR = "Y";
    private const EMPTY_CHAR = "S";
    private const ROW_BREAK_CHAR = "|";

    public function SaveCells(Map $map)
    {
        $chars = "";
        for ($y=0; $y < Map::MAP_HEIGHT; $y++) { 
            for ($x=0; $x < Map::MAP_WIDTH; $x++) {
                if($map->GetCell($x, $y) == CellValue::Red) 
                    $chars .= self::RED_CHAR;
                elseif($map->GetCell($x, $y) == CellValue::Yellow) 
                    $chars .= self::YELLOW_CHAR;
                elseif($map->GetCell($x, $y) == CellValue::Empty) 
                    $chars .= self::EMPTY_CHAR;
                else
                    throw new Error("Undefined cell cannot be saved");
            }

            if($y != Map::MAP_HEIGHT - 1)
                $chars .= self::ROW_BREAK_CHAR;
        }
        $_SESSION['cells'] = $chars;
    }

    public function LoadCells()
    {
        if(empty($_SESSION['cells']))
            return FALSE;

        $lines = explode(self::ROW_BREAK_CHAR, $_SESSION['cells']);

        for ($y=0; $y < sizeof($lines); $y++) { 
            $cells[$y] = [];
            for ($x=0; $x < Map::MAP_WIDTH; $x++) {
                if($lines[$y][$x] == self::RED_CHAR) 
                    $cells[$y][$x] = CellValue::Red;

                if($lines[$y][$x] == self::YELLOW_CHAR) 
                    $cells[$y][$x] = CellValue::Yellow;

                if($lines[$y][$x] == self::EMPTY_CHAR) 
                    $cells[$y][$x] = CellValue::Empty;
            }
        }

        return $cells;
    }
}