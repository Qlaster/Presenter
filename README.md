# ≡ Presenter
Ultra-fast and convenient templating engine



# ≡ Examples

### Быстрый старт


##### Подключение и вывод файла
```php
use QyberTech\Presenter;

$P = new Presentor();
$P->file("themes/index.html")->display();
```

##### Вывод контента
```php
use QyberTech\Presenter;

$content['title'] = 'Title';
$content['copyright'] = 'Copyright Year';

$P = new Presentor();
$P->file("themes/index.html")->display($content);
```

##### Для явного указания http пути к шаблону, можно использовать themelink:
```php
$P->file("themes/index.html")->themelink('/themes/myfolder')->display($content);
```

##### Получить все теги в документе:
```php
$P->file("themes/index.html")->get_tags();
```

##### Получить теги, которые шаблонизатор намерен обработать:
```php
$P->file("themes/index.html")->tag_list();
```

##### Добавить в секцию head свое содержимое:
```php
$P->head = "<link rel="icon" type="image/x-icon" href="/images/favicon.ico">";
```

##### Добавить тег base в секцию head со ссылкой на корень сайта:
```php
$P->base_html = true;
```

##### Пропустить все комментарии (\<!--- -->) при выводе в браузер:
```php
$P->config['skip_comments'] = true;
```
или во время создания
```php
$P = new Presentor(['skip_comments'=>true]);
```

##### Переназначить директорию для кешированных шаблонов (по умолчанию - системная временная директория, полученная через sys_get_temp_dir()):
```php
$P->config['compilation']['folder'] = '/custom/tmp/folder/';
```
или во время создания
```php
$P = new Presentor(['compilation'=>['folder'=>'/custom/tmp/folder/']]);
```

##### Жесткая замена
Шаблонизатор позволяет проводить жесткую замену строк игнорируя литеральные установки. Что бы использовать функцию, передайте replace параметры в конфигурации
```php
$P->config['replace']['find-string'] = 'replace-string';
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
Для вывода результатов функции можно использовать:
```php
{= inc($i)}
```
> Замечание! Пробел после знака равенства обязателен


##### Относительная ссылка ~/ на файлы ресурсов шаблона
Если понадобиться путь до директории, где лежит html (например, для подключения ресурсов)
> Замечание! Для ресурсов в тегах \<link href=""> и \<script src=""> этого можно не делать. Шаблонизатор самостоятельно определит http путь и поправит ссылки
```html
<link type="text/css" rel="stylesheet" href="~/styles/style.css"/>
<script src="~/js/jquery-2.1.1.js"></script>
```

##### Подключение внешнего фрагмента html
Шаблон можно нарезать на фрагменты и подключить в нужные места документа. Нет ограничений на количество и иерархию вложений.
 Пример реализации:
```php
{require "../section/menu.html"}
```
или использовать ключевое слово section
```php
{section "../section/menu.html"}
```
