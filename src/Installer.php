<?php

namespace Fastadmin\AddonsInstaller;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class Installer extends LibraryInstaller
{

    protected $packageTypes = [
        'fastadmin-addons' => 'addons/'
    ];

    public function getInstallPath(PackageInterface $package)
    {
        $type = $package->getType();
        if (!$this->supports($type)) {
            throw new InvalidAddonsException($package, '类型不支持');
        }
        $base = $this->packageTypes[$type];
        $extra = $package->getExtra();
        if (empty($extra['name'])) {
            throw new InvalidAddonsException($package, '插件没定义名称');
        }
        return $base.$extra['name'];
    }

    public function supports($packageType)
    {
        return array_key_exists($packageType, $this->packageTypes);
    }


    public function addAddons(PackageInterface $package, $isRoot = false)
    {

    }
}