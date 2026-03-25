<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Table;
class JoinCondition
{
   
    public function __construct( public string $table1 ,public string $table2, public string $column1 ,public string $column2 )  {}
    public function parseJoinCondition()
    {
        return " ON {$this->table1}.{$this->column1} = {$this->table2}.{$this->column2} "; 
    }
    
}
