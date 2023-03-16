<?php
namespace App\Service;

/***********************************************************************
 *
 * Symfony
 * https://symfony.com/doc/current/controller/upload_file.html
 *
 **********************************************************************/

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * file uploader
 */
class FileUploader
{
    /**
     * constructor
     *
     * @param string $targetDirectory
     * @param SluggerInterface $slugger
     */
    public function __construct(
        private $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }


    /**
     * upload
     *
     * @param UploadedFile $file
     * @return array
     */
    public function upload(UploadedFile $file): array
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return [
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage(),
            ];
        }

        // return
        return [
            'status' => Response::HTTP_OK,
            'message' => $fileName,
            'filename' => $fileName,
        ];
    }


    /**
     * targetDirectory
     *
     * @return ?string
     */
    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}