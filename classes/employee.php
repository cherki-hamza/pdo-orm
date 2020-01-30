<?php

class Employee{

    public $id;
    public $name;
    public $email;
    public $country;
    public $city;
    public $salary;
    public $tax;

//    public function __construct($name,$email,$country,$city,$salary,$tax)
//    {
//        $this->name = $name;
//        $this->email = $email;
//        $this->country = $country;
//        $this->city = $city;
//        $this->salary = $salary;
//        $this->tax = $tax;
//    }

    public function calculateTax(){
        //return ($this->salary - ($this->salary*$this->tax/100));
        return ($this->salary - ($this->salary*$this->the_tax()));
    }

    public function the_tax(){
        return ($this->tax/100);
    }

}