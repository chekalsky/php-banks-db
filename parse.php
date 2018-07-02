<?php
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
    'min_length' => 99,
    'max_length' => 0
];

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

    $bank_id = sprintf('%s:%s', $bank_data['country'], $bank_data['name']);

    $bank_data_stored = $bank_data;
    unset($bank_data_stored['prefixes']);

    $database['banks'][$bank_id] = $bank_data_stored;

    foreach ($bank_data['prefixes'] as $prefix) {
        $len = strlen((string) $prefix);

        $database['min_length'] = min($database['min_length'], $len);
        $database['max_length'] = max($database['max_length'], $len);

        if (isset($database['prefixes'][$prefix])) {
            printError(sprintf('Duplicated prefix: %s (%s <-> %s)', $prefix, $bank_id, $database['prefixes'][$prefix]));
            continue;
        }

        $database['prefixes'][$prefix] = $bank_id;
    }
}

$database_export = sprintf('<?php return %s;', preg_replace("/\n\s+(\d)/", ' $1', var_export($database, true)));

if (file_put_contents('db/bank_db.php', $database_export)) {
    echo sprintf("Successfully exported %d prefixes for %d banks with prefixes from %d to %d symbols length\n",
        count($database['prefixes']),
        count($database['banks']),
        $database['min_length'],
        $database['max_length']);
}

function printError(string $text)
{
    echo '! ' . $text . "\n";
}
