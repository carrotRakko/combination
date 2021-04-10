<?php

function rotate_around_x(array $dim_3_matrix): array
{
    $new_dim_3_matrix = array_fill(
        0,
        count($dim_3_matrix),
        array_fill(
            0,
            count($dim_3_matrix[0][0]),
            array_fill(
                0,
                count($dim_3_matrix[0]),
                null,
            ),
        ),
    );
    for ($x = 0; $x < count($dim_3_matrix); $x++) {
        for ($y = 0; $y < count($dim_3_matrix[0][0]); $y++) {
            for ($z = 0; $z < count($dim_3_matrix[0]); $z++) {
                $new_dim_3_matrix[$x][$y][$z] = $dim_3_matrix[$x][$z][count($dim_3_matrix[0][0]) - 1 - $y];
            }
        }
    }
    return $new_dim_3_matrix;
}

function rotate_around_y(array $dim_3_matrix): array
{
    $new_dim_3_matrix = array_fill(
        0,
        count($dim_3_matrix[0][0]),
        array_fill(
            0,
            count($dim_3_matrix[0]),
            array_fill(
                0,
                count($dim_3_matrix),
                null,
            ),
        ),
    );
    for ($x = 0; $x < count($dim_3_matrix[0][0]); $x++) {
        for ($y = 0; $y < count($dim_3_matrix[0]); $y++) {
            for ($z = 0; $z < count($dim_3_matrix); $z++) {
                $new_dim_3_matrix[$x][$y][$z] = $dim_3_matrix[$z][$y][count($dim_3_matrix[0][0]) - 1 - $x];
            }
        }
    }
    return $new_dim_3_matrix;
}

$transformation_list = [
    function (array $dim_3_matrix): array {
        
    },
];

$dim_3_matrix = [
    [
        [1,  2,  3,  4],
        [5,  6,  7,  8],
        [9, 10, 11, 12],
    ],
    [
        [13, 14, 15, 16],
        [17, 18, 19, 20],
        [21, 22, 23, 24],
    ],
];

var_dump($dim_3_matrix);

var_dump(rotate_around_x($dim_3_matrix));

var_dump(rotate_around_y($dim_3_matrix));
