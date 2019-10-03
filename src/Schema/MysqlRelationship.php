<?php

namespace Akceli\Schema;

class MysqlRelationship
{
    public $CONSTRAINT_CATALOG;
    public $CONSTRAINT_SCHEMA;
    public $CONSTRAINT_NAME;
    public $TABLE_CATALOG;
    public $TABLE_SCHEMA;
    public $TABLE_NAME;
    public $COLUMN_NAME;
    public $ORDINAL_POSITION;
    public $POSITION_IN_UNIQUE_CONSTRAINT;
    public $REFERENCED_TABLE_SCHEMA;
    public $REFERENCED_TABLE_NAME;
    public $REFERENCED_COLUMN_NAME;
    public $foreign_key;
    public $references;
}
