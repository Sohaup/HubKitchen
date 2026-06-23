<?php
namespace PostApi\modules\CS\domain\valueObjects\types;

enum RoleType : string {
    case SUPPORT = "support";
    case TECH = "tech";
    case DEVELOPER = "developer";
}