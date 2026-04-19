<?php
namespace PostApi\modules\auth\domain\services\tokens;


use PostApi\modules\auth\app\DB\repositories\TokenRepository;
use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\enities\Entity;
use PostApi\shared\app\http\responses\success\serin\links\Link;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;
use PostApi\shared\app\http\responses\success\serin\SerinJson;
use PostApi\shared\helpers\fecade\Urls;

class GetTokensCollectionAction {
    public static function execute() {
        $tokensRepository = new TokenRepository();
        $tokens = $tokensRepository->findAll();
        $propeties = new Propeties();
        $countPropety = new Propety("count" , count($tokens));
        $propeties->addPropety($countPropety);
        $entities = new Entities();
        foreach ($tokens as $token) {
            $tokenPropeties = new Propeties();
            $idPropety = new Propety("id" , $token->getId());
            $tokenPropeties->addPropety($idPropety);
            $tokenPropety = new Propety("token" , $token->getToken());
            $tokenPropeties->addPropety($tokenPropety);
            $userIdPropety = new Propety("user_id" , $token->getUser()->getId());
            $tokenPropeties->addPropety($userIdPropety);
            $createdAtPropety = new Propety("created_at" , $token->getCreatedAt());
            $tokenPropeties->addPropety($createdAtPropety);
            $expiresAtPropety = new Propety("expires_at" , $token->getExpiresAt());
            $tokenPropeties->addPropety($expiresAtPropety);
            $isRevokedPropety = new Propety("is_revoked" , $token->getRevoked());
            $tokenPropeties->addPropety($isRevokedPropety);
            $tokenLinks = new Links();
            $displayLink = new Link(rel:['self' , 'get'] , href:Urls::transformRouteUrl("/tokens/{$token->getId()}"));
            $tokenLinks->addLink($displayLink);
            $createLink = new Link(rel:['token' , 'create'] , href:Urls::transformRouteUrl("/tokens/create"));
            $tokenLinks->addLink($createLink);
            $updateLink = new Link(rel:['token' , 'update'] , href:Urls::transformRouteUrl("/tokens/{$token->getId()}"));
            $tokenLinks->addLink($updateLink);
            $deleteLink = new Link(rel:['token' , 'delete'] , href:Urls::transformRouteUrl("/tokens/{$token->getId()}"));
            $tokenLinks->addLink($deleteLink);
            $tokenEntity = new Entity(class:['tokens'] , rel:['item'] , propeties:$tokenPropeties , links:$tokenLinks);
            $entities->addEntity($tokenEntity);
        }
        $links  = new Links();
        $createLink = new Link(rel:['tokens' , 'create'] ,href:Urls::transformRouteUrl("/tokens/create") );
        $links->addLink($createLink);
        $serin = new SerinJson(class:['tokens' , 'collections'] ,propeties:$propeties , enities:$entities , actions:null , links:$links );
        return $serin;
    }
}