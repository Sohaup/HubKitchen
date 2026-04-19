<?php
namespace PostApi\shared\app\DB\repositories;

abstract class Reopsitory {
    public function __construct()
    {
        
    }
    public abstract function findOne(string $id);
    public abstract function findAll();
    public abstract function create();
    public abstract function update();
    public abstract function delete();
}