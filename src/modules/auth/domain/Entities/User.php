<?php
namespace PostApi\modules\auth\domain\Entities;

class User {
    private string $id = "";
    private string $name = "";    
    private string $email = "";
    private ?string $password = null;
    private ?string $phone = null; 
    private Role $role;
    private ?string $googleId = null;
    public function __construct()
    {
        $this->role = new Role();        
    }
    public function setId(string $id) {
        $this->id = $id;
    }
    public function getId() {
        return $this->id;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
    public function setEmail(string $email) {
        $this->email = $email;
    }
    public function getEmail() {        
        return $this->email;
    }
    public function setPassword(?string $password) {
       $this->password = $password;
    }
    public function getPassword() {
        return $this->password;
    }
    public function setPhone(?string $phone) {
        $this->phone = $phone;
    }
    public function getPhone() {
        return $this->phone;
    }
    public function setRole(Role $role) {
        $this->role = $role;
    }
    public function getRole() {
        return $this->role;
    }
    public function setGoogleId(?string $googleId) {
        $this->googleId = $googleId;
    }
    public function getGoogleId() {
        return $this->googleId;
    }
}
