<?php

declare(strict_types=1);

function combination(int $n, int $k): array
{
    if ($k === 0) {
        return [0];
    }
    
    $ans = [];
    for ($i = $n; 1 <= $i; $i--) {
        $sub_list = combination($i - 1, $k - 1);
        foreach ($sub_list as $sub) {
            $ans[] = (1 << ($i - 1)) + $sub;
        }
    }
    return $ans;
}

// 10進表記なので合ってるのかわかりにくい
// var_dump(combination(16, 4));

// 2進表記にするとちょっとわかりやすくなる
// var_dump(array_map('decbin', combination(16, 4)));

// 0埋めするともうちょっとわかりやすくなる
// var_dump(array_map(fn ($c) => str_pad(decbin($c), 16, '0', STR_PAD_LEFT), combination(16, 4)));

function visualize(int $placement): void
{
    for ($row = 0; $row <= 3; $row++) {
        for ($col = 0; $col <= 3; $col++) {
            if (($placement >> (15 - 4 * $row - $col)) % 2 === 1) {
                echo '#';
            } else {
                echo '.';
            }
        }
        echo "\n";
    }
    echo "\n";
}

// 表示してみよう
// foreach (combination(16, 4) as $placement) {
//     visualize($placement);
// }

function to_array(int $placement): array
{
    $matrix = array_fill(0, 4, array_fill(0, 4, []));
    for ($row = 0; $row <= 3; $row++) {
        for ($col = 0; $col <= 3; $col++) {
            $matrix[$row][$col] = ($placement >> (15 - 4 * $row - $col)) % 2;
        }
    }
    return $matrix;
}

// 配列になったのでPHPで扱いやすくなりました
// foreach (combination(16, 4) as $placement) {
//     var_dump(to_array($placement));
// }

function visualize_array(array $matrix): void
{
    for ($row = 0; $row <= 3; $row++) {
        for ($col = 0; $col <= 3; $col++) {
            if ($matrix[$row][$col] === 1) {
                echo '#';
            } else {
                echo '.';
            }
        }
        echo "\n";
    }
    echo "\n";
}

// 表示してみよう
// foreach (combination(16, 4) as $placement) {
//     visualize_array(to_array($placement));
// }

function is_connected($matrix): bool
{
    $q = new SplQueue();
    $reached = array_fill(0, 4, array_fill(0, 4, 0));
    
    for ($row = 0; $row <= 3; $row++) {
        for ($col = 0; $col <= 3; $col++) {
            if ($matrix[$row][$col] === 1) {
                $q->enqueue([$row, $col]);
                break 2;
            }
        }
    }
    
    while (!($q->isEmpty())) {
        $cell = $q->dequeue();
        [$row, $col] = $cell;
        $reached[$row][$col] = 1;
        if (0 < $row && $matrix[$row - 1][$col] === 1 && !$reached[$row - 1][$col]) {
            $q->enqueue([$row - 1, $col]);
        }
        if ($row < 3 && $matrix[$row + 1][$col] === 1 && !$reached[$row + 1][$col]) {
            $q->enqueue([$row + 1, $col]);
        }
        if (0 < $col && $matrix[$row][$col - 1] === 1 && !$reached[$row][$col - 1]) {
            $q->enqueue([$row, $col - 1]);
        }
        if ($col < 3 && $matrix[$row][$col + 1] === 1 && !$reached[$row][$col + 1]) {
            $q->enqueue([$row, $col + 1]);
        }
    }
    
    return $reached === $matrix;
}

// 辺で連結なものだけ表示してみよう
foreach (combination(16, 4) as $placement) {
    if (is_connected(to_array($placement))) {
        visualize_array(to_array($placement));
    }
}
