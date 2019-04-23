<?php
function get_all_sum(array $all_pairs)
{
    $sum_list = [];

    foreach ($all_pairs as $key => $pair) {
        $sum_list[] = array_sum($pair);
    }

    return $sum_list;
}

function get_all_pairs(array $members)
{
    $kumi = [];
    $cnt  = count($members);
    $j    = 0;

    try {
        foreach ($members as $key => $member) {
            ++$j;
            for ($i = $j; $i < $cnt; $i++) {
                $arr = array_slice($members, $i, 1);
                $kumi[] = [$key => $member] + [key($arr) => current($arr)];
            }
        }
    } catch (Exception $e) {
        return $e->getMessage();
    } finally {
        return $kumi;
    }
}

function make_pair($p1, $p2)
{
    return [$p1, $p2];
}

function dump($arg)
{
    echo '<pre>';
    var_export($arg);
    echo '</pre>';
}