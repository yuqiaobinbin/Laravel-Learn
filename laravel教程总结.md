## 访问路由提前工作
apache配置文件
```
LoadModule rewrite_module modules/mod_rewrite.so
```
所有 AllowOverride  None 改成 AllowOverride  All


## foreach
```
@foreach($pizzas as $pizza)
    <div>
       {{ $loop->index }} {{ $pizza['type'] }}
        @if($loop->first)
            <span>-first in the list</span>
            @endif
        @if($loop->last)
            <span>-last in the list</span>
        @endif
    </div>
@endforeach
```

## 创建样式
创建layout.blade.php,把需要用到的样式全部导入到里面，可以填充的内容使用@yield('content')

'content'可以写成别的字符串，但是要匹配相应内容

## 引用样式：
@extends('layouts.layout')     _//文件名，不需要加blade.php_

@section('content')     *//匹配@yield()括号内内容*

@endsection

## 图片
```
<img src="/img/pizza-house.jpg" alt="logo"/>
```
注意public下的文件是只需要/就可以

## get方式
(?name=---)
```
$name = request('name');
    return view('name' => $name]);
```

## 控制器
php artisan make:controller PizzaController
>创建PizzaController控制器
在PizzaController控制器中
```
public function show($id){
        return view('details',['id' => $id]);
}
```
在route中调用
```
Route::get('/pizzas/{id}', 'PizzaController@show');
```


## 数据库调用
.env文件修改：
```
DB_DATABASE=pizzahouse
DB_USERNAME=root
DB_PASSWORD=
mysql中：
create database pizzahouse
```
保存配置
```
php artisan serve
```



## migrate（用来创建表单)
### 简介

0. 基本语法

```php artisan migrate:fresh```

 命令将会先从数据库中删除所有表然后执行 migrate 命令


```php artisan migrate:refresh```

 命令将会先回滚所有数据库迁移，然后运行 migrate 命令。这个命令可以有效的重建整个数据库


**注意：refresh不会删除不是使用迁移文件创建的表**

```php artisan migrate:rollback``` 

想要回滚最新的一次迁移”操作“，可以使用 rollback 命令，注意这将会回滚最后一批运行的迁移，可能包含多个迁移文件：


```php artisan migrate:reset```

 命令将会回滚所有的应用迁移,事实上，他的意思是rollback all database migrations




1. php artisan make:migration create_pizzas_table   *//创建一个表单*

2. In directory: database/migrations/2020  *//在这个目录下有一个文件，可以往新增加的文件中写入表单参数*
```
$table->id();
$table->timestamps();
$table->string('type');
$table->string('base');
$table->string('name');
```
3. php artisan migrate   *//开始migrate*
4. php artisan migrate:status   *//列出status*
5. 如果想要增加一列怎么办？
    - 一种不怎么好的方法

        php artisan migrate:rollback  //回溯

        php artisan migrate //创建

    - 正确的方法
        ```
        php artisan make:migration add_price_to_pizzas_table
        ```
        找到文件，在文件中，添加想要增加的那一列

        ```
        php artisan migrate
        ```
        至此，已经完成新增的那一列


## model

** 一个用处就是调用数据库中的数据**

建立model
```
php artisan make:model Pizza
```

**在app目录下会出现一个Pizza.php（这个就是model）**

在app/http/controller目录下的PizzaController.php下写

```
use App\pizza;
```
```
$pizzas = Pizza::all();
```


或者一下任意一种（这对应着不同的判别方式）
```
 $pizzas = Pizza::orderBy('name','desc')->get();
```
```
$pizzas = Pizza::where('type','hawaiian')->get();
```
```
$pizzas = Pizza::latest()->get();
```


特别的,将视图传给pizza
```
return view('pizzas', [
            'pizzas' => $pizzas,
        ]);
```

在/resources/view/pizzas.blade.php下可以调用数据库的内容
```
<div>
    {{ $pizza->name }} - {{ $pizza->type }} - {{ $pizza->base }}
</div>
```



## 命名规范

命名规范表

| Request | route | controller&action | Views |
|  ----   | ----  |      ----         |  ---- |
| GET  | /pizzas | PizzaController@index | index |
| GET  | /pizzas/{id} | PizzaController@show | show |
| GET  | /pizzas/create | PizzaController@create | create |


可以在/resources/views目录下创建一个文件夹pizzas
这相当于一个小项目下的内容，所有相关的views可以放在里面

然后命名一个index.blade.php和一个shows.blade.php

controller里面的调用

```
public function show($id){
        return view('pizzas.show',['id' => $id]);
    }
```
注意调用方式是
```
pizzas.show
```

其他注意事项：
如果路由冲突，放在前面的起作用


## 调用数据库

pizzacontroller
```
 public function show($id){
        $pizza = Pizza::findOrFail($id);
        return view('pizzas.show',['pizza' => $pizza]);
    }
```
controller把数据传送给view

show.blade.php
```
@section('content')
    <div class="wrapper pizza-details">
        <h1>Order for {{ $pizza->name }}</h1>
        <p class="type">Type - {{ $pizza->type }}</p>
        <p class="base">Base - {{ $pizza->base }}</p>
    </div>
    <a href="/pizzas" class="back"><-Back to all</a>
@endsection
```

效果：网页下 /pizzas/1显示第一种pizza，/pizzas/2显示第二种pizza，不存在的显示404

## 创建表单

