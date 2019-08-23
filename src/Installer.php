<?php

namespace Fastadmin\AddonsInstaller;

use Composer\Composer;
use Composer\Installer\BinaryInstaller;
use Composer\Installer\LibraryInstaller;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Util\Filesystem;
use think\addons\Service;
use think\App;
use think\Db;


class Installer extends LibraryInstaller
{

    protected $packageTypes = [
        'fastadmin-addons' => 'addons/'
    ];

    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {

        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);
    }


    public function initBase()
    {
        //加载项目基础文件
        $this->io->write('加载项目基础文件');
        !defined('APP_PATH') && define('APP_PATH', realpath($this->vendorDir.'/../application/').'/');
        $base_file = APP_PATH . '../thinkphp/base.php';
        if (file_exists($base_file) && class_exists(App::class) && class_exists(Db::class)) {
            include_once $base_file;
            if (!empty(App::initCommon())) {
                try {
                    Db::execute("SELECT 1");
                    return true;
                } catch (\Exception $e) {
                }
                $this->io->write('已加载');
            }
        }

        throw new \Exception('请检查Fastadmin是否已经正确安装');
    }

    public function getInstallPath(PackageInterface $package)
    {
        $type = $package->getType();
        if (!$this->supports($type)) {
            throw new InvalidAddonsException($package, '类型不支持');
        }
        $base = $this->packageTypes[$type];
        $name = $this->getExtraName($package);
        return $base.$name;
    }

    public function supports($packageType)
    {
        return array_key_exists($packageType, $this->packageTypes);
    }


    public function getExtraName(PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (empty($extra['name']) || !preg_match('/^[a-z][a-z0-9]+$/', $extra['name'])) {
            throw new InvalidAddonsException($package, '此插件没定义合法的插件名称');
        }
        return $extra['name'];
    }



    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->initBase();

        $name = $this->getExtraName($package);
        $this->io->write('正在安装插件:'.$name.' <'.$package->getPrettyName().'>');
        $this->io->write('安装目录:'.$this->getInstallPath($package));
        $this->io->write('开始下载');
        parent::install($repo, $package);
        $this->io->write('下载完成');
        $this->io->write('执行安装');
        if (Service::install($name, false, [], false)) {
            $this->io->write('已安装');
        }
    }


    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $this->initBase();

        $name = $this->getExtraName($initial);
        $this->io->write('正在更新插件:'.$name.' <'.$initial->getPrettyName().'>');
        $this->io->write('禁用插件');
        if (Service::disable($name)) {
            $this->io->write('已禁用');
        }

        parent::update($repo, $initial, $target);
        $this->io->write('更新完成');
        $this->io->write('启用插件');
        if (Service::enable($name)) {
            $this->io->write('已启用');
        }
    }


    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->initBase();
        $name = $this->getExtraName($package);
        $this->io->write('正在卸载插件:'.$name.' <'.$package->getPrettyName().'>');
        $this->io->write('禁用插件');
        if (Service::disable($name)) {
            $this->io->write('已禁用');
        }

        $this->io->write('卸载插件');
        if (Service::uninstall($name)) {
            $this->io->write('已卸载');
        }
        $this->io->write('清空目录');
        parent::uninstall($repo, $package);
        $this->io->write('已清空');
    }
}

