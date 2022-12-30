<?php
    namespace sysborg;

    final class autoloader{
        private static array $loadPath = [
            'sysborg\BTDException'  => '/BTDException.class.php',
            'sysborg\btd'           => '/btd.class.php',
            'sysborg\utils'         => '/utils.class.php'
        ];

        /**
         * @description-en-US       Get class's paths
         * @description-pt-BR       Pega os caminhos da classe
         * @author                  Anderson Arruda < andmarruda@gmail.com >
         * @version                 1.0.0
         * @access                  public
         * @param                   string $className
         * @return                  string
         */
        public static function getClassPath(string $className) : string
        {
            return self::$loadPath[$className];
        }
    }

    spl_autoload_register(function($cls){
        require_once __DIR__. \sysborg\autoloader::getClassPath($cls);
    });
?>