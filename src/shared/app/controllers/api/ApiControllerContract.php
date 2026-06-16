<?php 
namespace PostApi\shared\app\controllers\api;

interface ApiControllerContract {
    public function index();
    public function get(string $id);
    public function create();
    public function update(string $id);
    public function delete(string $id);
}