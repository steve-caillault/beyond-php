<?php

/**
 * Gestion d'une image PNG
 */

namespace Root\Image;

final class ImagePNG extends AbstractImage {
	
	/**
	 * Type d'image
	 * @param ImageTypeEnum
	 */
	protected ImageTypeEnum $type = ImageTypeEnum::PNG;
	
	/**
	 * Initialise la ressource
	 * @return \GdImage
	 */
	protected function initResource() : \GdImage
	{
		return imagecreatefrompng($this->getFilepath());
	}
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	protected function display() : void
	{
		imagepng($this->getResource());
	}
	
	/**
	 * Enregistre l'image
	 * @param int $quality
	 * @return bool
	 */
	public function save(int $quality = 9) : bool
	{
		return imagepng($this->getResource(), $this->getFilepath(), $quality);
	}
	
}