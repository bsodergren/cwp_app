<?php

use Nette\Utils\FileSystem;

class log 
{

    public static function append(string $file, string $content, ?int $mode = 0666): void
	{
		FileSystem::createDir(dirname($file));
		if (@file_put_contents($file, $content,FILE_APPEND) === false) { // @ is escalated to exception
			throw new Nette\IOException(sprintf(
				"Unable to write file '%s'. %s",
				FileSystem::normalizePath($file),
				Helpers::getLastError()
			));
		}

		if ($mode !== null && !@chmod($file, $mode)) { // @ is escalated to exception
			throw new Nette\IOException(sprintf(
				"Unable to chmod file '%s' to mode %s. %s",
				FileSystem::normalizePath($file),
				decoct($mode),
				Helpers::getLastError()
			));
		}
	}
}
