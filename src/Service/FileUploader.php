<?php

namespace App\Service;

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
     * @var string
     */
    private $uploadPath;

    public function __construct(string $uploadPath, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->uploadPath = $uploadPath;
    }

    public function uploadFile(File $file): string
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

        $file->move($this->uploadPath, $fileName);

        return $fileName;
    }
}
