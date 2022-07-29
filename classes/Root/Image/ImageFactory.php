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
	public function get(string $filepath) : AbstractImage
	{
		$type = exif_imagetype($filepath);
		
        return match($type) {
            \IMAGETYPE_GIF => new ImageGIF($filepath),
            \IMAGETYPE_JPEG => new ImageJPG($filepath),
            \IMAGETYPE_PNG => new ImagePNG($filepath),
            default => exception('Type de fichier non autorisé.')
        };
	}

}