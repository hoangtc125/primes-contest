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

function binarySearch($arr, $x) {
    $low = 0;
    $high = count($arr) - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if ($arr[$mid] < $x) {
            $low = $mid + 1;
        } elseif ($arr[$mid] > $x) {
            $high = $mid - 1;
        } else {
            return $mid;
        }
    }
    return $low;
}


function binarySearchCache($x) {
    global $data;
    global $count;
    
    $low = 0;
    $high = $count - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if ($data[$mid] < $x) {
            $low = $mid + 1;
        } elseif ($data[$mid] > $x) {
            $high = $mid - 1;
        } else {
            return $mid;
        }
    }
    return $low;
}


function mergeRanges() {
    global $inputs;

    foreach ($inputs as $key => &$range) {
        if ($range[1] - $range[0] < 2) {
            unset($inputs[$key]);
        } else {
            $range[0] += 1;
        }
    }

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
        while (($line = fgets($file)) !== false) {
            // Skip lines that do not contain prime numbers
            if (preg_match('/^\s*\d/', $line)) {
                $numbers = preg_split('/\s+/', trim($line));
                foreach ($numbers as $number) {
                    if (is_numeric($number)) {
                        $number = (int)$number;
                        $str_number = (string)$number;
                        if ($str_number[0] == $str_number[strlen($str_number) - 1]) {
                            $primes[] = $number;
                        }
                    }
                }
            }
        }
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
        $primes = readPrimeFile("primes/primes$file.txt");
        $data = array_merge($data, $primes);
    }

    $count = count($data);
}


function solve() {
    global $inputs;
    global $data;

    $total = 0;
    $count_inputs = count($inputs);

    foreach ($inputs as $index => $input) {
        $start = $input[0];
        $end = $input[1];

        $start_index = binarySearchCache($start);
        $end_index = binarySearchCache($end);

        $primes = array_slice($data, $start_index, $end_index - $start_index);

        $total += count($primes);

        print_r(implode(" ", $primes));

        if ($index < $count_inputs - 1) {
            print_r(" ");
        }
    }

    print_r("\nTotal: $total");
}


function main() {

    $start_time = microtime(true);
    $start_memory = memory_get_usage();

    mergeRanges();

    loadFiles();

    solve();

    $end_time = microtime(true);
    $end_memory = memory_get_usage();

    $executionTime = $end_time - $start_time;
    $memory_usage = $end_memory - $start_memory;

    echo "\nExecution Time: " . $executionTime . " seconds";
    echo "\nMemory Usage: " . ($memory_usage / 1024) . " KB";

}


main();


?>