<?php 
require("src/game.php");
require("src/sessionStateLoader.php");

$loader = new sessionStateLoader();
var_dump($_SESSION);
$game = new Game($loader);
$game->Restart();
$game->MakeMove(Player::Red, 2);    
$game->Save();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connect four</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
        <table id="game-table">
            <?php 
            for ($y=0; $y < Map::MAP_HEIGHT; $y++) { 
                echo "<tr>";
                for ($x=0; $x < Map::MAP_WIDTH; $x++) {
                    $cellValue = $game->Map->GetCell($x,$y);

                    switch ($cellValue) {
                        case CellValue::Empty:
                            $cellClass = "cell-empty";
                            break;
                        case CellValue::Red:
                            $cellClass = "cell-red";
                            break;
                        case CellValue::Yellow:
                            $cellClass = "cell-yellow";
                            break;
                    }

                    ?>
                    <td class="game-cell <?php echo $cellClass ?>">
                    
                    </td>
                    <?php
                }
                echo "</tr>";
            }

            ?>
        </table>
    </div>
</body>
</html>