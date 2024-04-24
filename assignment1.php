<?php


function cacheContents(array $callLogs): array
{
    $output = [];
    $time_records = [];
    $priority_records = [];

    foreach ($callLogs as $row) {
        $key = $row[0];
        $value = $row[1];

        if (!array_key_exists($key, $time_records)) {
            $time_records[$key] = [$value];
        } else {
            $time_records[$key][] = $value;
        }

        if (!array_key_exists($value, $priority_records)) {
            $priority_records[$value] = ['first' => 0, 'second' => false];
        }
    }

    ksort($time_records);

    foreach ($time_records as $accessed_elements) {
        foreach ($priority_records as $priority_key => &$priority_value) {
            $number_of_occurrence = count(array_filter($accessed_elements, function ($el) use ($priority_key) {
                return $el == $priority_key;
            }));

            if ($number_of_occurrence > 0) {
                $priority_value['first'] += 2 * $number_of_occurrence;
                if ($priority_value['first'] > 5) {
                    $priority_value['second'] = true;
                }
            } else {
                if ($priority_value['first'] > 0) {
                    $priority_value['first'] -= 1;
                    if ($priority_value['first'] <= 3) {
                        $priority_value['second'] = false;
                    }
                }
            }
        }
    }

    foreach ($priority_records as $priority_key => &$priority_value) {
        if ($priority_value['second'] == true) {
            array_push($output, $priority_key);
        }
    }

    return $output;
}


$outputPath = getenv("OUTPUT_PATH") && getenv("OUTPUT_PATH") !== '' ? getenv("OUTPUT_PATH") : "output.txt";
$fptr = fopen($outputPath, "w");

$callLogs_rows = intval(trim(fgets(STDIN)));
$callLogs_columns = intval(trim(fgets(STDIN)));

$callLogs = array();

for ($i = 0; $i < $callLogs_rows; $i++) {
    $callLogs_temp = rtrim(fgets(STDIN));

    $callLogs[] = array_map('intval', preg_split('/ /', $callLogs_temp, -1, PREG_SPLIT_NO_EMPTY));
}

$result = cacheContents($callLogs);

fwrite($fptr, implode("\n", $result) . "\n");

fclose($fptr);