<?php

/**
 * Enum pour la gestion des types d'image
 */

 namespace Root\Image;

 enum ImageTypeEnum : string {
    case JPEG = 'jpg';
    case PNG = 'png';
    case GIF = 'gif';
 }