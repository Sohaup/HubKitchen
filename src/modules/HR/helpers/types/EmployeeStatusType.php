<?php

namespace PostApi\modules\HR\helpers\types;

enum EmployeeStatusType: string
{
    case PENDING = "pending";
    case ACTIVE = "active";
    case PROBATION = "probation";
    case TERMINATED = "terminated";
    case RETIRED = "retired";
}
