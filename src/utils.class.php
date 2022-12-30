<?php
/**
 * Some useful functions that can help the btd class
 * updated to PHP 8.1
 * Author Anderson Arruda < contato@sysborg.com.br >
 */

 namespace sysborg;

 final class utils{
    /**
     * description      Generates a unique name that doesn`t exists in some directory
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           string $dirpath
     * @param           string $filetype
     * @return          string
     */
    public static function uniqueName(string $dirpath, string $filetype) : string
    {
        $filename = self::generatesFileName();
        while(is_file(rtrim($dirpath, DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR. '.'. $filetype)){
            usleep(1);
            $filename=self::generatesFileName();
        }
        return $filename;
    }

    /**
     * description      Returns microtime as possible unique name
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @version         1.0.0
     * @param           
     * @return          string
     */
    public static function generatesFileName() : string
    {
        return (string) microtime(true);
    }

    /**
     * description      Get the filename of a path
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           $file
     * @return          string
     */
    public static function getFileName(string $file) : string
    {
        preg_match('/[^\/]+$/', $file, $filename);
        return $filename[0];
    }

    /**
     * description      Adds prefix into filename in a complete file path
     * @author          Anderson Arruda < contato@sysborg.com.br >
     * @param           string $file
     * @param           string $prefix
     * @return          string
     */
    public static function addPrefixFile(string $file, string $prefix) : string
    {
        $filename = self::getFileName($file);
        return str_replace($filename, $prefix.$filename, $file);
    }
 }
 ?>