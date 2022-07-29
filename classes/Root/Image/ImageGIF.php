<?php

/**
 * Gestion d'une image GIF
 */

namespace Root\Image;

final class ImageGIF extends AbstractImage {
	
	/**
	 * Type d'image
	 * @param ImageTypeEnum
	 */
	protected ImageTypeEnum $type = ImageTypeEnum::GIF;
	
	/**
	 * Initialise la ressource
	 * @return \GdImage
	 */
	protected function initResource() : \GdImage
	{
		return imagecreatefromgif($this->getFilepath());
	}
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	protected function display() : void
	{
		imagegif($this->getResource());
	}
	
	/**
	 * Enregistre l'image
	 * @param int $quality
	 * @return bool
	 */
	public function save(int $quality = 100) : bool
	{
		return imagegif($this->getResource(), $this->getFilepath());
	}
	
}