  ## <p align="center">童阅图书馆</p>



### 系统介绍

童阅图书馆是一款在线借阅微信公众号，主要是开通会员后，用户在公众号中进行图书借还，后台审核后进行配送，可及时查看借阅状态。


### 系统结构
- Laravel version :5.5
- Mysql version: 5.7
- PHP7.3
- Linux系统
- Nginx服务器

#### 安装配置
- 安装 php+mysql+redis+nginx环境,配置nginx运行环境.

- 导入数据：将backup目录下的sql导入mysql中.

- 配置env,主要配置mysql,公众号、支付、短信等信息.


- 安装核心库文件，项目根目录执行
    ```
    composer install
    ```
- 更改`storage`目录权限
    ```
    sudo chmod -R 777 storage
    ```
- 读取上传文件使用命令 `storage:link` 来创建符号链接：
    ```
    php artisan storage:link
    ```
