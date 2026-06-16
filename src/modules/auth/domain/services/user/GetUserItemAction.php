<?php

namespace PostApi\modules\auth\domain\services\user;

use PostApi\modules\auth\domain\Entities\User;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\helpers\fecade\Urls;

class GetUserItemAction
{
    public static function execute(User $user)
    {
        $properties = new Propeties();        
        $idPropety = new Propety("id", $user->getId());
        $namePropety = new Propety("name", $user->getName());
        $emailPropety = new Propety("email", $user->getEmail());
        $passwordPropety = new Propety("password", $user->getPassword());
        $phonePropety = new Propety("phone", $user->getPhone());
        $googleIdPropety = new Propety("google_id" , $user->getGoogleId());
        $rolePropeties = new Propeties();
        $roleIdPropety = new Propety("id" , $user->getRole()->getId());
        $rolePropeties->addPropety($roleIdPropety);
        $roleNamePropety = new Propety("name" , $user->getRole()->getName());
        $rolePropeties->addPropety($roleNamePropety);
        $rolePropety = new Propety("role" , $rolePropeties->propeties);        
        $propetiesArr = [ $idPropety, $namePropety, $emailPropety, $phonePropety, $passwordPropety , $googleIdPropety , $rolePropety];
        foreach ($propetiesArr as $propety) {
            $properties->addPropety($propety);
        }        
        $links = new Links();
        $displayLink = new Link(["display"], Urls::transformRouteUrl("/users/{$user->getId()}"));
        $deleteLink = new Link(["delete"], Urls::transformRouteUrl("/users/{$user->getId()}"));
        $updateLink = new Link(["update"], Urls::transformRouteUrl("/users/{$user->getId()}"));
        $collectionLink = new Link(["coolection"], Urls::transformRouteUrl("/users/"));
        $links->addLink($displayLink);
        $links->addLink($deleteLink);
        $links->addLink($updateLink);
        $links->addLink($collectionLink);
        $serin = new SerinJson(class: ["user", "item"], propeties: $properties, enities: null, actions: null, links: $links);
        return $serin;
    }
}
