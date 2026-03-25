<?php
namespace PostApi\shared\app\http\responses\success\serin;
use PostApi\shared\app\controllers\api\Propeties;
use PostApi\shared\app\http\responses\success\serin\actions\Action;
use PostApi\shared\app\http\responses\success\serin\actions\Actions;
use PostApi\shared\app\http\responses\success\serin\enities\Entities;
use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties as SerinPropeties;

class SerinJson {
    public array $propeties = [];
    public array $enities = [];
    public array $actions = [];
    public array $links = [];
    public function __construct(public array $class , SerinPropeties $propeties ,  Entities $enities ,  Actions $actions ,  Links $links )
    {
        $this->propeties = $propeties->propeties;
        $this->enities = $enities->entities;
        $this->actions = $actions->actions;
        $this->links = $links->links;
    }
    
}
