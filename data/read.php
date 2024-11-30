<?php

function processPrimesFile($src, $dest) {
    $primes = [];
    $file = fopen($src, 'r');
    
    if ($file) {
        while (($line = fgets($file)) !== false) {
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

        $primes = implode(" ", $primes);

        $file = fopen($dest, 'w');
        if ($file) {
            fwrite($file, $primes);
            fclose($file);
        } else {
            echo "Error opening file: $dest\n";
        }

    } else {
        echo "Error opening file: $src\n";
    }
}

for ($i = 1; $i <= 50; $i++) {
    processPrimesFile("../primes/primes$i.txt", "primes$i.txt");
}