<?php

declare(strict_types=1);

/**
 * @param int $n $_{n}C_{k}$ で言うところの `n`
 * @param int $k $_{n}C_{k}$ で言うところの `k`
 * 
 * @return array 組み合わせをビットで表したものの配列
 */
function bit_combination(int $n, int $k): array
{
    if ($k === 0) {
        return [0];
    }
    
    $bit_combination_list = [];
    for ($i = $n; 1 <= $i; $i--) {
        $sub_bit_combination_list = bit_combination($i - 1, $k - 1);
        foreach ($sub_bit_combination_list as $sub_bit_combination) {
            $bit_combination_list[] = (1 << ($i - 1)) + $sub_bit_combination;
        }
    }
    return $bit_combination_list;
}

/**
 * @param int $c ポリオミノを構成するタイルの数
 * @param int $bit_combination ビット形式の組み合わせ
 * 
 * @return array 2次元配列形式の組み合わせ
 */
function bit_combination_to_matrix_combination(int $c, int $bit_combination): array
{
    $matrix = array_fill(0, $c, array_fill(0, $c, []));
    for ($row = 0; $row < $c; $row++) {
        for ($col = 0; $col < $c; $col++) {
            $matrix[$row][$col] = ($bit_combination >> ($c * $c - 1 - $c * $row - $col)) % 2;
        }
    }
    return $matrix;
}

/**
 * @param array $matrix ポリオミノ（というかポリオミノもどき）を2次元配列で表したもの
 * 
 * @return bool 連結か？
 */
function is_connected(array $matrix): bool
{
    $q = new SplQueue();
    $reached = array_fill(0, count($matrix), array_fill(0, count($matrix[0]), 0));
    
    for ($row = 0; $row < count($matrix); $row++) {
        for ($col = 0; $col < count($matrix[0]); $col++) {
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
        if ($row < count($matrix) - 1 && $matrix[$row + 1][$col] === 1 && !$reached[$row + 1][$col]) {
            $q->enqueue([$row + 1, $col]);
        }
        if (0 < $col && $matrix[$row][$col - 1] === 1 && !$reached[$row][$col - 1]) {
            $q->enqueue([$row, $col - 1]);
        }
        if ($col < count($matrix[0]) - 1 && $matrix[$row][$col + 1] === 1 && !$reached[$row][$col + 1]) {
            $q->enqueue([$row, $col + 1]);
        }
    }
    
    return $reached === $matrix;
}

/**
 * @param array $matrix ポリオミノを2次元配列で表したもの
 */
function cut_off(array $matrix): array
{
    $row_cut_off = [];
    
    for ($row = 0; $row < count($matrix); $row++) {
        $empty = true;
        for ($col = 0; $col < count($matrix[0]); $col++) {
            if ($matrix[$row][$col] === 1) {
                $empty = false;
                break;
            }
        }
        if (!$empty) {
            $row_cut_off[] = $matrix[$row];
        }
    }
    
    $col_cut_off = array_fill(0, count($row_cut_off), []);
    for ($col = 0; $col <= count($row_cut_off[0]) - 1; $col++) {
        $empty = true;
        for ($row = 0; $row <= count($row_cut_off) - 1; $row++) {
            if ($row_cut_off[$row][$col] === 1) {
                $empty = false;
                break;
            }
        }
        if (!$empty) {
            for ($row = 0; $row <= count($row_cut_off) - 1; $row++) {
                $col_cut_off[$row][] = $row_cut_off[$row][$col];
            }
        }
    }
    
    return $col_cut_off;
}

/**
 * @param array $matrix ポリオミノを2次元配列で表したもの
 */
function rotate90(array $matrix): array
{
    $ans = array_fill(0, count($matrix[0]), array_fill(0, count($matrix), null));
    for ($row = 0; $row <= count($matrix) - 1; $row++) {
        for ($col = 0; $col <= count($matrix[0]) - 1; $col++) {
            $ans[$col][count($matrix) - 1 - $row] = $matrix[$row][$col];
        }
    }
    return $ans;
}

/**
 * @param int $c ポリオミノを構成するタイルの数
 * 
 * @return array ポリオミノを2次元配列で表したものの配列
 */
function find_polyomino_list(int $c): array
{
    // まずビット形式で組み合わせを列挙する
    $c_times_c_bit_combination_list = bit_combination($c * $c, $c);
    
    // 次にビット形式の組み合わせを2次元配列形式の組み合わせに変換する
    $c_times_c_matrix_combination_list = array_map(
        function (int $bit_combination) use ($c): array {
            return bit_combination_to_matrix_combination($c, $bit_combination);
        },
        $c_times_c_bit_combination_list
    );
    
    // 連結でないポリオミノ（というかポリオミノもどき）を除外する
    $duplicated_polyomino_list = array_values(array_filter($c_times_c_matrix_combination_list, 'is_connected'));
    
    // ポリオミノの周りの余計な行や列を切り取る
    $duplicated_polynomio_list = array_map('cut_off', $duplicated_polyomino_list);
    
    // ここにユニークなポリオミノを入れていく
    $polyomino_list = [];
    
    // 重複除去
    foreach ($duplicated_polynomio_list as $maybe_duplicated_polyomino) {
        $already_exists = false;
        foreach ($polyomino_list as $polyomino) {
            if ($polyomino === $maybe_duplicated_polyomino) {
                $already_exists = true;
                break;
            }
            if ($polyomino === rotate90($maybe_duplicated_polyomino)) {
                $already_exists = true;
                break;
            }
            if ($polyomino === rotate90(rotate90($maybe_duplicated_polyomino))) {
                $already_exists = true;
                break;
            }
            if ($polyomino === rotate90(rotate90(rotate90($maybe_duplicated_polyomino)))) {
                $already_exists = true;
                break;
            }
        }
        if (!$already_exists) {
            $polyomino_list[] = $maybe_duplicated_polyomino;
        }
    }
    
    return $polyomino_list;
}

/**
 * @param array $matrix ポリオミノを2次元配列で表したもの
 * 
 * @retunr void
 */
function visualize_matrix(array $matrix): void
{
    for ($row = 0; $row < count($matrix); $row++) {
        for ($col = 0; $col < count($matrix[0]); $col++) {
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

foreach (find_polyomino_list(4) as $polyomino) {
    visualize_matrix($polyomino);
}
