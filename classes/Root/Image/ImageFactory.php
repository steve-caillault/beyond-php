<?php

/**
 * Factory pour la manipulation d'une image
 */

namespace Root\Image;

final class ImageFactory {

    /**
	 * Retourne une instance de l'image correspondant au chemin du fichier en paramètre
	 * @param string $filepath Chemin de l'image
	 * @return AbstractImage
	 */
	public function getFromFilepath(string $filepath) : AbstractImage
	{
		$type = exif_imagetype($filepath);
        return match($type) {
            \IMAGETYPE_GIF => new ImageGIF($filepath),
            \IMAGETYPE_JPEG => new ImageJPG($filepath),
            \IMAGETYPE_PNG => new ImagePNG($filepath),
            \IMAGETYPE_AVIF => new ImageAVIF($filepath),
            \IMAGETYPE_WEBP => new ImageWEBP($filepath),
            default => exception('Type de fichier non autorisé.')
        };
	}

    /**
     * Retourne une instance de l'image en fonction du type en paramètre
     * @param ImageTypeEnum $type
     * @param string $filepath Le chemin de l'image à affecter
     * @return AbstractImage
     */
    public function getFromType(ImageTypeEnum $type, string $filepath) : AbstractImage
    {
        return match($type) {
            ImageTypeEnum::GIF => new ImageGIF($filepath),
            ImageTypeEnum::JPEG => new ImageJPG($filepath),
            ImageTypeEnum::PNG => new ImagePNG($filepath),
            ImageTypeEnum::AVIF => new ImageAVIF($filepath),
            ImageTypeEnum::WEBP => new ImageWEBP($filepath),
            default => exception('Type de fichier non autorisé.')
        };
    }

}