<?php
/**
 * The first edition of a image tool developed by Sysborg using PHP GD Library
 * updated to php 8.1
 * Author Anderson Arruda < contato@sysborg.com.br >
 */
namespace sysborg;

class btd{
    private array $allowedExtensions = [
        'image/bmp'   => 'bmp',
        'image/gif'   => 'gif',
        'image/jpeg'  => 'jpeg',
        'image/png'   => 'png',
        'image/x-tga' => 'tga',
        'image/webp'   => 'webp'
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
        !array_key_exists($this->mime, $this->allowedExtensions) && throw new BTDException(1);

        $type = $this->allowedExtensions[$this->mime];
        $this->gd = $this->resources[$type]($filepath);

        is_null($this->gd) && throw new BTDException(2);
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
     * @param           string $file
     * @param           int $width
     * @return          string
     */
    public function srcSetName(string $path, int $width) : string
    {
        $file = rtrim($path, DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR. utils::getFileName($this->filepath);
        return utils::addPrefixFile($file, (string) $width);
    }

    /**
     * description      Return array with all existing images and his sizes into srcset by name
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           string $path
     * @return          array
     */
    public function getSrcSetFiles(string $path) : array
    {
        $srcsetSizes = $this->srcsetFiltered();
        $ret = [];
        foreach($srcsetSizes as $size){
            $srcsetpath = $this->srcSetName($path, $size);
            if(is_file($srcsetpath))
                $ret[] = ['width' => $size, 'filepath' => $srcsetpath];
        }

        return $ret;
    }

    /**
     * description      Resize srcset
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           string $path
     * @param           string $type
     * @param           int $quality
     * @return          btd
     */
    public function resizeSrcSet(string $path, string $type, int $quality=100) : btd
    {
        $srcsetSizes = $this->srcsetFiltered();
        foreach($srcsetSizes as $size){
            (new btd($this->filepath))->proportional($size)
                                      ->save($this->srcSetName($path, $size), $type, $quality);

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
     * @param           string $file
     * @param           string $type
     * @return          btd
     */
    public function save(string $file, string $type, int $quality=100, bool $replace=false) : btd
    {
        $type = strtolower($type);
        !array_key_exists($type, $this->outputTypes) && throw new BTDException(3);
        is_file($file) && !$replace && throw new BTDException(5);
        !is_writable(dirname($file)) && throw new BTDException(4);

        $this->outputTypes[$type]($this->gd, $file, $quality);
        return $this;
    }
}
?>