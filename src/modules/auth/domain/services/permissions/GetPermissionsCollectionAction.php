<?php

namespace PostApi\modules\auth\domain\services\permissions;


use PostApi\modules\auth\domain\Entities\Permission;
use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\enities\Entity;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\helpers\fecade\Urls;

class GetPermissionsCollectionAction
{
    /**
     * @param Permission[]
     */
    public static function execute(array $permissions)
    {
        $propeties = new Propeties();
        $countPropety = new Propety("count", count($permissions));
        $propeties->addPropety($countPropety);
        $entites = new Entities();
        foreach ($permissions as $permission) {
            $entityPropeties = new Propeties();
            $idPropety = new Propety("id", $permission->getId());
            $entityPropeties->addPropety($idPropety);
            $namePropety = new Propety("name", $permission->getName());
            $entityPropeties->addPropety($namePropety);
            $entityLinks = new Links();
            $getItemLink = new Link(["item", "self"], Urls::transformRouteUrl("/permissions/{$permission->getId()}"));
            $entityLinks->addLink($getItemLink);
            $updateLink = new Link(["update"], Urls::transformRouteUrl("/permissions/{$permission->getId()}"));
            $entityLinks->addLink($updateLink);
            $deleteLink = new Link(["delete"], Urls::transformRouteUrl("/permissions/{$permission->getId()}"));
            $entityLinks->addLink($deleteLink);
            $permissionEntity = new Entity(class: ['permissions'], rel: ['item'], propeties: $entityPropeties, links: $entityLinks);
            $entites->addEntity($permissionEntity);
        }
        $links = new Links();
        $createItemLink = new Link(["create permission"], Urls::transformRouteUrl("/permissions/{$permission->getId()}"));
        $links->addLink($createItemLink);
        $serin = new SerinJson(class: ['permissions', 'collection'], propeties: $propeties, enities: $entites, actions: null, links: $links);
        return $serin;
    }
}
