<?php
    
    
function zip_Workbooks($xlsx_directory,$job_id,$zip_file )
{
    global $explorer;

    $rootPath = realpath($xlsx_directory);
    
    $zip = new ZipArchive();
    $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );

    foreach ($files as $name => $file)
    {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }

    // Zip archive will be created only after closing object
    $zip->close();
    
    $explorer->table('media_job')->where('job_id', $job_id)
    ->update(['zip_file' => $zip_file]);


    //myHeader($_SERVER['REQUEST_URI']);
}
?>