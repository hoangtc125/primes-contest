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


$map = [];


$data1 = [];
$data2 = [];
$data3 = [];
$data4 = [];
$data5 = [];
$data6 = [];
$data7 = [];
$data8 = [];
$data9 = [];
$data10 = [];
$data11 = [];
$data12 = [];
$data13 = [];
$data14 = [];
$data15 = [];
$data16 = [];
$data17 = [];
$data18 = [];
$data19 = [];
$data20 = [];
$data21 = [];
$data22 = [];
$data23 = [];
$data24 = [];
$data25 = [];
$data26 = [];
$data27 = [];
$data28 = [];
$data29 = [];
$data30 = [];
$data31 = [];
$data32 = [];
$data33 = [];
$data34 = [];
$data35 = [];
$data36 = [];
$data37 = [];
$data38 = [];
$data39 = [];
$data40 = [];
$data41 = [];
$data42 = [];
$data43 = [];
$data44 = [];
$data45 = [];
$data46 = [];
$data47 = [];
$data48 = [];
$data49 = [];
$data50 = [];


$count1 = 0;
$count2 = 0;
$count3 = 0;
$count4 = 0;
$count5 = 0;
$count6 = 0;
$count7 = 0;
$count8 = 0;
$count9 = 0;
$count10 = 0;
$count11 = 0;
$count12 = 0;
$count13 = 0;
$count14 = 0;
$count15 = 0;
$count16 = 0;
$count17 = 0;
$count18 = 0;
$count19 = 0;
$count20 = 0;
$count21 = 0;
$count22 = 0;
$count23 = 0;
$count24 = 0;
$count25 = 0;
$count26 = 0;
$count27 = 0;
$count28 = 0;
$count29 = 0;
$count30 = 0;
$count31 = 0;
$count32 = 0;
$count33 = 0;
$count34 = 0;
$count35 = 0;
$count36 = 0;
$count37 = 0;
$count38 = 0;
$count39 = 0;
$count40 = 0;
$count41 = 0;
$count42 = 0;
$count43 = 0;
$count44 = 0;
$count45 = 0;
$count46 = 0;
$count47 = 0;
$count48 = 0;
$count49 = 0;
$count50 = 0;


function binarySearchCeil($x, $file) {

    global ${'data' . $file};
    global ${'count' . $file};

    $low = 0;
    $high = ${'count' . $file} - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if (${'data' . $file}[$mid] <= $x) {
            $low = $mid + 1;
        } else {
            $high = $mid - 1;
        }
    }
    return $low;
}


function binarySearchFloor($x, $file) {

    global ${'data' . $file};
    global ${'count' . $file};

    $low = 0;
    $high = ${'count' . $file} - 1;
    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        if (${'data' . $file}[$mid] < $x) {
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
    
    global $data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $data10;
    global $data11, $data12, $data13, $data14, $data15, $data16, $data17, $data18, $data19, $data20;
    global $data21, $data22, $data23, $data24, $data25, $data26, $data27, $data28, $data29, $data30;
    global $data31, $data32, $data33, $data34, $data35, $data36, $data37, $data38, $data39, $data40;
    global $data41, $data42, $data43, $data44, $data45, $data46, $data47, $data48, $data49, $data50;
    
    global $count1, $count2, $count3, $count4, $count5, $count6, $count7, $count8, $count9, $count10;
    global $count11, $count12, $count13, $count14, $count15, $count16, $count17, $count18, $count19, $count20;
    global $count21, $count22, $count23, $count24, $count25, $count26, $count27, $count28, $count29, $count30;
    global $count31, $count32, $count33, $count34, $count35, $count36, $count37, $count38, $count39, $count40;
    global $count41, $count42, $count43, $count44, $count45, $count46, $count47, $count48, $count49, $count50;
        
    $files = [];
    $min_primes = [];

    for ($i = 0; $i < count($max_primes); $i++) {
        if ($i == 0) {
            $min_primes[$i] = 0;
        } else {
            $min_primes[$i] = $max_primes[$i - 1] + 1;
        }
    }

    foreach ($inputs as &$range) {
        $start = $range[0];
        $end = $range[1];

        $range["data"] = [
            "start_file" => 1,
            "end_file" => 1,
        ];

        $range_files = [];

        foreach ($max_primes as $index => $maxPrime) {
            if ($maxPrime >= $start && $min_primes[$index] <= $end) {
                $range_files[] = $index + 1;
            }
        }

        foreach ($range_files as $key => $file) {
            $files[] = $file;

            if ($key == 0) {
                $range["data"]["start_file"] = $file;
            }

            if ($key == count($range_files) - 1) {
                $range["data"]["end_file"] = $file;
            }
        }

    }

    $files =  array_unique($files);
    
    foreach ($files as $file) {
        ${'data' . $file} = readPrimeFile("data/primes$file.txt");
        ${'count' . $file} = count(${'data' . $file});
    }
}


function solve() {
    global $inputs;

    global $data1, $data2, $data3, $data4, $data5, $data6, $data7, $data8, $data9, $data10;
    global $data11, $data12, $data13, $data14, $data15, $data16, $data17, $data18, $data19, $data20;
    global $data21, $data22, $data23, $data24, $data25, $data26, $data27, $data28, $data29, $data30;
    global $data31, $data32, $data33, $data34, $data35, $data36, $data37, $data38, $data39, $data40;
    global $data41, $data42, $data43, $data44, $data45, $data46, $data47, $data48, $data49, $data50;

    $total = 0;
    $count_inputs = count($inputs);

    $outputs = "";

    foreach ($inputs as $index => $input) {
        $start = $input[0];
        $end = $input[1];

        $start_file = $input["data"]["start_file"];
        $end_file = $input["data"]["end_file"];

        $start_index = binarySearchCeil($start, $start_file);
        $end_index = binarySearchFloor($end, $end_file);

        if ($start_file == $end_file) {
            $primes = array_slice(${'data' . $start_file}, $start_index, $end_index - $start_index + 1);
            $total += count($primes);
            $outputs .= implode(" ", $primes);
        } else {
            $start_primes = array_slice(${'data' . $start_file}, $start_index);
            $total += count($start_primes);
            $outputs .= implode(" ", $start_primes) . " ";

            for ($i = $start_file + 1; $i < $end_file; $i++) { 
                $total += count(${'data' . $i});
                $outputs .= implode(" ", ${'data' . $i}) . " ";
            }
            
            $end_primes = array_slice(${'data' . $end_file}, 0, $end_index + 1);
            $total += count($end_primes);
            $outputs .= implode(" ", $end_primes);
        }
    
        if ($index < $count_inputs - 1) {
            $outputs .= " ";
        }

    }

    $outputs .= "\nTotal: $total";

    echo $outputs;
}


function stats($callback) {
    $startMemory = memory_get_usage();
    $start = microtime(true);

    call_user_func($callback);
    
    $end = microtime(true);
    $endMemory = memory_get_usage();
    
    $time = $end - $start;
    $memory = ($endMemory - $startMemory) / 1024 / 1024;
    
    echo "\nTime: $time seconds\n";
    echo "Memory: $memory MB\n";
}


function main() {

    mergeRanges();

    loadFiles();

    solve();

}


stats("main");


?>