<?php

namespace App\Service;

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

    public function uploadFile(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $fileName = $this->slugger
            ->slug($originalName)
            ->append('-', uniqid())
            ->append('.', $file->guessClientExtension())
            ->toString();

        $file->move($this->uploadPath, $fileName);

        return $fileName;
    }
}
