<?php

namespace Akceli\Schema;

class SchemaFactory
{
    /**
     * @param string $table_name
     * @return SchemaInterface
     */
    public static function resolve(string $table_name): SchemaInterface
    {
        return new MysqlSchema($table_name);
    }
}
