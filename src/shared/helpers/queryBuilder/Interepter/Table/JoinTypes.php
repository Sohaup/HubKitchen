<?php 
namespace PostApi\shared\helpers\queryBuilder\Interepter\Table;
enum JoinTypes  : string {
    case LEFT  = "LEFT JOIN";
    case RIGHT  = "RIGHT JOIN";
    case INNER = "INNER JOIN";
}