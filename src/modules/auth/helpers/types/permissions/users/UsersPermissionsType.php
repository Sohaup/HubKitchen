<?php 
namespace PostApi\modules\auth\helpers\types\permissions\users;

enum UsersPermissionsType : string {
    case DISPLAY_USERS = "display users";
    case CREATE_USERS = "create users";
    case DISPLAY_SELF = "display self";
    case UPDATE_USERS = "update users";
    case DELETE_USERS = "delete users";   
}