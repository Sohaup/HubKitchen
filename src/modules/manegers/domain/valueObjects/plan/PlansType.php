<?php

namespace PostApi\modules\manegers\domain\valueObjects\plan;

enum PlansType: string
{
    case STRAGEIC = "strategic";
    case TACTICAL = "tactical";
    case OPERATIONAL = "operational";
    case CONTINGENCY = "contingency";
}
