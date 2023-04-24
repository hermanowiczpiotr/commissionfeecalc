<?php


declare(strict_types=1);

namespace App\Infrastructure\FileLoader;

final class LoadDataFromFile
{
    public function __construct(
        private readonly string $dataDirectory,
    ) {
    }
    public function loadByFilename(string $filename): string
    {
        return file_get_contents($this->dataDirectory . DIRECTORY_SEPARATOR . $filename);
    }
}