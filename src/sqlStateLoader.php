<?php
require_once('gameStateLoader.php');


class sqlStateLoader extends GameStateLoader
{
    private const SQL_DB = "connnnect_four";
    private const SQL_USER = "t224";
    private const SQL_PASS = "t224";
    private const SQL_MOVES_TABLE = "cf_moves";
    private const SQL_WINNERS_TABLE = "cf_winners";
    private const SQL_CELLS_TABLE = "cf_cells";

    private $sql;

    private const RED_CHAR = "R";
    private const YELLOW_CHAR = "Y";
    private const EMPTY_CHAR = "S";
    private const COLUMN_DELIMITER = "|";

    function __construct()
    {
        $this->sql = mysqli_connect('localhost', self::SQL_USER, self::SQL_PASS, self::SQL_DB);
    }

    function __destruct()
    {
        mysqli_close($this->sql);
    }

    public function SaveMove(string $player, int $column)
    {
        $query = "INSERT INTO " . self::SQL_MOVES_TABLE . " (player, move_column) VALUES ('{$player}', '{$column}')";

        if (!mysqli_query($this->sql, $query)) {
            throw new Exception("Sikertelen beillesztés az adatbázisba!");
        }
    }

    public function LoadMoves()
    {
        $query = "SELECT * FROM " . self::SQL_MOVES_TABLE;

        $result = mysqli_query($this->sql, $query);
        $return = [];

        while ($row = $result->fetch_assoc()) {
            $item = new stdClass();
            $item->player = $row['player'];
            $item->column = $row['move_column'];
            $return[] = $item;
        }
        return $return;
    }

    public function ResetMoves()
    {
        $query = "DELETE FROM " . self::SQL_MOVES_TABLE;

        if (!mysqli_query($this->sql, $query)) {
            throw new Exception("Sikertelen törlés az adatbázisból!");
        }
    }

    public function SaveWinner(string $player)
    {
        $query = "INSERT INTO " . self::SQL_WINNERS_TABLE . " (player) VALUES ('{$player}')";

        if (!mysqli_query($this->sql, $query)) {
            throw new Exception("Sikertelen beillesztés az adatbázisba!");
        }
    }

    public function LoadWinners()
    {
        $query = "SELECT player FROM " . self::SQL_WINNERS_TABLE . " ORDER BY winner_id";

        $result = mysqli_query($this->sql, $query);
        $return = [];

        while ($row = $result->fetch_assoc()) {
            $return[] = $row['player'];
        }
        return $return;
    }

    /**
     * Stores cells in db, such as SSSSRY|SSSSYY|....
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

        $query = "INSERT INTO " . self::SQL_CELLS_TABLE . " (game_state) VALUES ('{$chars}')";

        if (!mysqli_query($this->sql, $query)) {
            throw new Exception("Sikertelen beillesztés az adatbázisba!");
        }
    }

    /**
     * Loads cells from db.
     */
    public function LoadCells()
    {
        $query = "SELECT game_state FROM " . self::SQL_CELLS_TABLE . " ORDER BY cell_state_id DESC";

        $result = mysqli_query($this->sql, $query);

        if ($result->num_rows == 0)
            return FALSE;

        $db_val = $result->fetch_assoc()["game_state"];

        $columnStrings = explode(self::COLUMN_DELIMITER, $db_val);

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
}