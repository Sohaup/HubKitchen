<?php
namespace PostApi\modules\HR\helpers\types;

enum MartialStatusType : string {
    case MARRIED = "married";
    case SINGLE = "single";
    case DEVORCED = "devorced";
    case WIDOWED = "widowed";
}