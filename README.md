## 疯狂星期四插件

### 要求

需要协程环境

炸毛框架 > 3.0.2

php > 8.1 或框架Driver为swoole (默认workerman)

### 安装
```
./zhamao plugin:install https://github.com/FishZe/zhamao_plugin_code_runner
```

### 使用

```
> code / 代码运行 / codeRunner / 运行代码 [可选语言]
> 请发送代码:
> [代码]
> 请发送输入:
> [stdin]
> [运行结果]
```
如:
```
> code python
> 请发送代码:
> print('hello world')
> 请发送输入:
> 0
> hello world
```
