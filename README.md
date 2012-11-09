magicwordpress
==============

magic project of wordpress

配置要点

假设服务器已经有相同版本的数据库

#更改网站根地址
update wp_options set option_value = 'http://yourdomain/yourrelativepath' where option_name = 'siteurl'
update wp_options set option_value = 'http://yourdomain/yourrelativepath' where option_name = 'home'

#添加 wp-config.php
*confidentiality* 内部配置文件

#添加 magicsso
git clone xxxx/magicsso
到 wordpress / 下, 文件夹名称固定为 magicsso
