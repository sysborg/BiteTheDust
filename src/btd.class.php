<?php
/**
 * The first edition of a image tool developed by Sysborg using PHP GD Library
 * updated to php 8.1
 * Author Anderson Arruda < contato@sysborg.com.br >
 */
namespace sysborg;

class btd{
    const ALLOWED_EXTENSION = [
        'image/bmp'   => 'bmp',
        'image/gif'   => 'gif',
        'image/jpeg'  => 'jpeg',
        'image/png'   => 'png',
        'image/x-tga' => 'tga',
        'image/webp'  => 'webp',
        'image/jpg'   => 'jpg'
    ];

    private array $resources = [
        'bmp'  => 'imagecreatefrombmp',
        'gif'  => 'imagecreatefromgif', 
        'jpeg' => 'imagecreatefromjpeg',
        'png'  => 'imagecreatefrompng',
        'tga'  => 'imagecreatefromtga',
        'webp' => 'imagecreatefromwebp'
    ];

    private array $outputTypes = [
        'bmp'  => 'imagebmp',
        'gif'  => 'imagegif',
        'jpeg' => 'imagejpeg',
        'jpg'  => 'imagejpeg',
        'png'  => 'imagepng',
        'webp' => 'imagewebp'
    ];

    /**
     * this sizes are rellated to width
     */
    const SRCSET = [
        320, 768, 1024,
        1280, 1366, 1440,
        1600, 1680, 1920
    ];

    private string $mime;

    private \GdImage|null $gd;
    private string $file;
    private string $extension;
    private string $latestSaved;

    /**
     * description      Construct class and prepares the image edition
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           private string $filepath
     * @return          void
     */
    public function __construct(private string $filepath)
    {
        !is_file($filepath) && throw new BTDException(0);

        $this->mime = mime_content_type($this->filepath);
        !array_key_exists($this->mime, self::ALLOWED_EXTENSION) && throw new BTDException(1);

        $type = self::ALLOWED_EXTENSION[$this->mime];
        $this->gd = $this->resources[$type]($filepath);

        is_null($this->gd) && throw new BTDException(2);
        $this->file = basename($this->filepath);
        $this->extension = pathinfo($this->filepath, PATHINFO_EXTENSION);
    }

    /**
     * description      Verify if file extension is allowed
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           string $extension
     * @return          bool
     */
    public static function isExtensionAllowed(string $extension) : bool
    {
        return in_array(strtolower($extension), array_values(self::ALLOWED_EXTENSION));
    }

    /**
     * description      Returns all allowed extensions
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           
     * @return          array
     */
    public static function getAllowedExtensions() : array
    {
        return array_values(self::ALLOWED_EXTENSION);
    }

    /**
     * description      Get the implode of all allowed extensions
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           string $separator
     * @return          string
     */
    public static function implodeAllowedExtensions(string $separator) : string
    {
        return implode($separator, self::getAllowedExtensions());
    }

    /**
     * description      Makes the image at grayscale
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           
     * @return          btd
     */
    public function grayscale() : btd
    {
        imagefilter($this->gd, IMG_FILTER_GRAYSCALE);
        return $this;
    }

    /**
     * description      Get filesize
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           
     * @return          int
     */
    public function getImageSize() : int
    {
        $size = filesize($this->filepath);
        !$size && throw new BTDException(7);
        return $size;
    }

    /**
     * description      Check max filesize
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           int $maxsize
     * @return          bool
     */
    public function checkMaxFileSize(int $maxsize) : bool
    {
        return $this->getImageSize() <= $maxsize;
    }

    /**
     * description      Crop image
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           int $left
     * @param           int $top
     * @param           int $width
     * @param           int $height
     * @return          btd
     */
    public function crop(int $left, int $top, int $width, int $height) : btd
    {
        $cropped = imagecrop($this->gd, ['x' => $left, 'y' => $top, 'width' => $width, 'height' => $height]);
        !$cropped && throw new BTDException(6);
        $this->gd = $cropped;
        return $this;
    }

    /**
     * description      Filter sizes smaller than the image width
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           
     * @return          array
     */
    private function srcsetFiltered() : array
    {
        $imgWidth = $this->getWidth();
        return array_filter(self::SRCSET, function($val) use($imgWidth){
            return $val < $imgWidth;
        });
    }

    /**
     * description      Get image size
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           
     * @return          int
     */
    public function getWidth() : int
    {
        return imagesx($this->gd);
    }

    /**
     * description      Prefix for files into srcset
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           int $width
     * @return          string
     */
    public function srcSetName(int $width) : string
    {
        return utils::addPrefixFile($this->filepath, (string) $width);
    }

    /**
     * description      Return array with all existing images and his sizes into srcset by name
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           
     * @return          array
     */
    public function getSrcSetFiles() : array
    {
        $srcsetSizes = $this->srcsetFiltered();
        $ret = [];
        foreach($srcsetSizes as $size){
            $srcsetpath = $this->srcSetName($size);
            if(is_file($srcsetpath))
                $ret[] = ['width' => $size, 'filepath' => $srcsetpath];
        }

        return $ret;
    }

    /**
     * description      Resize srcset
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           string $type
     * @param           int $quality
     * @return          btd
     */
    public function resizeSrcSet(string $type, int $quality=100) : btd
    {
        $srcsetSizes = $this->srcsetFiltered();
        foreach($srcsetSizes as $size){
            (new btd($this->filepath))->proportional($size)
                                      ->save($this->srcSetName($size), $type, $quality);

        }
        return $this;
    }

    /**
     * description      Remove all srcset of loaded image
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           
     * @return          btd
     */
    public function deleteSrcSet() : btd
    {
        $allFiles = $this->getSrcSetFiles();
        foreach($allFiles as $file){
            !is_writable(dirname($file['filepath'])) && throw new BTDException(4);
            unlink($file['filepath']);
        }

        return $this;
    }

    /**
     * description      Proportional resize
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           ?int $width
     * @param           ?int $height
     * @return          btd
     */
    public function proportional(?int $width=NULL, ?int $height=NULL) : btd
    {
        $this->gd = imagescale($this->gd, $width ?? -1, $height ?? -1);
        return $this;
    }

    /**
     * description      Save file into disk
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           ?string $file
     * @param           string $type
     * @param           int $quality
     * @param           bool $replace
     * @return          btd
     */
    public function save(?string $file, string $type, int $quality=100, bool $replace=false) : btd
    {
        $type = strtolower($type);
        $file = $file ?? preg_replace('/\.[0-9a-zA-Z]+$/', '.'. $type, $this->filepath);
        !array_key_exists($type, $this->outputTypes) && throw new BTDException(3);
        is_file($file) && !$replace && throw new BTDException(5);
        !is_writable(dirname($file)) && throw new BTDException(4);

        $this->outputTypes[$type]($this->gd, $file, $quality);
        $this->latestSaved = $file;
        return $this;
    }

    /**
     * Get latest filepath or filename
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           bool $fullpath=TRUE
     * @return          string
     */
    public function getLatestSaved(bool $fullpath=true) : string
    {
        return $fullpath ? $this->latestSaved : basename($this->latestSaved);
    }

    /**
     * Delete file at his original path
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           
     * @return          btd
     */
    public function delete() : btd
    {
        unlink($this->filepath);
        return $this;
    }
}
?>
