<?php
/**
 * Featured Slider
 *
 * @category   Slideshow
 * @package    Flyingkrai
 * @subpackage Helpers
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */

namespace Flyingkrai\Helpers;

/**
 * Resizer helper class
 *
 * @category   Slideshow
 * @package    Flyingkrai
 * @subpackage Helpers
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */
class Resizer
{
    /**
     * Resizer singleton
     *
     * @var Resizer
     */
    private static $_instance = null;

    private function __construct() {

    }

    /**
     * Initialize singleton instance
     *
     * @return Resizer singleton instance
     */
    public static function get_instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function get_file_name_by_dimensions($file, $width, $height)
    {
        $ext = '.' . end(explode('.', $file));
        $newExt = "-{$width}x{$height}" . $ext;
        $pos = strrpos($file, $ext);
        return substr_replace($file, $newExt, $pos, strlen($ext));
    }

    public function calculate_dimensions($file, $dimension)
    {
        if (!is_file($file)) {
            throw new \Exception('Arquivo não existe');
        }
        $fileInfo = getimagesize($file);
        $type = ($fileInfo[0] > $fileInfo[1]) ? 'width' : 'heigh';
        $width = $height = 0;
        switch ($type) {
            case 'height':
                $height = $dimension;
                $width = (int) (($dimension * $fileInfo[0]) / $fileInfo[1]);
                break;
            case 'width':
                $width = $dimension;
                $height = (int) (($dimension * $fileInfo[1]) / $fileInfo[0]);
                break;
        }
        return compact('width', 'height', 'fileInfo');
    }

    public function resize($file, $dimension)
    {
        if(!is_file($file)) {
            return;
        }
        $calc = $this->calculate_dimensions($file, $dimension);

        $newFile = $this->get_file_name_by_dimensions($file, $calc['width'], $calc['height']);
        if (is_file($newFile)) {
            return $newFile;
        }

        $this->_do_resize($file, $calc['fileInfo'], $newFile,  $calc['width'], $calc['height']);
    }

    private function _do_resize($oldFile, $oldFileInfo, $newFile, $width, $height)
    {
        $oldImage = $this->_create_image($oldFile, $oldFileInfo);
        $newImage = null;

        switch ($oldFileInfo['mime']) {
            case 'image/gif':
                $newImage = imagecreate($width, $height);
                break;
            case 'image/jpeg':
                $newImage = imagecreatetruecolor($width, $height);
                break;
            case 'image/png':
                $newImage = imagecreatetruecolor($width, $height);
                imagecolortransparent($newImage, imagecolorallocate($newImage, 0, 0, 0));
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
        }
        if (null === $newImage) {
            return false;
        }

        if($newImage === null) {
            return $oldFile;
        }

         if (function_exists('imagecopyresampled')) {
           imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, $width, $height, $oldFileInfo[0], $oldFileInfo[1]);
         } else {
           imagecopyresized($newImage, $oldImage, 0, 0, 0, 0, $width, $height, $oldFileInfo[0], $oldFileInfo[1]);
         }

         $this->_save($newImage, $newFile, $oldFileInfo['mime']);
    }

    private function _create_image($image, $imageInfo)
    {
        $imageResource = null;
        switch ($imageInfo['mime']) {
            case "image/jpeg":
                $imageResource = imagecreatefromjpeg($image);
                break;
            case "image/png":
                $imageResource = imagecreatefrompng($image);
                break;
             case "image/gif":
                $imageResource = imagecreatefromgif($image);
                break;
        }

        return $imageResource;
    }

    private function _save($image, $file, $mimeType)
    {
        switch($mimeType){
            case "image/jpeg":
            imagejpeg($image, $file, 95);
            break;
        case "image/png":
            imagepng($image, $file, 7);
            break;
        case "image/gif":
            imagegif($image, $file);
            break;
        }
        @chmod($file, 0777);
    }

}
