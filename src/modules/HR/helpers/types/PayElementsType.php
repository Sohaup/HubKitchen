<?php
namespace PostApi\modules\HR\helpers\types;

enum PayElementsType : string {
    case BOUNCE = "bounce";
    case PROFIT = "profit";
    case ALLOWONCES = "allowonces";
    case OVERTIME = "overtime";
    case COMMISSIONS = "commissions";
    case SOCIALINSURENCE = "social insurence";
    case INCOMETAX = "income tax";
    case FINES = "fines";
    case ABSANCEDECUTION = "absance decution";
    case LOANREPAYMENT = "loan repayment";
}