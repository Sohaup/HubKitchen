<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Table;

class QueryTableWithJoin extends QueryTableDecorator {
    private string $table = "";     
      
     public function addTable(string $table) {
        $this->table = $table;        
    }
    public function addJoin(JoinTypes $joinType , string $joinTable , string $joinCondition)  {
        $this->query .= " {$joinType->value} {$joinTable} ";
        $this->query .= $joinCondition;
    }
}

