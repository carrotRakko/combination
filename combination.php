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
    for ($col = 0; $col <= 3; $col++) {
        for ($row = 0; $row <= 3; $row++) {
            if (($placement >> (15 - 4 * $col - $row)) % 2 === 1) {
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
foreach (combination(16, 4) as $placement) {
    visualize($placement);
}
