# webhook部署 码云 or Coding

## 食用 

  **以下服务端的操作，均为在root身份下操作！**
  
  0.设置php.ini取消关闭shell_exec
  
```
    vi /usr/local/php/etc/php.ini
```

  **找到disable_function,把里面包含的shell_exec去掉!!**
  
  1.生成sshkey
  
```shell
   $ ssh-keygen -t rsa -C "youremail@xxx.com"
```
  
  按三个回车，然后生成了ssh key
  
```shell
   $ git config --global user.name "yourname"
   $ git config --global user.email  "youremail@xxx.com"
```
  
  2.添加到ssh-agent
  
```shell
   $ eval "$(ssh-agent -s)"
   $ ssh-add ~/.ssh/id_rsa
```
  
  3.获取公钥交给码云或者coding
  
```shell
   $ cat /root/.ssh/id_rsa.pub
```

  在码云相关页面部署上ssh key
  
  4.部署一个可以访问php的端口脚本(lnmp add vhost)并且配置vhost文件
  
  5.在部署目录把此文件夹的文件扔上去，并给上777权限
  
  6.设置www免密码
  
```shell
   $ visudo
```

  在文件末行加上

```
    www ALL=NOPASSWD:ALL
```
  
  ctrl+x 然后按y按回车保存并退出nano
  
  7.去到码云的项目设置，找到webhook，填上访问脚本的url，填上密码
  
  8.配置脚本目录的Config.php，其中的Secret相当于码云webhook中的password(coding中的token)