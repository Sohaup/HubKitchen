<?php

namespace PostApi\shared\app\controllers\api;

use PostApi\shared\app\http\requests\Request;

interface ApiControllerContract
{
    public function index(Request $request);
    public function get(string $id);
    public function create(Request $request);
    public function update(Request $request, string $id);
    public function delete(string $id);
}
