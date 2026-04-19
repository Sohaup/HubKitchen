<?php
namespace PostApi\modules\auth\helpers\types;

enum RoleTypes : string {
    case USER = "user";
    case MANAGER = "manager";
    case SECURITY = "security";
    case HR = "HR";
    case CS = "customer service";
    case SALES = "sales";
    case MARKETING = "marketing";
}