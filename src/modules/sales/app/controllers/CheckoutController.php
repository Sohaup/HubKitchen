<?php
namespace PostApi\modules\sales\app\controllers;

use Override;
use PostApi\shared\app\controllers\api\ApiControllerContract;

class CheckoutController implements ApiControllerContract {
    #[Override]
    public function index()
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function get(string $id)
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function create()
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function update(string $id)
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function delete(string $id)
    {
        throw new \Exception('Not implemented');
    }
}