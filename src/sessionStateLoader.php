<?php
require_once('gameStateLoader.php');
session_start();


class SessionStateLoader extends GameStateLoader
{
    private const RED_CHAR = "R";
    private const YELLOW_CHAR = "Y";
    private const EMPTY_CHAR = "S";
    private const COLUMN_DELIMITER = "|";

    /**
     * Stores cells session, such as SSSSRY|SSSSYY|....
     */
    public function SaveCells(Map $map)
    {
        $chars = "";
        for ($col = 0; $col < Map::MAP_WIDTH; $col++) {
            for ($row = 0; $row < Map::MAP_HEIGHT; $row++) {
                if ($map->GetCell($col, $row) == CellValue::Red)
                    $chars .= self::RED_CHAR;
                elseif ($map->GetCell($col, $row) == CellValue::Yellow)
                    $chars .= self::YELLOW_CHAR;
                elseif ($map->GetCell($col, $row) == CellValue::Empty)
                    $chars .= self::EMPTY_CHAR;
                else
                    throw new Error("Undefined cell cannot be saved");
            }

            if ($col != Map::MAP_WIDTH - 1)
                $chars .= self::COLUMN_DELIMITER;
        }
        $_SESSION['cells'] = $chars;
    }

    /**
     * Loads cells from session.
     */
    public function LoadCells()
    {
        if (empty($_SESSION['cells']))
            return FALSE;

        $columnStrings = explode(self::COLUMN_DELIMITER, $_SESSION['cells']);

        for ($col = 0; $col < sizeof($columnStrings); $col++) {
            $cells[$col] = [];
            for ($row = 0; $row < strlen($columnStrings[$col]); $row++) {
                if ($columnStrings[$col][$row] == self::RED_CHAR)
                    $cells[$col][$row] = CellValue::Red;

                if ($columnStrings[$col][$row] == self::YELLOW_CHAR)
                    $cells[$col][$row] = CellValue::Yellow;

                if ($columnStrings[$col][$row] == self::EMPTY_CHAR)
                    $cells[$col][$row] = CellValue::Empty;
            }
        }

        return $cells;
    }

    public function SaveMove(string $player, int $column)
    {
        if (!isset($_SESSION['moves'])) {
            $_SESSION['moves'] = [];
        }
        $id = new stdClass();
        $id->player = $player;
        $id->column = $column;
        $_SESSION['moves'][] = $id;
    }

    public function LoadMoves()
    {
        if (!isset($_SESSION['moves'])) {
            $_SESSION['moves'] = [];
        }

        return $_SESSION['moves'];
    }

    public function SaveWinner(string $player)
    {
        if (!isset($_SESSION['winners'])) {
            $_SESSION['winners'] = [];
        }

        $_SESSION['winners'][] = $player;
    }

    public function LoadWinners()
    {
        if (!isset($_SESSION['winners'])) {
            $_SESSION['winners'] = [];
        }

        return $_SESSION['winners'];
    }
}