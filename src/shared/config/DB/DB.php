<?php

namespace PostApi\shared\config\DB;

interface DB
{
    public function __construct();
    public function getPdo();    
}
