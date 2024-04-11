
# thinkex 使用规范

---

## 1/模块接口 (以demo模块为例子)

> 示例模块-代码定制
~~~
domain 根目录
    |__ demo 模块目录
        |___port                应用层-端口业务
        |    |___controller     应用层-控制器(控制器=端口)
        |    |___request        应用层-输入验证
        |    |___logic          应用层-服务函数组合层(组合)
        |    |___trans          应用层-输出过滤
        |___config              领域层-模块配置
        |___enum                领域层-常量枚举值
        |___error               领域层-业务异常值
        |___srv                 领域层-服务函数层
        |___repository          领域层-仓储层
        |___model               基础层-MySql数据模型
        |___aggr                领域层-聚合(curl/RPC/OpenAPI的聚合层)
        |___entity              基础层-实体(curl/RPC/OpenAPI的数据模型)
        |___edoc                基础层-实体(ElasticSearch数据模型)
        |___job                 领域层-队列        
        |___console             领域层-业务指令
~~~

---

## 2/示例模块-接口调用规范

### 说明
~~~
如果是自动生成的接口代码, 且执行主表自定义查询, 都默认支持下面这些查询query.
~~~

### POST 类型接口
~~~
没有特别规定
~~~

### GET 类型接口
~~~
get接口若要开启临时缓存, 统一添加query参数: 'time'. 约定不传该参数时,默认调用缓存 ; 传 'time=1' 时, 跳过缓存.
~~~

> 获取-列表-实时 & 获取-详情-实时:
~~~
{{base_url}}/demo/sample/index ?time=1
{{base_url}}/demo/sample/read/1 ?time=1
----------------------------------------
'time'      |   number   |  跳过缓存: 不跳过=0,跳过=1, 默认不跳过.
~~~

> 获取-列表-翻页:
~~~
{{base_url}}/demo/sample/index ?pagination=true &page=1 &page_size=3
----------------------------------------
'pagination'   | boolean | 翻页 打开=true,关闭=false; 关闭时,一页100条数据上限; 默认20;
'page'         | number  | 页码 默认1
'page_size'    | number  | 页数 默认20
~~~

> 获取-列表-排序:
~~~
{{base_url}}/demo/sample/index ?sort=-id
----------------------------------------
'sort' | string | 自定义排序, 正序= id , 倒序= -id ; 默认倒序, id可以是其它字段;
~~~

> 获取-列表-筛选动作 `search` :
~~~
{{base_url}}/demo/sample/index ?search=user &type=1 &status=1,2 &name=陈%
----------------------------------------
'search'       |  string  |  触发"user-用户端"筛选动作, 默认值对应实际api根路径名,而这里是'demo'. 若有其它筛选动作,再增加动作名称.
'type'         |  string  |  触发筛选动作时, 添加 type = 1 的筛选条件, '=,>,<,>=,<='运算符,服务端内部设定,前端不用关心. 字段名对应表字段.
'status'       |  string  |  触发筛选动作时, 添加 status in 1,2 的筛选条件, 即包含条件. 字段名对应表字段.
'name'         |  string  |  触发筛选动作时, 添加 name like 陈% (或 陈*) 的筛选条件, 即"陈"开头的姓名. 字段名对应表字段.
~~~

> 获取-列表-关联副表数据:
~~~
{{base_url}}/demo/sample/index ?include=image,video
----------------------------------------
'include'      |   string   | 指定关联模型 关联 image,video 数据, 需要服务端定制;
----------------------------------------
include=? 关联数据: 按场景选择需关联对象,提高接口性能.
~~~

> 获取-列表-副表扩展查询:
~~~
{{base_url}}/demo/sample/index ?extend=user_event_have &type=1 &status=1 ...
----------------------------------------
'extend'             |  string   | 自动扩展查询动作 user_event_have, 需要服务端定制;
'type','status',...  |  string   | 触发查询动作 user_event_have 时, 传递的查询参数, 可识别多个参数, 需要服务端定制;
----------------------------------------
extend=? 副表查询动作: 按需要触发.
~~~

---

## 3/示例模块代码 按数据表设计 生成 基本curd业务
~~~
根据示例模块结构, 自动生成需要的子功能接口,
且按照相应功能或数据表结构, 提供CURD基本接口, 并能满足一般的增删查改需要, 取消注释就能使用.
接口业务逻辑统一写在service类里, 方便跨业务或单元测试调用.

> 创建模块+目录结构
php thinkex module:make test

> 以下指令,适用于此参数结构:
[php,thinkex,module:*,'控制器名','模块名','可选生成curd函数(c,u,r,d,bc,bu,br,bd,cj,cmd)','指定数据库连接配置']

> 创建模块-路由 -无数据库
php thinkex module:route TestChild test

> 创建子接口组 -无数据库
php thinkex module:base TestChild test c,u,r,d
> 创建子接口组 -有数据库 
php thinkex module:base-on TestChild test c,u,r,d mysql

> 新建-模型 -无数据库
php thinkex module:model TestChild test   
> 新建-模型 -有数据库
php thinkex module:model-on TestChild test mysql

> 更新-模型 过滤字段 -有数据库
php thinkex module:model-fields TestChild test mysql
> 更新-转化器 过滤字段 -有数据库
php thinkex module:trans-fields TestChild test mysql

> 创建模块-业务指令 -无数据库
php thinkex module:cmd TestChild test     
> 创建模块-业务指令 -有数据库
php thinkex module:cmd-on TestChild test mysql

> 创建模块-消息队列 -无数据库
php thinkex module:job TestChild test
> 创建模块-消息队列 -有数据库
php thinkex module:job-on TestChild test mysql

~~~



