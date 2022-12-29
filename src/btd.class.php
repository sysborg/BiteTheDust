<?php
/**
 * The first edition of a image tool developed by Sysborg using PHP GD Library
 * updated to php 8.1
 * author Anderson Arruda < contato@sysborg.com.br >
 */
namespace sysborg;

class btd{
    private array $allowedExtensions = [
        'image/bmp'   => 'bmp',
        'image/gif'   => 'gif',
        'image/jpeg'  => 'jpeg',
        'image/png'   => 'png',
        'image/x-tga' => 'tga',
        'image/web'   => 'webp'
    ];

    private array $resources = [
        'bmp'  => 'imagecreatefrombmp',
        'gif'  => 'imagecreatefromgif', 
        'jpeg' => 'imagecreatefromjpeg',
        'png'  => 'imagecreatefrompng',
        'tga'  => 'imagecreatefromtga',
        'webp' => 'imagecreatefromwebp'
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
        $this->gd = $type($filepath);

    }
}
?>