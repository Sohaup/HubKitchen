<?php
namespace PostApi\modules\auth\helpers\types\permissions\permissions;

enum PermissionsType : string {
    case DISPLAY_PERMISSIONS = "display permissions";
    case CREATE_PERMISSIONS = "create permissions";
    case UPDATE_PERMISSIONS = "update permissions";
    case DELETE_PERMISSIONS = "delete permissions";
}