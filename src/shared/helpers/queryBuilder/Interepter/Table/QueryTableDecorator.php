<?php
namespace PostApi\shared\helpers\queryBuilder\Interepter\Table;

abstract class QueryTableDecorator implements QueryTableInterface {   
    protected string $query = "";   
    public function __construct(protected QueryTableInterface $queryTable)
    {
        
    } 
    public function setQuery(string $table)
    {
        $this->queryTable->setQuery($table);      
    }    
    public function getQuery() {
       return $this->queryTable->getQuery()." ".$this->query;
    }
   
}
