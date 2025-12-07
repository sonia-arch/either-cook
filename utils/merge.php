<?php
/**
 * Utility untuk menggabungkan semua file PHP dalam proyek ke satu file output.
 * Script ini berada di folder utils/
 */

$projectRoot = dirname(__DIR__); // root proyek (satu level di atas utils/)
$ignoreFolders = ['assets', 'utils', 'view']; // abaikan folder assets & utils
$outputFile = __DIR__ . '/all_code.txt'; // output berada di utils/

function scanFolder($dir, $ignoreFolders = [], $relativePath = '') {
    $result = [];
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $fullPath = $dir . '/' . $file;
        $relPath = $relativePath === '' ? $file : $relativePath . '/' . $file;

        // skip folder ignore
        if (is_dir($fullPath)) {
            if (in_array($file, $ignoreFolders)) continue;
            $result = array_merge($result, scanFolder($fullPath, $ignoreFolders, $relPath));
        } else {
            // ambil hanya file PHP
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $result[$relPath] = file_get_contents($fullPath);
            }
        }
    }
    return $result;
}

// scan semua file
$allFiles = scanFolder($projectRoot, $ignoreFolders);

// tulis ke file output
$output = '';
foreach ($allFiles as $path => $content) {
    $output .= "# $path\n";
    $output .= $content . "\n\n";
}

file_put_contents($outputFile, $output);

echo "Semua file PHP berhasil digabungkan ke $outputFile\n";