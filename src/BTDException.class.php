<?php

namespace sysborg;
use \Exception;

class BTDException extends Exception{
    const BTD_ERRORS = [
        0 => 'Can\'t reach file',
        1 => 'File mime type are not allowed',
        9999 => 'Unknow Error'
    ];

    /**
     * description      Throw new Exceptio using custom exception for BTD
     * @author          Anderson Arruda < andmarruda@gmail.com >
     * @version         1.0.0
     * @param           int $errorCode
     * @return          void
     */
    public function __construct(int $errorCode)
    {
        $errorCode = isset(self::BTD_ERRORS[$errorCode]) ? $errorCode : 9999;
        parent::__construct(self::BTD_ERRORS[$errorCode], $errorCode);
    }

    /**
     * description      Makes a friendly error string
     * @author          Anderson Arruda < andmarruda@gmail.com >
     * @version         1.0.0
     * @param           
     * @return          string
     */
    public function __toString(): string
    {
        return '['. $this->getCode(). ']:'. $this->getMessage(). ' in file '. $this->getFile(). ', line: '. $this->getLine();
    }
}