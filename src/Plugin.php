<?php

namespace Fastadmin\AddonsInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface
{

    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new Installer($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);

        if ($installer->supports($composer->getPackage()->getType())) {
            $installer->addAddons($composer->getPackage(), true);
        }
    }
}
