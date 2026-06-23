<?php
namespace PostApi\modules\CS\domain\entityListeners;

use Override;
use PostApi\modules\CS\domain\services\customerLog\CreateCustomerLogAction;
use PostApi\modules\CS\domain\valueObjects\types\CustomerLogType;
use PostApi\modules\CS\helpers\adapters\mailer\Mailer;
use SplObserver;
use SplSubject;

class CreateCustomerLogListener implements SplObserver {
    #[Override]
    public function update(SplSubject $subject): void
    {
        if ($subject instanceof CreateCustomerLogAction &&  $subject->getCustomerLogEvent() == "created" ) {
            $customerName = $subject->getCustomerLog()->getCustomer()->getUser()->getName();   
            $customerEmail = $subject->getCustomerLog()->getCustomer()->getUser()->getEmail();
            $logType = $subject->getCustomerLog()->getLogType();
            if ($logType == CustomerLogType::LEAVEONBUYING->value || $logType == CustomerLogType::LEAVEONSHOPPING->value) {
                $emailContent = file_get_contents(__DIR__ . "/../../helpers/templates/emailTemplate.html");
                $mailer = new Mailer();
                $mailer->authorisaze($customerEmail , $customerName);
                $mailer->buildMail("welcome $customerName" , $emailContent , $emailContent);
                $mailer->send();
            }
        }
    }
} 