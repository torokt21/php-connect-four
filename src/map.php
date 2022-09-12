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

    /** Sets all cells to Empty. */
    public function Clear()
    {
        for ($row = 0; $row < self::MAP_HEIGHT; $row++)
            for ($col = 0; $col < self::MAP_WIDTH; $col++)
                $this->SetCell($col, $row, CellValue::Empty);
    }

    /** Gets the specified cell value. */
    public function GetCell(int $column, int $row)
    {
        return $this->cells[$column][$row];
    }

    /** Sets the specified cell value. */
    public function SetCell(int $column, int $row, CellValue $cellValue)
    {
        $this->cells[$column][$row] = $cellValue;
    }

    /** Overwrites all cell data. */
    public function SetCells(array $cells)
    {
        if (sizeof($cells) != self::MAP_WIDTH) {
            throw new Error("Height mismatch in setcells");
        }

        if (sizeof($cells[0]) != self::MAP_HEIGHT) {
            throw new Error("Width mismatch in setcells");
        }

        $this->cells = $cells;
    }


    public function GetTopEmptyCell(int $column)
    {
        if ($column < 0 || $column >= self::MAP_WIDTH)
            throw new Error("Column {$column} is not in the map.");

        for ($row = Map::MAP_HEIGHT - 1; $row >= 0; $row--) {
            if ($this->GetCell($column, $row) == CellValue::Empty) {
                return $row;
            }
        }

        return false;
    }
}