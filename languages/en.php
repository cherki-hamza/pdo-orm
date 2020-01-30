<?php

// Author : cherki hamza
// English Translate Function

function trans($word){

    static $trans = array(
      'HOME'        => 'Home',
      'DASHBOARD'  => 'DASHBOARD',
      'EMPLOYEE'   => 'Employee',
      'ADD_AMP'   => 'ADD Employee',
      'MANAGE_EMP' => 'MANAGE EMPLOYEES'
    );
    return $word;

}