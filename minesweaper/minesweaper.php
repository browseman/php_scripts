<?php

// Output the menu for choosing game diff.
echo 'Choose dificulty: [1-4]'.PHP_EOL;
echo '1. Begginer (9x9 - 10Mines)'.PHP_EOL;
echo '2. Intermediate (16x16 - 49Mines)'.PHP_EOL;
echo '3. Expert (30x16 - 99Mines)'.PHP_EOL;
echo '4. Custom (XX x YY - ZZ Mines)'.PHP_EOL.PHP_EOL;

// User input
$choice = readline('Enter number between [1-4]: ');

// Output the choosed number
echo "You choosed: $choice".PHP_EOL;
if ($choice == 1) {
    // Fill initialization vars (Lvl: Begginer)
    $num_mines = 10;
    $num_rows = 9;
    $num_cols = 9;
    $num_cells = ($num_rows * $num_cols) - 1;
}
elseif ($choice == 2) {
    // Fill initialization vars (Lvl: Intermediate)
    $num_mines = 49;
    $num_rows = 16;
    $num_cols = 16;
    $num_cells = ($num_rows * $num_cols) - 1;    
}
elseif ($choice == 3) {
    // Fill initialization vars (Lvl: Expert)
    $num_mines = 99;
    $num_rows = 30;
    $num_cols = 16;
    $num_cells = ($num_rows * $num_cols) - 1;    
}
elseif ($choice == 4) {
    // Fill initialization vars (Lvl: Custom)
    $num_mines = readline('Enter number of mines: ');
    $num_rows = readline('Enter number of rows: ');
    $num_cols = readline('Enter number of colums: ');
    $num_cells = ($num_rows * $num_cols) - 1;  
    if ($num_cells < $num_mines) {
        echo "ERROR: Number of Mines exceeds the max. cells! Exiting!".PHP_EOL;
        exit;
    }
}
else {
    echo "ERROR: You've entered incorrect number!".PHP_EOL;
    exit;
}

//echo "DEBUG -> Num. of Mines: $num_mines".PHP_EOL;
//echo "DEBUG -> Num. of Rows: $num_rows".PHP_EOL;
//echo "DEBUG -> Num. of Colums: $num_cols".PHP_EOL;
//echo "DEBUG -> Num. of Cells: $num_cells".PHP_EOL;

// Fill corners vars
$LUC = 0;
$RUC = $num_cols - 1;
$LBC = $num_cells - $num_cols + 1;
$RBC = $num_cells;

// Define the arrays which we will use
$arr_mines = [];
$arr_mines_position = [];
$arr_mine_near_positions = [];


// Fill array with zeros
for ($i = 0; $i <= $num_cells; $i++) {
    $arr_mines[$i] = '0';
}


// Generate position for the mines
for ($i = 0; $i < $num_mines; $i++) {
    // take random number
    $rand_num = rand(0, $num_cells);

    // check if the number was not choosen already
    if ( $arr_mines[$rand_num] == '*' ) {
        $i--;
    }
    else {
        // Laying down the mines
        $arr_mines[$rand_num] = '*';

        // Remember the mines position
        $arr_mines_position[$i] = $rand_num;
    }
}

// Sort the arrya for ease of debuging 
sort($arr_mines_position);
//var_dump($arr_mines);
//var_dump($arr_mines_position);

