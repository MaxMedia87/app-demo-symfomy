<?php

namespace App\Service;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $articleFileSystem, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->filesystem = $articleFileSystem;
    }

    public function uploadFile(File $file, ?string $oldFileName = null): string
    {
        $originalName = pathinfo(
            $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename(),
            PATHINFO_FILENAME
        );

        $fileName = $this->slugger
            ->slug($originalName)
            ->append('-', uniqid())
            ->append('.', $file->guessExtension())
            ->toString();

        $stream = fopen($file->getPathname(), 'r');

        $result = $this->filesystem->writeStream($fileName, $stream);

        if (false === $result) {
            throw new \Exception('Не удалось записать файл ' . $fileName);
        }

        if (true === is_resource($stream)) {
            fclose($stream);
        }

        if (null !== $oldFileName && $this->filesystem->has($oldFileName)) {
            $result = $this->filesystem->delete($oldFileName);

            if (false === $result) {
                throw new \Exception('Не удалось удалить файл ' . $fileName);
            }
        }

        return $fileName;
    }
}
