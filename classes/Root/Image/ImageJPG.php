<?php

/**
 * Gestion d'une image JPEG
 */

namespace Root\Image;

final class ImageJPG extends AbstractImage {
	
	/**
	 * Type d'image
	 * @param ImageTypeEnum
	 */
	protected ImageTypeEnum $type = ImageTypeEnum::JPEG;
	
	/**
	 * Initialise la ressource
	 * @return \GdImage
	 */
	protected function initResource() : \GdImage
	{
		return imagecreatefromjpeg($this->getFilepath());
	}
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	protected function display() : void
	{
		imagejpeg($this->getResource());
	}
	
	/**
	 * Enregistre l'image
	 * @param int $quality
	 * @return bool
	 */
	public function save(int $quality = 100) : bool
	{
		return imagejpeg($this->getResource(), $this->getFilepath(), $quality);
	}
	
}