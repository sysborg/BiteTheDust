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
        if(!file_exists($this->filepath)){
            throw new \Exception('File path can\'t be reach, path: '. $this->filepath);
        }

        $this->mime = mime_content_type($this->filepath);
        if(!array_key_exists($this->mime, $this->allowedExtensions)){
            throw new \Exception('File mime type are not allowed. Mime type: '. $this->mime. ', allowed: '. implode(', ', array_keys($this->allowedExtensions)));
        }

        $type = $this->allowedExtensions[$this->mime];
        $this->gd = $type($filepath);

    }
}
?>