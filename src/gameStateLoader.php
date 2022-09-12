<?php 
abstract class GameStateLoader
{
    abstract public function SaveCells(Map $map);

    abstract public function LoadCells();
}