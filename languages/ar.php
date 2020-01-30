<?php

// Author : cherki hamza
// Arabic Translate Function

function trans($word)
{

    static $trans = array(
         'HOME'        => 'الرئيسية',
         'DASHBOARD'   => 'لوحة القيادة',
         'EMPLOYEE'    => 'الموظفين',
         'ADD_EMP'    => 'اضافة موظف',
         'MANAGE_EMP'=> 'إدارة الموظفين'
    );
    return $trans[$word];

}
