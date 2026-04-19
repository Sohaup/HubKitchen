<?php 
namespace PostApi\modules\auth\helpers\types\permissions\tokens;

enum TokensPermissionsType : string {
    case DISPLAY_TOKENS = "display tokens";
    case CREATE_TOKENS = "create tokens";
    case UPDATE_TOKENS = "update tokens";
    case DELETE_TOKENS = "delete tokens"; 
}