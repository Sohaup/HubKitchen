<?php
namespace PostApi\modules\auth\domain\services\Roles;


use PostApi\modules\auth\domain\Entities\Role;
use PostApi\shared\app\http\responses\success\serin\actions\Action;
use PostApi\shared\app\http\responses\success\serin\actions\Actions;
use PostApi\shared\app\http\responses\success\serin\actions\fields\Field;
use PostApi\shared\app\http\responses\success\serin\actions\fields\Fields;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\app\http\types\HttpMethodsType;
use PostApi\shared\helpers\fecade\Urls;

class GetRoleItemAction {
    public static function execute(Role $role) {
        $propeties = new Propeties();
        $idPropety = new Propety("id" , $role->getId());
        $propeties->addPropety($idPropety);
        $namePropety = new Propety("name" , $role->getName()); 
        $propeties->addPropety($namePropety);
        $links = new Links();
        $getCollectionLink = new Link(["collection" ] , Urls::transformRouteUrl("/roles/"));
        $links->addLink($getCollectionLink);
        $getItemLink = new Link(["item"  , "self"] , Urls::transformRouteUrl("/roles/{$role->getId()}"));
        $links->addLink($getItemLink);
        $updateLink = new Link(["update"] , Urls::transformRouteUrl("/roles/{$role->getId()}"));
        $links->addLink($updateLink);
        $deleteLink = new Link(["delete"] ,Urls::transformRouteUrl("/roles/{$role->getId()}") );
        $links->addLink($deleteLink);
        $actions = new Actions(); 
        $fields = new Fields();
        $nameField = new Field("name" , "text" );
        $fields->addField($nameField);       
        $updateAction = new Action("update role" , HttpMethodsType::PUT , Urls::transformRouteUrl("/roles/{$role->getId()}") , "" , $fields );
        $actions->addAction($updateAction);
        $deleteAction = new Action("delete role" , HttpMethodsType::DELETE , Urls::transformRouteUrl("/roles/{$role->getId()}") , "" , $fields );
        $actions->addAction($updateAction);
        $serin = new SerinJson(class:['roles' , 'item'] ,propeties:$propeties , enities:null , actions:$actions , links:$links );
        return $serin;
    }
}