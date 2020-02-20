<?php

declare(strict_types=1);

/**
 * This script needed only for one-time building the database after the submodule update
 */

$banks_db_path = __DIR__ . '/banks-db/banks';

if (file_exists('db/bank_db.php')) {
    unlink('db/bank_db.php');
}

if (!file_exists($banks_db_path . '/index.js')) {
    echo 'Please update git submodule';
    exit(1);
}

$database = [
    'prefixes' => [],
    'banks' => [],
];

$bank_id = 0;
$max_length = 6;

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($banks_db_path));

/** @var SplFileInfo $file */
foreach ($rii as $file) {
    if ($file->isDir()) {
        continue;
    }

    if ($file->getExtension() !== 'json') {
        continue;
    }

    $json_file = $file->openFile();

    $contents = $json_file->fread($json_file->getSize());

    $bank_data = json_decode($contents, true);

    if (!isset($bank_data['prefixes'])) {
        printError('Corrupted file: ' . $file->getPathname());
        continue;
    }

    ++$bank_id;

    $bank_data_stored = $bank_data;
    unset($bank_data_stored['prefixes']);

    $database['banks'][$bank_id] = $bank_data_stored;

    foreach ($bank_data['prefixes'] as $prefix) {
        $len = strlen((string) $prefix);

        for ($i = $len; $i <= $len; $i++) {
            $len_diff = $max_length - $len;

            if ($len_diff > 0) {
                for ($l = 1; $l <= $len_diff; $l++) {
                    $count_prefixes = 10 ** $l;

                    for ($k = 0; $k < $count_prefixes; $k++) {
                        $new_prefix = (int) sprintf('%d%d', $prefix, $k);

                        addPrefix($new_prefix, $bank_id, $database);
                    }
                }
            } else {
                addPrefix($prefix, $bank_id, $database);
            }
        }
    }
}

$database_export = sprintf('<?php return %s;', var_export($database, true));

if (file_put_contents('db/bank_db.php', $database_export)) {
    echo sprintf(
        "Successfully exported %d prefixes for %d banks with prefixes\n",
        count($database['prefixes']),
        count($database['banks'])
    );
}

function printError(string $text)
{
    echo '! ' . $text . "\n";
}

function addPrefix(int $prefix, int $bank_id, array &$database)
{
    if (isset($database['prefixes'][$prefix])) {
        printError(sprintf('Duplicated prefix: %s (%s <-> %s)', $prefix, $bank_id, $database['prefixes'][$prefix]));

        return;
    }

    $database['prefixes'][$prefix] = $bank_id;
}
