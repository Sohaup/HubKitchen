<?php

namespace PostApi\modules\HR\domain\services\addresse;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\modules\HR\domain\entities\Addresse;

class CreateAddresseAction
{
    public static function execute(array $params)
    {        
        $repo = new AddreseRepository();
        $entity = new Addresse();
        foreach ($params as $key => $value) {
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (method_exists($entity, $setter)) {
                $entity->{$setter}($value);
            }
        }
        $repo->create($entity);
        return $entity;
    }
}
