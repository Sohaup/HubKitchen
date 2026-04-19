<?php

namespace PostApi\modules\auth\domain\services\tokens;

use PostApi\modules\auth\app\DB\repositories\TokenRepository;
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

class GetTokenItemAction
{
    public static function execute(int $tokenId)
    {
        $tokenRepository = new TokenRepository();
       
        $token = $tokenRepository->findOne($tokenId);
        // echo $token->getToken();
        // echo "\n";
        $propeties = new Propeties();
        $idPropety = new Propety("id", $token->getId());
        $propeties->addPropety($idPropety);
        $tokenPropety = new Propety("token", $token->getToken());
        $propeties->addPropety($tokenPropety);
        $userIdPropety = new Propety("user_id", $token->getUser()->getId());
        $propeties->addPropety($userIdPropety);
        $createdAtPropety = new Propety("created_at", $token->getCreatedAt());
        $propeties->addPropety($createdAtPropety);
        $expiresAtPropety = new Propety("expires_at", $token->getExpiresAt());
        $propeties->addPropety($expiresAtPropety);
        $isRevokedPropety = new Propety("is_revoked", $token->getRevoked());
        $propeties->addPropety($isRevokedPropety);
        $links  = new Links();
        $displayLink = new Link(rel: ['self', 'get'], href: Urls::transformRouteUrl("/tokens/{$token->getId()}"));
        $links->addLink($displayLink);
        $createLink = new Link(rel: ['token', 'create'], href: Urls::transformRouteUrl("/tokens/create"));
        $links->addLink($createLink);
        $updateLink = new Link(rel: ['token', 'update'], href: Urls::transformRouteUrl("/tokens/{$token->getId()}"));
        $links->addLink($updateLink);
        $deleteLink = new Link(rel: ['token', 'delete'], href: Urls::transformRouteUrl("/tokens/{$token->getId()}"));
        $links->addLink($deleteLink);
        $actions = new Actions();
        $createActionFields = new Fields();
        $userIdField = new Field("user_id" , "text" );
        $createActionFields->addField($userIdField);
        $createTokenAction = new Action("create token" , HttpMethodsType::POST , Urls::transformRouteUrl("/tokens/create"), "un safe" ,$createActionFields );
        $actions->addAction($createTokenAction);
        $updateActionFields = new Fields();
        $isRevokedField = new Field("is_revoked" , "boolean");
        $updateActionFields->addField($isRevokedField);
        $updateTokenAction = new Action("update token" , HttpMethodsType::PUT , Urls::transformRouteUrl("/tokens/{$token->getId()}") , "idempotent" , $updateActionFields);
        $actions->addAction($updateTokenAction);
        $links = new Links();
        $getCollectionLink = new Link(rel:['tokens' , 'collection'] ,href:Urls::transformRouteUrl("/tokens/"));
        $links->addLink($getCollectionLink);
        $serin = new SerinJson(class:['tokens' , 'item'] , propeties:$propeties , enities:null , actions:$actions , links:$links);  
        return $serin;     
    }
}
