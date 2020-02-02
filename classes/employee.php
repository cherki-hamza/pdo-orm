<?php

class Employee{

    private  $id;
    private $name;
    private $email;
    private $country;
    private $city;
    private $salary;
    private $tax;

    public function __construct($name,$email,$country,$city,$salary,$tax)
    {
        $this->name = $name;
        $this->email = $email;
        $this->country = $country;
        $this->city = $city;
        $this->salary = $salary;
        $this->tax = $tax;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function calculateTax(){
        //return ($this->salary - ($this->salary*$this->tax/100));
        return ($this->salary - ($this->salary*$this->the_tax()));
    }

    public function the_tax(){
        return ($this->tax/100);
    }

}