<?php
namespace PostApi\modules\HR\helpers\types;

enum AppraisalStatusType : string {
    case DRAFTED = "drafted";
    case ACTIVE = "active";
    case COMPLETED = "completed";
}