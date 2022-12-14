<?php
require("src/game.php");
require("src/sqlStateLoader.php");

const DEBUG = FALSE;

$loader = new sqlStateLoader();
$game = new Game($loader);
$player = Player::Red;
$bot = Player::Yellow;

if (isset($_REQUEST['restart']))
    $game->Restart();

$old_winner = $game->CheckForWinner();

if (isset($_REQUEST['column']) && is_numeric($_REQUEST['column'])) {
    if ($game->MakeMove($player, number_format($_REQUEST['column']))) {
        // Successfull move, bot's turn
        do {
            $success = $game->MakeMove($bot, rand(0, Map::MAP_WIDTH - 1));
        } while (!$success);

        // Redirect, to avoid form resubmission
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}

if ($winner = $game->CheckForWinner()) {
    if ($old_winner != $winner) {
        $loader->SaveWinner($winner);
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connnnect four</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Antonio:wght@700&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <h1>Co<span class="<?php echo rand(0, 1) === 0 ? "red-player-text" : "yellow-player-text" ?>">nnnn</span>ect
            Four
        </h1>
        <?php if ($winner) : ?>
        <div id="winner-box">
            <?php
                $class = $winner === Player::Red ? "red-player-text" : "yellow-player-text";
                $playerTxt = $winner === Player::Red ? "RED" : "YELLOW";
                ?>
            <?php
                echo "<span class='{$class}'>{$playerTxt}</span> WINS";
                ?>
        </div>
        <?php endif; ?>
    </header>

    <main>
        <form method="POST" id="game-form">
            <input id="column-input" name="column" type="hidden" value="" />
            <table id="game-table" class="<?php echo $game->gameOver ? "game-over" : "" ?>">
                <?php
                for ($row = 0; $row < Map::MAP_HEIGHT; $row++) {
                    echo "<tr>";
                    for ($col = 0; $col < Map::MAP_WIDTH; $col++) {
                        $cellValue = $game->Map->GetCell($col, $row);

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
                <td onclick="submitForm(<?php echo $col ?>)" class="game-cell <?php echo $cellClass ?>">
                    <?php echo DEBUG ? "{$col}-{$row}" : "" ?>
                </td>
                <?php
                    }
                    echo "</tr>";
                }

                ?>
            </table>
        </form>

        <div id="buttons-div">
            <form method="POST">
                <input class="button-39" name="restart" type="submit" value="Restart" />
            </form>
        </div>

        <div class="container">
            <div class="half-container">
                <span>Past wins</span>
                <div>
                    <?php
                    $pastWins = $loader->LoadWinners();
                    if (sizeof($pastWins) === 0) : ?>
                    <span>No games yet</span>
                    <?php else : ?>
                    <ol>
                        <?php foreach ($pastWins as $pwinner) :
                                $class = $pwinner === Player::Red ? "red-player-text" : "yellow-player-text";
                                $playerTxt = $pwinner === Player::Red ? "RED" : "YELLOW";
                            ?>
                        <li><span class="<?php echo $class ?>"><?php echo $playerTxt ?></span></li>
                        <?php endforeach; ?>
                    </ol>
                    <?php endif; ?>
                </div>
            </div>
            <div class="half-container text-right">
                <span>Moves</span>
                <div>
                    <?php
                    $moves = $loader->LoadMoves();
                    if (sizeof($moves) === 0) : ?>
                    <span>No moves yet</span>
                    <?php else : ?>
                    <ol>
                        <?php foreach ($moves as $mov) :
                                $class = $mov->player === Player::Red ? "red-player-text" : "yellow-player-text";
                                $playerTxt = $mov->player === Player::Red ? "RED" : "YELLOW";
                            ?>
                        <li><span class="<?php echo $class ?>"><?php echo $playerTxt  ?></span> - column
                            <?php echo $mov->column + 1 ?></li>
                        <?php endforeach; ?>
                    </ol>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

<script>
var form = document.getElementById("game-form");
var hiddenInput = document.getElementById("column-input");
const submitForm = (column) => {
    hiddenInput.value = column;
    form.submit();
    console.log(column);
};
</script>

</html>