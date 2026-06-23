<?php
namespace PostApi\modules\CS\domain\valueObjects\types;

enum CustomerLogType : string {
    case ENTERED = "entered";
    case BOUGHT = "bought";
    case LEAVEONSHOPPING = "leaved on shopping";
    case LEAVEONBUYING = "leaved on buying";
}