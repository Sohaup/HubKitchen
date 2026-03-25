<?php 
namespace PostApi\shared\app\controllers\api;

interface ApiControllerContract {
    public function get();
    public function create();
    public function update();
    public function delete();
}