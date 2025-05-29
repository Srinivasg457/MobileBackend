<?php
date_default_timezone_set('Asia/Kolkata');

function archiveScreenshots() {
    $baseSourceDir = __DIR__ . "/uploads/screenshots_compressed";
    $archiveDir = __DIR__ . "/uploads/screenshots_archived";

    if (!is_dir($archiveDir)) {
        mkdir($archiveDir, 0777, true);
    }

    $folders = glob("$baseSourceDir/*", GLOB_ONLYDIR);

    // Reusable zip folder function
    function addFolderToZip($folder, $zip, $baseFolderPath) {
        $files = scandir($folder);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $filePath = "$folder/$file";
            $localPath = str_replace($baseFolderPath . '/', '', $filePath); // Correct relative path
            if (is_dir($filePath)) {
                $zip->addEmptyDir($localPath);
                addFolderToZip($filePath, $zip, $baseFolderPath);
            } else {
                $zip->addFile($filePath, $localPath);
            }
        }
    }

    function deleteFolder($folder) {
        $files = array_diff(scandir($folder), ['.', '..']);
        foreach ($files as $file) {
            $filePath = "$folder/$file";
            if (is_dir($filePath)) {
                deleteFolder($filePath);
            } else {
                unlink($filePath);
            }
        }
        rmdir($folder);
    }

    foreach ($folders as $folderPath) {
        $relativeFolderName = basename($folderPath);
        $timestamp = date('Ymd_His');
        $zipFilePath = "$archiveDir/{$relativeFolderName}_$timestamp.zip";

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            addFolderToZip($folderPath, $zip, $folderPath); // Pass actual folder path for base
            $zip->close();

            deleteFolder($folderPath);

            echo "[" . date('Y-m-d H:i:s') . "] Archived folder '$relativeFolderName' to $zipFilePath\n";
        } else {
            echo "[" . date('Y-m-d H:i:s') . "] Failed to create ZIP for folder: $relativeFolderName\n";
        }
    }
}

// Infinite loop every 2 minutes
while (true) {
    archiveScreenshots();
    sleep(120);
}
