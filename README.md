# 使用composer管理fastadmin插件


- 插件代码可以独立的用git管理，自己针对插件做二次开发后，还可以随时合并官方插件的变更。
- 插件代码更新后，使用composer把插件更新到最新的代码。


# 使用方法


### 安装 fastadmin/addons-installer

```
composer require fastadmin/addons-installer dev-master
```

或者更改你的项目的composer.json，在 repositories 增加 fastadmin/addons-installer 的仓库地址后，执行安装命令。

```
"repositories": {
  {
     "type": "git",
     "url": "git@github.com:sunchuo/fastadmin-addons-installer.git"
   }
}
```




### composer update 安装 fastadmin/addons-installer


### 把自己的插件代码放到git仓库，需要在插件根目录创建composer.json文件，内容如下（去掉注释，json不支持注释）：

```json
{
    "name": "fastadmin/addons-cms", //插件包名，composer安装、更新、删除使用的包名.
    "type": "fastadmin-addons", //包类型固定为：fastadmin-addons，为了告诉composer这个包是个fastadmin的插件。
    "minimum-stability": "dev", //最小稳定版本，除非你知道是啥意思，否则不用变。
    "version": "0.0.1", //版本号，随意
    "extra": {
        "name": "cms" //插件真实名称，比如 cms，ask等，插件安装之后的根目录名称，插件不会安装到 vendor目录，而是会被安装到 addons/<插件名称> 目录。
    }
}
```
### 修改主项目 composer.json 在 repositories 里 增加你插件的仓库地址

```
    "repositories": {
       {
            "type": "git",
            "url": "git@github.com:sunchuo/fastadmin-addons-cms.git" //修改成你自己的插件git仓库地址
        }
    }
```


### 安装插件：

```composer require fastadmin/addons-cms dev-master```

![](https://raw.githubusercontent.com/sunchuo/fastadmin-addons-installer/master/preview0.png)


### 更新插件
```
composer update fastadmin/addons-cms
```

![](https://raw.githubusercontent.com/sunchuo/fastadmin-addons-installer/master/preview1.png)
### 删除插件

```
composer remove fastadmin/addons-cms
```

![](https://raw.githubusercontent.com/sunchuo/fastadmin-addons-installer/master/preview2.png)






