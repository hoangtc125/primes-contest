<?php

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


$max_primes = [
    15485863, 32452843, 49979687, 67867967, 86028121, 104395301, 122949823, 141650939, 160481183, 179424673,
    198491317, 217645177, 236887691, 256203161, 275604541, 295075147, 314606869, 334214459, 353868013, 373587883,
    393342739, 413158511, 433024223, 452930459, 472882027, 492876847, 512927357, 533000389, 553105243, 573259391,
    593441843, 613651349, 633910099, 654188383, 674506081, 694847533, 715225739, 735632791, 756065159, 776531401,
    797003413, 817504243, 838041641, 858599503, 879190747, 899809343, 920419813, 941083981, 961748927, 982451653
];


$data = [];
$count = 0;


function binarySearchCeil($x) {
    global $data;
    global $count;

    $low = 0;
    $high = $count - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if ($data[$mid] <= $x) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return $low;
}


function binarySearchFloor($x) {
    global $data;
    global $count;

    $low = 0;
    $high = $count - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if ($data[$mid] < $x) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return $high;
}


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

    $inputs = $merged;
}


function readPrimeFile($filename) {
    $primes = [];
    $file = fopen($filename, 'r');
    
    if ($file) {
        $line = fgets($file);
        $primes = preg_split('/\s+/', trim($line));
        fclose($file);
    } else {
        echo "Error opening file: $filename\n";
    }

    return $primes;
}


function loadFiles() {
    global $inputs;
    global $max_primes;
    global $data;
    global $count;
        
    $files = [];
    $min_primes = [];

    for ($i = 0; $i < count($max_primes); $i++) {
        if ($i == 0) {
            $min_primes[$i] = 0;
        } else {
            $min_primes[$i] = $max_primes[$i - 1] + 1;
        }
    }

    foreach ($inputs as $range) {
        $start = $range[0];
        $end = $range[1];

        foreach ($max_primes as $index => $maxPrime) {
            if ($maxPrime >= $start && $min_primes[$index] <= $end) {
                $files[] = $index + 1;
            }
        }
    }

    $files =  array_unique($files);
    
    foreach ($files as $file) {
        $primes = readPrimeFile("data/primes$file.txt");
        $data = array_merge($data, $primes);
    }

    $count = count($data);
}


function solve() {
    global $inputs;
    global $data;

    $total = 0;
    $count_inputs = count($inputs);

    $outputs = "";

    foreach ($inputs as $index => $input) {
        $start = $input[0];
        $end = $input[1];

        $start_index = binarySearchCeil($start);
        $end_index = binarySearchFloor($end);

        $primes = array_slice($data, $start_index, $end_index - $start_index + 1);

        $total += count($primes);

        $outputs .= implode(" ", $primes);

        if ($index < $count_inputs - 1) {
            $outputs .= " ";
        }
    }

    $outputs .= "\nTotal: $total";

    print_r($outputs);
}


function stats($callback) {
    $start = microtime(true);
    call_user_func($callback);
    $end = microtime(true);
    $time = $end - $start;
    echo "\nTime: $time\n";
}


function main() {

    mergeRanges();

    loadFiles();

    solve();

}


stats("main");


?>