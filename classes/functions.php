<?php

// get all employee
$sql_get = "SELECT * FROM employee";
$stmt = $connect->prepare($sql_get);
$stmt->execute();
$employees = $stmt->fetchAll();

//echo '<pre style="background: black;color: gold;">';
//  var_dump($employees);
//echo '</pre>';

// add new employee

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    // get all form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $salary = $_POST['salary'];
    $tax = $_POST['tax'];
    echo $_POST;
    exit();
    // sql insert statement
    $sql_add = "INSERT INTO employee(name,email,country,city,salary,tax,) values (?,?,?,?,?,?)";
    $stmt_add = $connect->prepare($sql_add);
    $stmt_add->execute(array(
        'name'   => $name,
        'email'  => $email,
        'country'  => $country,
        'city'  => $city,
        'salary'  => $salary,
        'tax'  => $tax
    ));
    $result = $stmt_add->rowCount();
    echo "the user named $name insert with success and the num of row : ".$result;
}
