##  媒体信息发布平台

### 项目介绍
* 一个自媒体信息发布平台
### 项目构成
	服务层 apache+php
	数据层 mysql
	应用层 后台管理 和 前台展示
### 项目基本业务介绍
* 网站后台管理界面 
  *  用户登录
  *  分类管理
  *  评论管理
  *  用户管理
  *  网站设置
* 网站前台展示界面
  * 公共模块
  * 首页
  * 列表页
  * 详细页

### 功能点分析

​	

* ​

* 表单提交

  * 浏览器

    * 页面form表单设置

      * `method="post"`  `action="<?php echo $_SERVER['PHP_SELF']; ?>"` 
      * 上传文件注意
      * input  =>  `name="email"`  `name="password"`
      * 添加`submit`按钮

    * 服务器

        * 1-首先判断是POST还是GET请求

            * ​

        * 2-接受表单数据并进行逻辑校验是否完整

            *  `$_POST` 或 `$_GET`

            * 校验 是否为空 `empty($_POST['xxxx'])`

            * 如果为空 返回提示信息

              `$GLOBALS['message'] = '没有填写用户名';`	

            * 文件校验 
            * 校验完毕接受参数

        * 3- 业务校验

            * 判断用户名是否存在 ,用户名密码是否匹配
            * 链接数据库进行查询语句,是否存在用户名,在比较对应密码是否匹配 
            * ​

        * 4 -数据库链接查询  通过查询语句 进行增删改查的业务功能

        *    ​

        * 链接数据库
            * 连接数据库`$conn = mysqli_connect(HOST, USER, DB_PASS, DB_NAME);`
            * 设置查询语句 `$sql='增删改查sql'`
            * 设置查询语句 `$query = mysqli_query($conn, $sql)`
            * 校验查询是否成功  `if (!$query){ }`
            * ​

### 开发阶段

#### 项目开发环境搭建
* apache安装与配置
* php安装与配置
* mysql安装与配置

#### 项目目录结构搭建

* 项目最基本的分为两个大块，前台（对大众开放）和后台（仅对管理员开放）


  * 一般在实际项目中，很多人会吧前台和后台分为两个项目去做，部署时也会分开部署：

    * 前台访问地址：https://www.wedn.net/
    * 后台访问地址：https://admin.wedn.net/

  * 这样做相互独立不容易乱，也更加安全。但是有点麻烦，所以我们采用更为常见的方案：让后台作为一个子目录出现。这样的话，大体结构就是：

    * 前台访问地址：https://www.wedn.net/
    * 后台访问地址：https://www.wedn.net/admin/

* 基本目录结构 

```
└── baixiu ······································ 项目文件夹（网站根目录）
    ├── admin ··································· 后台文件夹
    │   └── index.php ··························· 后台脚本文件
    ├── static ·································· 静态文件夹
    │   ├── assets ······························ 资源文件夹
    │   └── uploads ····························· 上传文件夹
    └── index.php ······························· 前台脚本文件
```
#### 数据库设计

TODO  待定  数据库 要设计的合理

### 用到的技术点

* bootstrap ---
* echarts ---图表
* font-awesome  ---字体图标库
* jquery  ---
* jsrender ---模板引擎（基于原生js）
* moment  ---格式化时间
* nprogress  --- 进图条
* require.js ---JavaScript模块加载器  了解
* simplemde  ---富文本编辑器
* swipe  ---移动web页面内容触摸滑动类库（暂未应用，考虑移动端）
* twbs-pagination  ---jquery分页插件:jquery.twbsPagination 
* ueditor ---富文本编辑器
* ​