## 广大华软社团联合会官网后端

> 后端使用了ThinkPHP + mysql，包含了前台跟后台api接口，接口风格用了RESTful，太简单的一个系统，没什么可说的

## 目录

```
├── app						# 网站应用目录
│	├── api					# api应用
│		├── controller		# 控制器层
│		├── lib				# 错误处理lib
│		├──	model			# 模型层
│		└── service			# 服务层
│	├── common				# 公共模块
│	└── vbs					# vbs执行脚本
├── extend					# 扩展类库目录
├── public					# 静态资源存放目录
│	├── static				# 静态资源
│	├── upload				# 文件上传目录
│	├── video				# 视频上传目录
│	├── index.php			# 框架入口文件
│	├── router.php			# 路由
│	├── robots.txt			# 搜索引擎robots.txt
│	└── favicon.ico			# favicon.ico
├── runtime					# 应用运行时的目录
├── tests					# 测试
├── thinkphp				# tp5 框架目录
├── vendor					# 第三方类库目录
├── build.php				# 自动生成定义文件
├── composer.json			# composer.json文件
├── phpunit.xml				# phpunit配置文件
└── think					# 命令行入口文件
```

