Unit of SQL
===

## DML

### Insert

### Select

### Update

#### Increment

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

### Delete

### Table join

 This is simple example.
 `table_name.field_name <= table_name.field_name`
 `<=` is LEFT JOIN. If right join is `=>`.

```php
$config = [];
$config['table'] = 't_user.mail_id <= t_mail.id, t_user.sns_id <= t_sns.id';
$config['field'] = '*, t_user.id as user_id';
$config['limit'] = 1;
$config['where'][] = 'id not null';
$record = $DB->Select($config);
```
