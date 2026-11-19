<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {}

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        try {
            $extension = $file->guessExtension();
        } catch (\Symfony\Component\Mime\Exception\LogicException $e) {
            // Fallback if php_fileinfo is not enabled
            $extension = $file->getClientOriginalExtension();
        }
        
        $extension = $extension ?? $file->getClientOriginalExtension();
        $fileName = $safeFilename.'-'.uniqid().'.'.$extension;


        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
