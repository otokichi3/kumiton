<?php
function get_kumis_by_level(array $pairs_by_level) : array {
    $kumi = [];
    $cnt  = count($pairs_by_level);
    $j    = 0;

    try {
        foreach ($pairs_by_level as $key => $member) {
            ++$j;
            for ($i = $j; $i < $cnt; $i++) {
                $arr = array_slice($pairs_by_level, $i, 1);
                $kumi[] = [$key => $member] + [key($arr) => current($arr)];
            }
        }
    } catch (Exception $e) {
        return $e->getMessage();
    } finally {
        return $kumi;
    }
}

function get_pairs_by_level(array $all_pairs, array $sum_list, array $sum_numbers) : array {
    $ret = [];

    foreach ($sum_numbers as $sum_number) {
        foreach ($sum_list as $key2 => $val2) {
            if ($val2 === $sum_number) {
                $ret[$sum_number][] = array_keys($all_pairs[$key2]);
            }
        }
    }
    return $ret;
}

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
    $pair= [];
    $cnt  = count($members);
    $j    = 0;

    try {
        foreach ($members as $key => $member) {
            ++$j;
            for ($i = $j; $i < $cnt; $i++) {
                $arr = array_slice($members, $i, 1);
                $pair[] = [$key => $member] + [key($arr) => current($arr)];
            }
        }
    } catch (Exception $e) {
        return $e->getMessage();
    } finally {
        return $pair;
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