view/create.blade.php
```
@extends('layouts.layout')

@section('content')
    <div class="wrapper create-pizza">
        <h1>Create a new pizza</h1>
        <form action="/pizzas" method="POST">
            <label for="name">Your name::</label>
            <input type="text" id="name" name="name">
            <label for="type">Choose your pizza type</label>
            <select name="type" id="type">
                <option value="margarita">Margarita</option>
                <option value="hawaiian">Hawaiian</option>
                <option value="veg supreme">Veg Supreme</option>
                <option value="volcano">Volcano</option>
            </select>
            <select name="base" id="base">
                <option value="cheesy crust">cheesy crust</option>
                <option value="garlic crust">garlic crust</option>
                <option value="thin & crisp">thin & crisp</option>
                <option value="thick">Thick</option>
            </select>
            <input type="submit" value="Order Pizza">
        </form>
    </div>
@endsection
```

## 表单Post


路由使用POST请求
```
Route::post('pizzas','PizzaController@store');
```

如果提交表单后出现错误，则需要在表单中添加CSRF字段。
```
<form method ="POST" action ="/profile"> 
    @csrf 
    ... 
</ form>
```


## 表单数据提交到数据库


### 提交方法

在pizzacontroller里面对某一种方法
```
public function store(){
        $pizza = new Pizza;
        $pizza->name = request('name');
        $pizza->type = request('type');
        $pizza->base = request('base');

        $pizza->save();
        return redirect('/')->with('message','Thanks for your order');
    }
```



### 友好展示

在pizzacontroller里面
```
return redirect('/')->with('message','Thanks for your order');
```

在welcome.blade.php里面添加一个标签，这是一个session信息
```
<p class="message">{{ session('message') }}</p>
```




## 数组和JSON

如果要添加一个数组到数据库当中，我们应该怎么做呢

首先添加一列数据在migration中
```
$table->json('toppings');
```
直接刷新数据表（会丢失之前的数据）
```
php artisan migration:refresh
```

在view视图里面添加多选框
```
<fieldset>
                <label>Extra toppings:</label>
                <input type="checkbox" name="toppings[]" value="mushrooms">Mushrooms<br/>
                <input type="checkbox" name="toppings[]" value="pepper">Pepper<br/>
                <input type="checkbox" name="toppings[]" value="garlic">Garlic<br/>
                <input type="checkbox" name="toppings[]" value="olives">Olives<br/>
            </fieldset>
```
注意name是toppings[  ]

在控制器PizzaController中截取数据
```
$pizza->toppings = request('toppings');
        $pizza->save();
```

在model中：Pizza.php

将'toppings'json转换成数组，以便输出
```
protected $casts = [
        'toppings' => 'array'
    ];
```
验证数据是否存放成功

在show.blade.php中列表输出toppings

```
<ul>
            @foreach($pizza->toppings as $topping)
                <li>{{ $topping }}</li>
                @endforeach
        </ul>
```

## 删除

show.blade.php
```
<form action="/pizzas/{{ $pizza->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button>Complete Order</button>
        </form>
```
route->web.php
```
Route::delete('/pizzas/{id}','PizzaController@destroy');
```
PizzaController
```
public function destroy($id){
        $pizza = Pizza::findOrFail($id);
        $pizza->delete();

        return redirect('/pizzas');
    }
```


## sass

安装node.js
```
# wget https://nodejs.org/dist/v10.9.0/node-v10.9.0-linux-x64.tar.xz    // 下载

# tar xf  node-v10.9.0-linux-x64.tar.xz       // 解压

# cd node-v10.9.0-linux-x64/                  // 进入解压目录

# ./bin/node -v                               // 执行node命令 查看版本

ln -s bin/npm /usr/local/bin/

ln -s bin/node /usr/local/bin/

```

npm 安装
```
npm install
```

在webpack.mix.js文件夹当中修改代码如下
```
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/main.scss','public/css');
```
> 这意味着从resources/sass/main.scss  =>=> public/css

> 注意不要多加分号

编译scss(从resources/sass/目录到->public/css)
```
npm run dev
```

如果有更新scss,执行并修改文件，他就会自动编译
```
npm run watch
```

> scss教程参考别的




## 身份验证

安装laravel/ui
```
composer require laravel/ui
```

php使用ui(这里会报错，解决方案在下面)
```
php artisan ui vue --auth
npm install
npm run dev
```

ui报错解决方案

1. 创建一个 1G 大小的文件
```
/bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
```
2. 格式化该文件
```
/sbin/mkswap /var/swap.1
```
3. 将该文件挂载至文件系统中
```
/sbin/swapon /var/swap.1
```

此时，resources/view有auth的view

route/web.php下有一条新路由

app/http/controllers有新的控制器

统一布局，修改引入文件

@extends('layouts.app')

## 设置权限

在route中设置验证
```
Route::get('/pizzas','PizzaController@index')->middleware('auth');
```

如果要保护所有的路由，可以在控制器中设置
```
public function __construct()
    {
        $this->middleware('auth');
    }
```

## 去除注册界面

查看所有路由
```
 php artisan route:list
 ```

 路由
 ```
 Auth::routes([
    'register' => false
]);
```
这是因为 app.blade.php有如下语句 
```
@if (Route::has('register'))
    <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
    </li>
@endif
```

重命名路由
```
Route::get('/pizzas','PizzaController@index')->name('pizzas.index')->middleware('auth');
```
这样，使用如下语句就可以调用
```
{{ route('pizza.index') }}
```
如果后面有参数
```
{{ route('pizza.index'，$pizza->id) }}
```

简易创建

| model | controller | migration |
|  ----   | ----  |      ---- |
| Pizza  | PizzaController | create_pizzas_table |
| Kebab  | KebabController | create_Kebabs_table |


```
 php artisan make:model Kebab -mc

```