<?php

namespace PostApi\modules\HR\domain\services\saleryComponent;

use Error;
use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;

class UpdateSaleryComponentAction
{
    public static function execute(int $id , array $body)
    {
        $repo = new SaleryComponentRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }       
        if (isset($body['name'])) {
            $entity->setName($body['name']);
        }
        if (isset($body['type'])) {
            $entity->setType($body['type']);
        }
        if (isset($body['calc_type'])) {
            $entity->setCalcType($body['calc_type']);
        }
        $repo->update($entity);
    }
}
