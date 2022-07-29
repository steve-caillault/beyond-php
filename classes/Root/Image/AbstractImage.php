<?php

/**
 * Gestion d'une image
 */

namespace Root\Image;

use Root\Arr;

abstract class AbstractImage {

	/**
	 * Type d'image
	 * @var ImageTypeEnum
	 */
	protected ImageTypeEnum $type;
	
	/**
	 * Ressource de l'mage
	 * @var \GdImage
	 */
	private \GdImage $resource;
	
	/**
	 * Dimensions de l'image
	 * @var array
	 */
	private array $dimensions = [
		'width' => null,
		'height' => null,
	];
	
	/**********************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur
	 * @param string $filepath Chemin de l'image
	 */
	public function __construct(private string $filepath)
	{

	}
	
	/**********************************************************************************/
	
	/**
	 * Initialise la ressource
	 * @return \GdImage
	 */
	abstract protected function initResource() : \GdImage;

	/**
	 * Modifie la ressource de l'image
	 * @param \GdImage $resource
	 * @return self
	 */
	private function setResource(\GdImage $resource) : self
	{
		$this->resource = $resource;
		return $this;
	}
	
	/**********************************************************************************/
	
	/**
	 * Redimensionne une image
	 * @param int $width
	 * @param int $height
	 */
	public function resize(?int $width, ?int $height) : void
	{
		$originalDimensions = $this->getDimensions();
		$originalWidth = Arr::get($originalDimensions, 'width');
		$originalHeight = Arr::get($originalDimensions, 'height');
		
		// Modifie les dimensions pour respecter les proportions
		
		if($width !== null or $height !== null)
		{
			if($height === null and $width !== null)
			{
				$height = $originalHeight * ($width / $originalWidth);  
			}
			elseif($width === null and $height !== null)
			{
				$width = $originalWidth * ($height / $originalHeight);
			}
			elseif(($originalWidth / $width) > ($originalHeight / $height))
			{
				$height = $originalHeight * ($width / $originalWidth);
			}
			else
			{
				$width = $originalWidth * ($height / $originalHeight);
			}
		}
		
		$this->setDimensions($width, $height);
		
		$imageDest = imagecreatetruecolor($width, $height);
		imagecopyresampled($imageDest, $this->getResource(), 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);
		$this->resource = $imageDest;
	}
	
	/**
	 * Modifie les dimensions de l'image
	 * @param float $width
	 * @param float $height
	 * @return void
	 */
	private function setDimensions(float $width, float $height) : void
	{
	    $this->_dimensions = [
	        'width' => $width,
	        'height' => $height,
	    ];
	}
	        	
	/**
	 * Retourne les dimensions de l'image
	 * @return array
	 */
	 public function getDimensions() : array
	 {
	 	$width = Arr::get($this->dimensions, 'width');
	 	$height = Arr::get($this->dimensions, 'height');
	 	
	 	if($width === null or $height === NULL)
	 	{
	 		list($width, $height) = getimagesize($this->_filepath);
	 		$this->dimensions = [
	 			'width' => $width,
	 			'height' => $height,
	 		];
	 	}
	 	
	 	return $this->dimensions;
	}
	
	/**********************************************************************************/
	
	/**
	 * Retourne le contenu de l'image
	 * @return string
	 */
	public function getContents() : string
	{
	    ob_start();
	    $this->display();
	    $content = ob_get_contents();
	    ob_end_clean();
	    return $content;
	}
	
	/**
	 * Affichage de l'image
	 * @return void
	 */
	abstract protected function display() : void;

	/**
	 * Convertit l'image vers le type en paramètre
	 * @param ImageTypeEnum $conversionType
	 * @param string $conversionFilepath
	 * @return self
	 */
	public function convert(ImageTypeEnum $conversionType, string $conversionFilepath) : self
	{
		$conversionImage = (new ImageFactory())->getFromType(
			type: $conversionType,
			filepath: $conversionFilepath
		);

		$conversionImage->setResource($this->getResource());

		return $conversionImage;
	}

	/**
	 * Enregistre l'image
	 * @param int $quality
	 * @return bool
	 */
	abstract public function save(int $quality = 100) : bool;
	
	/**********************************************************************************/
	
	/**
	 * Retourne le chemin du fichier
	 * @return string
	 */
	protected function getFilepath() : string
	{
		return $this->filepath;
	}

	/**
	 * Retourne le type d'image
	 * @return ImageTypeEnum
	 */
	public function getType() : ImageTypeEnum
	{
	    return $this->type;
	}

	/**
	 * Retourne la ressource de l'image
	 * @return \GdImage
	 */
	public function getResource() : \GdImage
	{
		return $this->resource ??= $this->initResource();
	}

	/**********************************************************************************/

}
	