# ≡ Presenter
Ultra-fast and convenient templating engine



# ≡ Examples

### Быстрый старт


##### Подключение и вывод файла 
```php
$P = new Presentor();
$P->file("themes/index.html")->display();
```

##### Вывод контента 
```php
$content['title'] = 'Title';
$content['copyright'] = 'Copyright Year';

$P = new Presentor();
$P->file("themes/index.html")->display($content);
```



### Разметка шаблона
Примеры использования шаблонизатора в html документах

##### Вывод одиночной переменной title
(вывод будет экранирован функцией htmlspecialchars)
```html
<title>{$title}</title>
```


##### Вывод одиночной переменной copyright
Вывод будет осуществлен в точности, как было передано. Чаще всего используется для передачи html содержимого
```html
<p>{$$copyright}</p>
```

##### Объявление служебной переменной и присвоение значения

```php
{;$x = 1}
```
или 
```php
{($x = 1)}
```

##### Условный оператор
```html
{if $logo}
	<img src="{$logo}" alt="logo" />
{end}
```
Блок условия завершается тегом {end}. Для ветвлений можно использовать следующую конструкцию:

```html
{if $logo}
	<img src="{$logo}" alt="logo" />
{else}
	<img src="blank.jpg" alt="logo" />
{end}
```

##### Цикл foreach
Циклический проход по списку
```html
{foreach $slider['main']['list'] as $value} 
	<li class="slider">
		<a href="{$value['link']}">
			<img src="{$value['image']}" alt="{$value['info']}" />
		</a>
	</li> 
{end}
```

##### Цикл for
Циклический проход по списку
```html
{for $i = 1; $i <= 10; $i++}
	<li class="page"> {$i} </li>
{end}
```

##### Литеральный тег
Тег применяется для структур, в которых не следует проводить анализ разметки. Шаблонизатор самостоятельно способен определять и пропускать литеральные структуры, такие как \<script> или \<style> и т.д. Однако, может потребоваться явно указать блоки документа, где это разбор проводить не следует, как в этом примере:
```html
{literal}
	<a href="#" onclick="{ajax();return false}"> Ссылка </a>
{/literal}
```

##### Использование нативного php в шаблоне
```html
{php}
	$var = 'Hello world!';  //Var set value 'Hello world'
	echo $var;		//Print to browset
{/php}
```
или обрамить классическим тегом
```html
<?php
	$var = 'Hello world!';  //Var set value 'Hello world'
	echo $var;		//Print to browset
?>
```

##### Вызов функций окружения и механизмов php
Вы можете использовать встроеные функции вашего окружения и самого php. 
> Замечание! Прозрачная трансляция тегов будет работать только если включена опция config['compilation']['nobody']
```php
{phpinfo()}
{inc($i)}
```

##### Относительная ссылка ~/ на файлы ресурсов шаблона
Если понадобиться путь до директории, где лежит html (например, для подключения ресурсов)
> Замечание! Для ресурсов в тегах \<link href=""> и \<script src=""> этого можно не делать. Шаблонизатор самостоятельно определит http путь и поправит ссылки
```html
<link type="text/css" rel="stylesheet" href="~/styles/style.css"/>
<script src="~/js/jquery-2.1.1.js"></script>
```

##### Подключение внешнего фрагмента html 
Шаблон можно нарезать на фрагменты и подключить следующим образом 
```php
{require "../section/menu.html"}
```
или использовать ключевое слово section
```php
{section "../section/menu.html"}
```


