<?php

// INPUT HERE
$inputs = [
    [8 * (10 ** 7), 8 * (10 ** 7) + 448347],
    [10 ** 8 - 2 * 10 ** 7, 10 ** 8 - 1],
    [10 ** 7, 10 ** 7 + 10 ** 5],
    [10 ** 6, 10 ** 6 + 10 ** 4],
    [245656, 306546],
    [14, 354656],
    [999, 124554],
    [8 * (10 ** 7), 8 * (10 ** 7) + 448347],
    [10 ** 8 - 2 * 10 ** 7, 10 ** 8 - 1],
    [10 ** 7, 10 ** 7 + 10 ** 5],
    [10 ** 6, 10 ** 6 + 10 ** 4],
    [245656, 306546],
    [14, 354656],
    [999, 124554],
    [8 * (10 ** 7) + 10 ** 4, 8 * (10 ** 7) + 448347 + 10 ** 4],
    [10 ** 8 - 2 * 10 ** 7 + 10 ** 4, 10 ** 8 - 1],
    [10 ** 7 + 10 ** 4, 10 ** 7 + 10 ** 5 + 10 ** 4],
    [10 ** 6 + 10 ** 4, 10 ** 6 + 10 ** 4 + 10 ** 4],
    [245656 + 10 ** 4, 306546 + 10 ** 4],
    [14 + 10 ** 4, 354656 + 10 ** 4],
];


// START SOLVING
ini_set('memory_limit', '1G');

$origin_inputs = $inputs;
$primes = [];
$results = [];
$gap = 100000;


function mergeRanges() {
    global $inputs;
    global $gap;

    usort($inputs, function($a, $b) {
        return $a[0] - $b[0];
    });

    $merged = [];
    foreach ($inputs as $range) {
        if (empty($merged) || $merged[count($merged) - 1][1] - 1 < $range[0]) {
            $merged[] = $range;
        } else {
            $merged[count($merged) - 1][1] = max($merged[count($merged) - 1][1], $range[1]);
        }
    }

    for ($i = 0; $i < count($merged); $i++) {
        $merged[$i][0] += 1;
        $merged[$i][1] -= 1;
    }
    
    $split_merged = [];
    
    for ($i = 0; $i < count($merged); $i++) {
        $range = $merged[$i];
        $start = $range[0];
        $end = $range[1];
        $total_numbers = $end - $start + 1;

        if ($total_numbers < 1) {
            continue;
        }
        
        if ($total_numbers > $gap) {
            $current_start = $start;
            while ($current_start <= $end) {
                $current_end = min(floor($current_start / $gap) * $gap + $gap - 1, $end);
                $split_merged[] = [$current_start, $current_end];
                $current_start = $current_end + 1;
            }
        } else {
            $split_merged[] = $range;
        }
    }

    $merged = $split_merged;

    $inputs = $merged;
}


function sieve() {
    global $inputs;
    global $primes;

    if (!count($inputs)) {
        return;
    }

    $limit = floor(sqrt($inputs[count($inputs) - 1][1])) + 1;
    $sieve = array_fill(0, $limit + 1, true);
    $sieve[0] = $sieve[1] = false;
    
    for ($i = 2; $i * $i <= $limit; $i++) {
        if ($sieve[$i]) {
            for ($j = $i * $i; $j <= $limit; $j += $i) {
                $sieve[$j] = false;
            }
        }
    }

    $primes = $sieve;
}


function hasSameFirstLastDigit($n) {
    $str = (string) $n;
    return $str[0] == $str[strlen($str) - 1];
}


function segmentedSieve($low, $high, &$total) {
    global $primes;
    global $results;
    global $gap;

    if ($low > $gap) {
        $str_low = (string) $low;
        $first_low = $str_low[0];
        if (!in_array($first_low, ['1', '3', '7', '9'])) {
            return;
        }
    }

    $limit = floor(sqrt($high)) + 1;
    $n = $high - $low + 1;
    $mark = array_fill(0, $n, true);
    $primes_idx = [];

    for ($i = 2; $i <= $limit; $i++) {
        if ($primes[$i]) {
            $low_lim = max($i * $i, $low + ($i - $low % $i) % $i);
            for ($j = $low_lim; $j <= $high; $j += $i) {
                $mark[$j - $low] = false;
            }
        }
    }

    $_low = $low;
    if ($low == 2) {
        $results[] = 2;
        $total++;
        $_low = 3;
    } else if ($low % 2 == 0) {
        $_low++;
    }
    
    $step = 2;
    $step_after = 2;
    if ($low >= $gap) {
        $step_after = 10;
    }

    for ($i = $_low; $i <= $high; $i += $step) {
        if ($mark[$i - $low] && hasSameFirstLastDigit($i)) {
            $step = $step_after;
            $results[] = $i;
            $total++;
        }
    }

}


function binarySearchStart($values, $start) {
    $low = 0;
    $high = count($values) - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if ($values[$mid] <= $start) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return $low < count($values) ? $low : -1;
}


function binarySearchEnd($values, $end) {
    $low = 0;
    $high = count($values) - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if ($values[$mid] < $end) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return $high >= 0 ? $high : -1;
}


function solve() {
    global $inputs;
    global $results;
    global $origin_inputs;

    $total = 0;
    
    foreach ($inputs as $range) {
        list($low, $high) = $range;
        segmentedSieve($low, $high, $total);
    }

    foreach($origin_inputs as $range) {
        $start = $range[0];
        $end = $range[1];

        $start_idx = binarySearchStart($results, $start);
        $end_idx = binarySearchEnd($results, $end);

        $sub_results = array_slice($results, $start_idx, $end_idx - $start_idx + 1);

        print_r($sub_results);
    }
    
}


function stats($callback) {
    $start = microtime(true);
    
    call_user_func($callback);
    
    $end = microtime(true);
    $time = $end - $start;
    echo "\nTime: $time";
}


function main() {
    global $primes;
    global $origin_inputs;

    mergeRanges();

    sieve();

    solve();

}


stats('main');


?>