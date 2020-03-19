<?php
class customUser{
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $patronym;
    public $country;
    public $city;
    public $address;
    public $street;
    public $house;
    public $phone;
    public $username;
    public $password;
    public $notice_sms;
    public $notice_email;
    public $apartment;
    public $intercom;
    public $porch;
    public $floor;

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

    public function setNoticeSms($notice_sms){
        $this->notice_sms = $notice_sms;
        return $this;
    }

    public function setNoticeEmail($notice_email){
        $this->notice_email = $notice_email;
        return $this;
    }

    public function setStreet($street){
        $this->street = $street;
        return $this;
    }

    public function setHouse($house){
        $this->house = $house;
        return $this;
    }

    public function setApartment($apartment){
        $this->apartment = $apartment;
        return $this;
    }

    public function setIntercom($intercom){
        $this->intercom = $intercom;
        return $this;
    }

    public function setPorch($porch){
        $this->porch = $porch;
        return $this;
    }

    public function setFloor($floor){
        $this->floor = $floor;
        return $this;
    }
}
?>