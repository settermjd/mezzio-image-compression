<?php

declare(strict_types=1);

namespace App\Handler;

use ImageOptimizer\OptimizerFactory;
use Intervention\Image\ImageManager;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ImageCompressionHandler implements RequestHandlerInterface
{
    public const IMAGE_UPLOAD_PATH = __DIR__ . '/../../../../data/uploads/';
    public const DEFAULT_IMAGE_QUALITY = 80;

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $newFileName = $request->getParsedBody()['file_name'] ?? 'untitled.jpg';
        $imageQuality = $request->getParsedBody()['image_quality'] ?? self::DEFAULT_IMAGE_QUALITY;
        $newFile = self::IMAGE_UPLOAD_PATH . "{$newFileName}";

        /** @var UploadedFileInterface $image */
        $image = $request->getUploadedFiles()['image'];
        $image->moveTo(self::IMAGE_UPLOAD_PATH . $image->getClientFilename());

        $manager = new ImageManager(['driver' => 'imagick']);
        try {
            $processedImage = $manager->make(self::IMAGE_UPLOAD_PATH . $image->getClientFilename());
            $processedImage->save($newFile, $imageQuality);

            (new OptimizerFactory())
                ->get()
                ->optimize($newFile);
        } catch (\Exception $e) {
            return new JsonResponse("Oops. Something went wrong. Reason: {$e->getMessage()}");
        }

        return new JsonResponse(
            [
                'original file' => [
                    'File name' => self::IMAGE_UPLOAD_PATH . $image->getClientFilename(),
                    'File size' => $image->getSize(),
                    'File type' => $image->getClientMediaType(),
                ],
                'new file' => [
                    'File name' => $newFile,
                    'File size' => filesize($newFile),
                    'File type' => mime_content_type($newFile),
                ]
            ],
        );
    }
}
