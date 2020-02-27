<?php
class customUser{
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $country;
    public $city;
    public $address;
    public $patronym;
    public $phone;
    public $username;
    public $password;

    public function setUsername($username){
        $this->username = $username;
        return $this;
    }

    public function setPassword($password){
        $this->password = $password;
        return $this;
    }
  
    public function setUserID($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    public function setEmail($email){
        $this->email = $email;
        return $this;
    }

    public function setFirstName($first_name){
        $this->first_name = $first_name;
        return $this;
    }

    public function setLastName($last_name){
        $this->last_name = $last_name;
        return $this;
    }

    public function setCountry($country){
        $this->country = $country;
        return $this;
    }

    public function setCity($city){
        $this->city = $city;
        return $this;
    }

    public function setAddress($address){
        $this->address = $address;
        return $this;
    }

    public function setPatronym($patronym){
        $this->patronym = $patronym;
        return $this;
    }

    public function setPassport($passport){
        $this->passport = $passport;
        return $this;
    }

    public function setPhone($phone){
        $this->phone = $phone;
        return $this;
    }

}
?>