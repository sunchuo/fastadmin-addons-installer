<?php

namespace Fastadmin\AddonsInstaller;

use Composer\Package\PackageInterface;
use Throwable;

class InvalidAddonsException extends \Exception
{

    private $package;
    private $error;

    public function __construct(PackageInterface $package, $error = '', Throwable $previous = null)
    {
        $this->package = $package;
        $this->error = $error;

        parent::__construct("安装失败：" . $package->getPrettyName() . ': ' . $error, 0, $previous);
    }

    public function getPackage()
    {
        return $this->package;
    }

    public function getError()
    {
        return $this->error;
    }
}

