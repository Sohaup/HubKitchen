<?php
namespace PostApi\modules\auth\domain\services\permissions;

use PostApi\modules\auth\domain\Entities\Permission;
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

class GetPermissionItemAction {
    public static function execute(Permission $permission) {
        $propeties = new Propeties();
        $idPropety = new Propety("id" , $permission->getId());
        $propeties->addPropety($idPropety);
        $namePropety = new Propety("name" , $permission->getName()); 
        $propeties->addPropety($namePropety);
        $links = new Links();
        $getCollectionLink = new Link(["collection" ] , Urls::transformRouteUrl("/permissions/"));
        $links->addLink($getCollectionLink);
        $getItemLink = new Link(["item"  , "self"] , Urls::transformRouteUrl("/permissions/{$permission->getId()}"));
        $links->addLink($getItemLink);
        $updateLink = new Link(["update"] , Urls::transformRouteUrl("/permissions/{$permission->getId()}"));
        $links->addLink($updateLink);
        $deleteLink = new Link(["delete"] ,Urls::transformRouteUrl("/permissions/{$permission->getId()}") );
        $links->addLink($deleteLink);
        $actions = new Actions(); 
        $fields = new Fields();
        $nameField = new Field("name" , "text" );
        $fields->addField($nameField);       
        $updateAction = new Action("update permission" , HttpMethodsType::PUT , Urls::transformRouteUrl("/permissions/{$permission->getId()}") , "" , $fields );
        $actions->addAction($updateAction);
        $deleteAction = new Action("delete permission" , HttpMethodsType::DELETE , Urls::transformRouteUrl("/permissions/{$permission->getId()}") , "" , $fields );
        $actions->addAction($updateAction);
        $serin = new SerinJson(class:['permissions' , 'item'] ,propeties:$propeties , enities:null , actions:$actions , links:$links );
        return $serin;
    }
}