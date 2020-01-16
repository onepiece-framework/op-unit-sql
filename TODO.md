TODO
===

## Where is support old format

```php
$where['column_name'] = $value;
```

```php
DML\Common::Where($where){
  if( isset($where[0]) ){
    self::_WhereNew($where);
  }else{
    self::_WhereOld($where);
  };
}

DML\Common::_WhereNew($wheres){
  foreach($wheres){
    ...
  };
  self::_WhereCommon($wheres);
}

DML\Common::_WhereOld($wheres){
  foreach($wheres as $field => $value){
    $where['field'] = $field;
    $where['value'] = $value;
    $where['evalu'] = '=';
  };
  self::_WhereCommon($where);
}

DML\Common::_WhereCommon($wheres){
  foreach($wheres as $where){
    $field = $where['field'];
    $value = $where['value'];
    $evalu = $where['evalu'];
  };
}
```