// Create Hints about mines position
foreach ($arr_mines_position as $value) {
    // All possible near possitions
    // X + 1
    // X - 1
    // X - $num_cols
    // X - $num_cols + 1
    // X - $num_cols - 1 
    // X + $num_cols
    // X + $num_cols + 1
    // X + $num_cols - 1

    
    // Define the array which we will use for filling near possitions
    $arr_mine_near_positions = [];
//    echo "DEBUG -> Mine_Possition: $value".PHP_EOL;
    
    
    // Fill the closest cells if mine is planted in corners
    if (($arr_mines[$LUC] == '*') && ($LUC == $value)) {
        $arr_mine_near_positions = [
            '0' => $value + 1,
            '1' => $value + $num_cols,
            '2' => $value + $num_cols + 1
        ];
//        echo "DEBUG -> LUC Triggerd".PHP_EOL;
    }
    if (($arr_mines[$RUC] == '*') && ($RUC == $value)) {
        $arr_mine_near_positions = [
            '0' => $value - 1,
            '1' => $value + $num_cols,
            '2' => $value + $num_cols - 1
        ];
//        echo "DEBUG -> RUC Triggerd".PHP_EOL;
    }
    if (($arr_mines[$LBC] == '*') && ($LBC == $value)) {
        $arr_mine_near_positions = [
            '0' => $value + 1,
            '1' => $value - $num_cols,
            '2' => $value - $num_cols + 1
        ];
//        echo "DEBUG -> LBC Triggerd".PHP_EOL;
    }
    if (($arr_mines[$RBC] == '*') && ($RBC == $value)) {
        $arr_mine_near_positions = [
            '0' => $value - 1,
            '1' => $value - $num_cols,
            '2' => $value - $num_cols - 1
        ];
//        echo "DEBUG -> RBC Triggerd".PHP_EOL;
    }
    
    
    // Fill the closest cells if mine is planted in sides of rectangle
    //
    // Left side of the rectange
    foreach (range($LUC+$num_cols, $LBC-$num_cols, $num_cols) as $position) {
        if (($arr_mines[$position] == '*') && ($position == $value)) {
            $arr_mine_near_positions = [
                '0' => $value + 1,
                '1' => $value - $num_cols,
                '2' => $value - $num_cols + 1,
                '3' => $value + $num_cols,
                '4' => $value + $num_cols + 1,
            ];
//            echo "DEBUG -> DEBUG -> Trrigered LEFT_SIDE: $position".PHP_EOL;
        }
    }

    // Right side of the rectange
    foreach (range($RUC+$num_cols, $RBC-$num_cols, $num_cols) as $position) {
        if (($arr_mines[$position] == '*') && ($position == $value)) {
            $arr_mine_near_positions = [
                '0' => $value - 1,
                '1' => $value - $num_cols,
                '2' => $value - $num_cols - 1,
                '3' => $value + $num_cols,
                '4' => $value + $num_cols - 1,
            ];
//            echo "DEBUG -> Trrigered RIGHT_SIDE: $position".PHP_EOL;
        }        
    }

    // Upper side of the rectange
    foreach (range($LUC+1, $RUC-1) as $position) {
        if (($arr_mines[$position] == '*') && ($position == $value)) {
            $arr_mine_near_positions = [
                '0' => $value + 1,
                '1' => $value - 1,
                '2' => $value + $num_cols,
                '3' => $value + $num_cols + 1,
                '4' => $value + $num_cols - 1,
            ];
//            echo "DEBUG -> Trrigered UPPER_SIDE: $position".PHP_EOL;
        }
    }

    // Down side of the rectange
    foreach (range($LBC+1, $RBC-1) as $position) {
        if (($arr_mines[$position] == '*') && ($position == $value)) {
            $arr_mine_near_positions = [
                '0' => $value + 1,
                '1' => $value - 1,
                '2' => $value - $num_cols,
                '3' => $value - $num_cols + 1,
                '4' => $value - $num_cols - 1,
            ];
//            echo "DEBUG -> Trrigered DOWN_SIDE: $position".PHP_EOL;
        }
    }

    // If the Bomb is in the middle use 8 near possitions
    if (empty($arr_mine_near_positions)) {
        $arr_mine_near_positions = [
            '0' => $value + 1,
            '1' => $value - 1,
            '2' => $value - $num_cols,
            '3' => $value - $num_cols + 1,
            '4' => $value - $num_cols - 1,
            '5' => $value + $num_cols,
            '6' => $value + $num_cols + 1,
            '7' => $value + $num_cols - 1,
        ];
//        echo "DEBUG -> FULL Triggerd".PHP_EOL;
    }
  
    // Insert the hints in to main_array if near is not Bomb
    foreach ($arr_mine_near_positions as $near_cell) {
        if ($arr_mines[$near_cell] !== '*') {
            $arr_mines[$near_cell]=++$arr_mines[$near_cell];
        }
    }
}


// Output first line
for ($i = 0; $i < $num_rows; $i++) {
    echo '----';
}
echo '-'.PHP_EOL;

// Output table
for ($i = 0; $i < $num_cols; $i++) {
    for ($j = 0 ; $j < $num_rows; $j++) {
        echo '| '.$arr_mines[($i * $num_cols + $j)] . ' ';
    }
    echo '|'.PHP_EOL;
}
// Output last line
for ($i = 0; $i < $num_rows; $i++) {
    echo '----';
}
echo '-'.PHP_EOL;


// Use old simpler output format:
for ($i = 0; $i < $num_cols; $i++) {
    for ($j = 0 ; $j < $num_rows; $j++) {
        echo $arr_mines[($i * $num_cols + $j)] . ' ';
    }
    echo PHP_EOL;
}