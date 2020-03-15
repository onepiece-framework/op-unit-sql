Unit of SQL
===

# Usage

## Insert

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['set]['column_a'] = $value_a;
$config['set]['column_b'] = $value_b;
$config['update']['column_b'] = null; // ON DUPLICATE UPDATE

//  Generate SQL.
$sql = Unit('SQL')->DML()->Insert($config);
```

## Select

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['limit'] = 1;
$config['where']['column_name'] = $value;

//  Generate SQL.
$sql = Unit('SQL')->DML()->Select($config);
```

## Update

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['limit'] = 1;
$config['where']['column_name'] = $value;
$config['set]['column_a'] = $value_a;
$config['set]['column_b'] = $value_b;

//  Generate SQL.
$sql = Unit('SQL')->DML()->Update($config);
```

## Delete

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['limit'] = 1;
$config['where']['column_name'] = $value;

//  Generate SQL.
$sql = Unit('SQL')->DML()->Delete($config);
```

## Where

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['limit'] = 1;
$config['where'][] = ' column_1  = 1';
$config['where'][] = ' column_2 >= 1';
$config['where'][] = ' column_3 != 1';
$config['where'][] = " column_4 =  'null'"; // Match null string
$config['where'][] = ' column_4 =   null';  // Match NULL
$config['where'][] = ' column_4 =   NULL';  // Match NULL
$config['where'][] = ' column_4 is  null';
$config['where'][] = ' column_5 not null';
$config['where'][] = ' column_6 in  1, 2, 3';
$config['where'][] = ' column_7 like %_word_%';// Underbar is automatically escape.
$config['where'][] = ' column_8 !in  1, 2, 3'; // Not in
$config['where'][] = ' column_9 !like %word%'; // Not like
$config['where'][] = ' column_0 between 1 to 3';

//  Generate SQL.
$sql = Unit('SQL')->DML()->Select($config);
```

## Order

 Add order, Can specify more than one at separating them with comma.

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['limit'] = 1;
$config['order'] = 'id, timestamp desc';
$config['where']['column_name'] = $value;

//  Generate SQL.
$sql = Unit('SQL')->DML()->Select($config);
```

## Offset

```php
//  Configuration.
$config = [];
$config['table'] = 'table_name';
$config['where']['column_name'] = $value;
$config['limit'] = 10;
$config['offset']= ($config['limit] * $_GET['page'] ?? 1) - $config['limit];

//  Generate SQL.
$sql = Unit('SQL')->DML()->Select($config);
```

## Increment

```php
//  Generate config.
$config = [];
$config['database] = 'database_name';
$config['table']   = 't_table';
$config['where'][] = 'id = 1';
$config['set'][]   = 'score + 1'; // increment
$config['limit']   = 1;

//  Update
$result = $app->DB('mysql://username:password@localhost/')->Update($config);
```

## Table join

 This is simple example.
 `table_name.field_name <= table_name.field_name`
 `<=` is LEFT JOIN. If right join is `=>`.

 Simple table join.

```php
$config['table'][] = 't_user.id <= t_mail.id';
```

 Multi table join is comma separate.

```php
$config['table'][] = 't_user.id <= t_mail.id, t_mail.id <= t_addr.id';
```

 More easy to readable.

```php
$config['table'][] = 't_user.id <= t_mail.id';
$config['table'][] = 't_mail.id <= t_addr.id';
```

 Composite.

```php
$config = [];
$config['table'] = 't_user.id <= t_mail.id, t_mail.id <= t_addr.id';
$config['field'] = '*, t_user.id as user_id';
$config['limit'] = 1;
$config['where'][] = 'id not null';
$record = $DB->Select($config);
```
