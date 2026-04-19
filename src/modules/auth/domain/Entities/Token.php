<?php
namespace PostApi\modules\auth\domain\Entities;

use DateInterval;
use DateTime;
use DateTimeZone;

class Token {
    private ?int $id = 0;
    private string $token = "";
    private User $user;
    private DateTime $expires_at;
    private DateTime $created_at;
    private bool $is_revoked;
    public function __construct(?int $id = null , ?User $user = null , ?string $token = null , ?string $created_at = null , ?string $expires_at = null  , bool $is_revoked = false)
    {
        $id ? $this->id = $id : $this->id = 0;
        $user ? $this->user = $user : $this->user = new User();
        $now =  new DateTime();    
        $token ? $this->token = $token : $this->token = "";    
        $now->setTimezone(new DateTimeZone("Africa/Cairo"));
        $created_at ?  $this->created_at = new DateTime($created_at): $this->created_at = $now;
        $coledNow = clone $now; 
        $plusOneDay =  $coledNow->modify("+1 day");
        $expires_at ? $this->expires_at = new DateTime($expires_at) :$this->expires_at = $plusOneDay;    
        $is_revoked ? $this->is_revoked = $is_revoked : $this->is_revoked = false; 
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id; 
    }
    public function setToken(string $token) {
        $this->token = $token;
    }
    public function getToken() {
        return $this->token;
    }
    public function setUser(User $user) {
        $this->user = $user;
    }
    public function getUser() {
        return $this->user;
    }
    public function setCreatedAt(string $created_at) {
        $this->created_at = new DateTime($created_at);
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function addExpiresAt(int $hours) {
        $this->expires_at = $this->expires_at->modify("+{$hours} hours");        
    }
    public function setExpiresAt(string $timestamp) {
        $date = new DateTime($timestamp);
        $date->setTimezone(new DateTimeZone('Africa/cairo'));
    }
    public function getExpiresAt() {
        return $this->expires_at;
    }
    public function setRevoked(bool $is_revoked) {
        $this->is_revoked = $is_revoked;
    }
    public function isRevoked() {
        $this->is_revoked ? "revoked" : "not revoked";
    }
    public function getRevoked() {
        return $this->is_revoked;
    }    
}