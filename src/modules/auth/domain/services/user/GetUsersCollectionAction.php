<?php

namespace PostApi\modules\auth\domain\services\user;


use PostApi\modules\auth\domain\Entities\User;
use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\enities\Entity;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\helpers\fecade\Urls;

class GetUsersCollectionAction
{
    /**
     * @param User[]
     */
    public static function execute(array $users)
    {
        $propeties = new Propeties();
        $propety1 = new Propety("count", count($users));
        $propeties->addPropety($propety1);
        $entities = new Entities();
        $entitiesPropeties = new Propeties();
        $entitiesLinks = new Links();
        foreach ($users as $user) {
            $userIdProp = new Propety("id", $user->getId());
            $userNameProp = new Propety("name", $user->getName());
            $userEmailProp = new Propety("email", $user->getEmail());
            $userPasswordProp = new Propety("password", $user->getPassword());
            $userPhoneProp = new Propety("phone", $user->getPhone());
            $userGoogleIdProp = new Propety("google_id" , $user->getGoogleId());
            $rolePropeties = new Propeties();
            $roleIdPropety = new Propety("id", $user->getRole()->getId());
            $rolePropeties->addPropety($roleIdPropety);
            $roleNamePropety = new Propety("name", $user->getRole()->getName());
            $rolePropeties->addPropety($roleNamePropety);
            $userRoleProp = new Propety("role", $rolePropeties->propeties);          
            $propetiesArr = [$userIdProp, $userNameProp, $userEmailProp, $userPasswordProp, $userPhoneProp , $userGoogleIdProp , $userRoleProp];
            foreach ($propetiesArr as $propety) {
                $entitiesPropeties->addPropety($propety);
            }
            $entitySelfLink = new Link(["self"], Urls::transformRouteUrl("/users/{$user->getId()}"));
            $entitiesLinks->addLink($entitySelfLink);
            $entity = new Entity(["user"], ["item"], $entitiesPropeties, $entitiesLinks);
            $entities->addEntity($entity);
            $links = new Links();
            $link1 = new Link(["self"], Urls::transformRouteUrl("/users/get"));
            $link2 = new Link(["create"], Urls::transformRouteUrl("/users/create"));
            $links->addLink($link1);
            $links->addLink($link2);
        }
        $serin = new SerinJson(class: ["users", "collection"], propeties: $propeties, enities: $entities, actions: null, links: $links);
        return $serin;
    }
}
