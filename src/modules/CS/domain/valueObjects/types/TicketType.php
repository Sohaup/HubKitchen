<?php
namespace PostApi\modules\CS\domain\valueObjects\types;

enum TicketType : string {
    case INQUIRYTICKETS = "Inquiry Tickets";
    case TECHNICALSUPPORT = "Technical Support";
    case BILLINGANDFINANCIAL = "Billing & Financial";
    case RMA = "RMA";
    case SUGGETION = "suggetion";
}