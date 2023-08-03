<?php
/**
 * CWP Media tool
 */

$version = $argv[2];

$drive_letter = substr($argv[1], 0, 2);
$target_dir = $drive_letter.str_replace($drive_letter, '', $argv[1]);
$web_dir = $target_dir.\DIRECTORY_SEPARATOR.'public';
$update_dir = $web_dir.\DIRECTORY_SEPARATOR.'AppUpdates';

$update_zips = $update_dir;

$update_json = $update_zips.\DIRECTORY_SEPARATOR.'update.json';
$fileList = $update_zips.\DIRECTORY_SEPARATOR.'previous.txt';
$deleteList = $web_dir.\DIRECTORY_SEPARATOR.'delete.txt';

$skipdirs = [
   'public\\AppUpdates',
   'public\\vendor',
   // 'www\\updater\\updater_versions',
   // 'www\\updater\\download',
   'public\\database',
   'public\\cache',
   'www\\files',
   '.vscode',
   '.git',
];

$skipfiles =
[
    'update.log',
    'config.ini',
    'test_navlinks.php',
    '.gitignore',
    '.editorconfig',
    '.php-cs-fixer.php',
    'php_errors.log',
];

function listAllFiles($dir)
{
    $array = array_diff(scandir($dir), ['.', '..']);

    foreach ($array as &$item) {
        $item = $dir.\DIRECTORY_SEPARATOR.$item;
    }
    unset($item);
    foreach ($array as $item) {
        if (is_dir($item)) {
            $array = array_merge($array, listAllFiles($item));
        }
    }

    return $array;
}

$files_array = listAllFiles($target_dir);

foreach ($files_array as $file) {
    $exists = false;
    foreach ($skipdirs as $skip) {
        if (str_contains($file, $skip)) {
            $exists = true;
            continue;
        }
    }
    foreach ($skipfiles as $skip) {
        if (str_contains($file, $skip)) {
            $exists = true;
            continue;
        }
    }

    if (false == $exists) {
        $newFiles[] = $file;
    }
}
/*
$currentList = file_get_contents($fileList);
$newList_Array = explode("\n", $currentList);

$deletedArray = array_diff($newList_Array, $newFiles);

if (count($deletedArray) > 0) {
    $deleted_str = implode("\n", $deletedArray);
    $deleted_str = str_replace($web_dir.\DIRECTORY_SEPARATOR, '', $deleted_str);

    // file_put_contents($deleteList, $deleted_str);
} else {
    unlink($deleteList);
}

$fileList_str = implode("\n", $newFiles);
file_put_contents($fileList, $fileList_str);
*/
$zip_file = $update_zips.\DIRECTORY_SEPARATOR.$version.'.zip';
$rootPath = $target_dir;
$zipPath = str_replace(basename($zip_file), '', $zip_file);

$zip = new ZipArchive();
$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

foreach ($newFiles as $file) {
    if (!is_dir($file)) {
        $filePath = realpath($file);
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}

// if (file_exists($deleteList)) {
//     $filePath = realpath($deleteList);
//     $relativePath = substr($filePath, strlen($rootPath) + 1);
//     $zip->addFile($filePath, $relativePath);
// }

// Zip archive will be created only after closing object
$zip->close();

$update_files = listAllFiles($update_zips);

$url = 'https://raw.githubusercontent.com/bsodergren/cwp_www/main/AppUpdates/';
$iniString = '';

foreach ($update_files as $zipFile) {
    if ($zipFile == $update_json
    || $zipFile == $fileList
    || $zipFile == $deleteList) {
        continue;
    }

    $zipversion = basename($zipFile, '.zip');
    $zipurl = $url.basename($zipFile);
    $jsonArray[] = "\t".'"'.$zipversion.'":"'.$zipurl.'"';
    $iniString .= '['.$zipversion."]\n".'url = '.$zipurl."\n";
}

$json_string = "{ \n";
$json_string .= implode(",\n", $jsonArray);
$json_string .= "\n}";

file_put_contents($update_json, $json_string);

// file_put_contents($update_ini, $iniString);
