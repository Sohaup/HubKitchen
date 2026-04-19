<?php

namespace PostApi\modules\auth\domain\services\Roles;

use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\enities\Entity;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\helpers\fecade\Urls;

class GetRoleCollectionAction
{
    /**
     * @param Role[]
     */
    public static function execute(array $roles)
    {
        $propeties = new Propeties();
        $countPropety = new Propety("count", count($roles));
        $propeties->addPropety($countPropety);
        $entites = new Entities();
        foreach ($roles as $role) {
            $entityPropeties = new Propeties();
            $idPropety = new Propety("id", $role->getId());
            $entityPropeties->addPropety($idPropety);
            $namePropety = new Propety("name", $role->getName());
            $entityPropeties->addPropety($namePropety);
            $entityLinks = new Links();
            $getItemLink = new Link(["item", "self"], Urls::transformRouteUrl("/roles/{$role->getId()}"));
            $entityLinks->addLink($getItemLink);
            $updateLink = new Link(["update"], Urls::transformRouteUrl("/roles/{$role->getId()}"));
            $entityLinks->addLink($updateLink);
            $deleteLink = new Link(["delete"], Urls::transformRouteUrl("/roles/{$role->getId()}"));
            $entityLinks->addLink($deleteLink);
            $permissionEntity = new Entity(class: ['roles'], rel: ['item'], propeties: $entityPropeties, links: $entityLinks);
            $entites->addEntity($permissionEntity);
        }
        $links = new Links();
        $createItemLink = new Link(["create permission"], Urls::transformRouteUrl("/roles/{$role->getId()}"));
        $links->addLink($createItemLink);
        $serin = new SerinJson(class: ['roles', 'collection'], propeties: $propeties, enities: $entites, actions: null, links: $links);
        return $serin;
    }
}
