<?php
abstract class GameStateLoader
{
    abstract public function SaveCells(Map $map);

    abstract public function LoadCells();

    abstract public function SaveMove(string $player, int $column);

    abstract public function LoadMoves();

    abstract public function SaveWinner(string $player);

    abstract public function LoadWinners();
}