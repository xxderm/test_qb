1) Муратшин Ильяс
* Запихнуть код из файла в https://phpize.online/
* Запихнуть sql
```
  CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  age INT
);
insert into users(name, age) values ('test1', 17);
insert into users(name, age) values ('test2', 18);
insert into users(name, age) values ('test3', 20);
insert into users(name, age) values ('test4', 30);
```
* Вариант использования:
```
<?php
$config = [
    'type' => 'mysql',
    'host' => 'localhost',
    'dbname' => '',
    'user' => 'root',
    'password' => ''
];

$db = new QueryBuilder($config);

$result = $db->select('*')->from('users')->where('age', '>', 18)->execute();
print_r($result);

$db->insert('users', ['name' => 'test5', 'age' => 40])->execute();
$result = $db->select('*')->from('users')->where('age', '>', 30)->limit(1)->execute();
print_r($result);

$db->update('users', ['name' => 'test88'])->where('name', '=', 'test5')->execute();
$db->delete('users')->where('name', '=', 'test1')->execute();
$result = $db->select('*')->from('users')->execute();
print_r($result);
?>
```
