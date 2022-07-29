<?php

/**
 * Gestion d'une image AVIF
 */

namespace Root\Image;

final class ImageAVIF extends AbstractImage {
	
	/**
	 * Type d'image
	 * @param ImageTypeEnum
	 */
	protected ImageTypeEnum $type = ImageTypeEnum::AVIF;
	
	/**
	 * Initialise la ressource
	 * @return \GdImage
	 */
	protected function initResource() : \GdImage
	{
		return imagecreatefromavif($this->getFilepath());
	}
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	protected function display() : void
	{
		imageavif($this->getResource());
	}
	
	/**
	 * Enregistre l'image
	 * @param int $quality
	 * @return bool
	 */
	public function save(int $quality = 100) : bool
	{
        // @todo Gestion du paramètre speed ?
        return imageavif($this->getResource(), $this->getFilepath(), $quality);
	}
	
}