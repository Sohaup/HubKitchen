<?php

namespace PostApi\modules\HR\domain\services\addresse;

use PostApi\modules\HR\app\DB\repositories\AddreseRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateAddresseAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new AddreseRepository();
        $entity = $repo->findOne($id);
        if ($entity) {
            foreach ($params as $key => $value) {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
                if (method_exists($entity, $setter)) {
                    $entity->{$setter}($value);
                }
            }
            $repo->update($entity);
        }
    }
}
