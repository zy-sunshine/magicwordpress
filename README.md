magicwordpress
==============

magic project of wordpress

配置要点

假设服务器已经有相同版本的数据库

#更改网站根地址
    update wp_options set option_value = 'http://yourdomain/yourrelativepath' where option_name = 'siteurl';
    update wp_options set option_value = 'http://yourdomain/yourrelativepath' where option_name = 'home';

#添加 wp-config.php
*confidentiality* 内部配置文件

#添加 magicsso
git clone xxxx/magicsso
到 wordpress / 下, 文件夹名称固定为 magicsso

#配置 magicsso
在wp-config.php末尾加入以下两行配置
    define('SSO_ROOT', dirname(__FILE__)."/magicsso/"); // magicsso 的文件夹绝对路径
    define('SSO_URL', 'http://218.56.161.12:81/magicsso'); // magicsso 的URL路径
