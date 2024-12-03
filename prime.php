<?php

ini_set('memory_limit', '1G');

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


$primes = [];
$results = [];


function mergeRanges() {
    global $inputs;

    usort($inputs, function($a, $b) {
        return $a[0] - $b[0];
    });

    $merged = [];
    foreach ($inputs as $range) {
        if (empty($merged) || $merged[count($merged) - 1][1] < $range[0]) {
            $merged[] = $range;
        } else {
            $merged[count($merged) - 1][1] = max($merged[count($merged) - 1][1], $range[1]);
        }
    }

    $splitMerged = [];
    
    for ($i = 0; $i < count($merged); $i++) {
        $range = $merged[$i];
        $start = $range[0];
        $end = $range[1];
        $totalNumbers = $end - $start + 1;
        
        if ($totalNumbers > 100000) {
            $numSplits = ceil($totalNumbers / 100000);
            $splitSize = ceil($totalNumbers / $numSplits);
            
            for ($j = 0; $j < $numSplits; $j++) {
                $subStart = $start + ($j * $splitSize);
                $subEnd = min($subStart + $splitSize - 1, $end);
                $splitMerged[] = [$subStart, $subEnd];
            }
        } else {
            $splitMerged[] = $range;
        }
    }

    $merged = $splitMerged;

    $inputs = $merged;
}


function sieve() {
    global $inputs;
    global $primes;

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


function segmentedSieve($low, $high, &$total) {
    global $primes;
    global $results;

    $limit = floor(sqrt($high)) + 1;
    $n = $high - $low + 1;
    $mark = array_fill(0, $n, true);

    for ($i = 2; $i <= $limit; $i++) {
        if ($primes[$i]) {
            $low_lim = max($i * $i, $low + ($i - $low % $i) % $i);
            for ($j = $low_lim; $j <= $high; $j += $i) {
                $mark[$j - $low] = false;
            }
        }
    }

    $_low = $low;
    if ($low < 11) {
        $results[] = 2;
        $results[] = 3;
        $results[] = 5;
        $results[] = 7;
        $total += 4;

        $_low = 11;
    }

    $step = 1;
    for ($i = $_low; $i <= $high; $i += $step) {
        $last = $i % 10;
        $first = floor($i / 10 ** (strlen($i) - 1));
        if (in_array($first, [1, 3, 7, 9])) {
            if ($first < $last) {
                $step = 10 + $first - $last;
            } else if ($first > $last) {
                $step = $first - $last;
            } else {
                if ($first == 9) {
                    if (strlen($i + 10) > strlen($i)) {
                        $step = 2;
                    } else {
                        $step = 10;
                    }
                } else {
                    $step = 10;
                }
            }

            if ($mark[$i - $low]) {
                $results[] = $i;
                $total++;

            }
        } else {
            $low_lim = ($first + 1) * 10 ** (strlen($i) - 1);
            $step = $low_lim - $i;
        }
    }

}


function hasSameFirstLastDigit($n) {
    $str = (string) $n;
    return $str[0] == $str[strlen($str) - 1];
}


function segmentedSieve2($low, $high, &$total) {
    global $primes;
    global $results;

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

    for ($i = $low; $i <= $high; $i++) {
        if ($mark[$i - $low]) {
            $primes_idx[] = $i;
        }
    }

    $results = [];
    foreach ($primes_idx as $i) {
        if (hasSameFirstLastDigit($i)) {
            $results[] = $i;
            $total++;
        }
    }

}


function solve() {
    global $inputs;
    global $results;

    $total = 0;
    
    foreach ($inputs as $range) {
        list($low, $high) = $range;
        segmentedSieve2($low, $high, $total);
    }
    
    echo implode(" ", $results) . "\n";
    echo "Total: $total\n";
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

    mergeRanges();

    sieve();

    solve();

}


stats('main');


?>