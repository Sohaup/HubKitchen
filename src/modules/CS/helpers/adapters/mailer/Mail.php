<?php
namespace PostApi\modules\CS\helpers\adapters\mailer;

interface Mail {
    public function authorisaze( string $to , string $customerName);
    public function buildMail(string $subject  , string $body , string $altBody);
    public function send();
}