<?php
namespace PostApi\modules\auth\helpers\types\permissions\roles;

enum RolesPermissionsType : string {
    case DISPLAY_ROLES = "display roles";
    case CREATE_ROLES = "create roles";
    case UPDATE_ROLES = "update roles";
    case DELETE_ROLES = "delete roles"; 
}