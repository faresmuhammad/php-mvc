<?php

namespace Core\Database;

abstract class Model
{
    protected string $tableName;

    protected string $primaryKey = 'id';

    protected string $keyType = 'int';

    public function loadAttributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this,$key)){
                $this->{$key} = $value;
            }
        }
    }
}