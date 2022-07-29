<?php

/**
 * Gestion d'une image WEBP
 */

namespace Root\Image;

final class ImageWEBP extends AbstractImage {
	
	/**
	 * Type d'image
	 * @param ImageTypeEnum
	 */
	protected ImageTypeEnum $type = ImageTypeEnum::WEBP;
	
	/**
	 * Initialise la ressource
	 * @return \GdImage
	 */
	protected function initResource() : \GdImage
	{
		return imagecreatefromwebp($this->getFilepath());
	}
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	protected function display() : void
	{
		imagewebp($this->getResource());
	}
	
	/**
	 * Enregistre l'image
	 * @param int $quality
	 * @return bool
	 */
	public function save(int $quality = 100) : bool
	{
        return imagewebp($this->getResource(), $this->getFilepath(), $quality);
	}
	
}