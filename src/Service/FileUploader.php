<?php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectory;
    private $targetDirectoryProduit;
    private $slugger;
    public function __construct($targetDirectory, $targetDirectoryProduit, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->targetDirectoryProduit = $targetDirectoryProduit;
        $this->slugger = $slugger;
    }
    public function uploadCategorie(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return null; // for example
        }
        return $fileName;
    }

    public function uploadProduit(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectoryProduit(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return null; // for example
        }
        return $fileName;
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
    public function getTargetDirectoryProduit()
    {
        return $this->targetDirectoryProduit;
    }
}
