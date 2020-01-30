<?php
//ini_set("display_errors", "1");
//error_reporting(E_ALL);

// db
require_once ('classes/db.php');
require_once ('classes/employee.php');

$lang = $_GET['lang'];
if ($lang == 'ar'){
    require_once ('languages/ar.php');
}else{
    require_once ('languages/en.php');
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {

     $message = '';
    // get all form data
        $name = filter_input(INPUT_POST , 'name' , FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST , 'email' , FILTER_SANITIZE_EMAIL);
        $country = filter_input(INPUT_POST , 'country' , FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST , 'city' , FILTER_SANITIZE_STRING);
        $salary = filter_input(INPUT_POST , 'salary' , FILTER_SANITIZE_NUMBER_INT);
        $tax = filter_input(INPUT_POST , 'tax' , FILTER_SANITIZE_NUMBER_INT);
        //print_r($name.'<br>'.$email.'<br>'.$country.'<br>'.$city.'<br>'.$salary.'<br>'.$tax);

//    $employee = new Employee($name,$email,$country,$city,$salary,$tax);
//    $employee = new Employee;
//    $employee->name = $name;
//    $employee->email = $email;
//    $employee->country = $country;
//    $employee->city = $city;
//    $employee->salary = $salary;
//    $employee->tax = $tax;

    // sql insert statement
    $sql_add = "INSERT INTO employee(name,email,country,city,salary,tax,) values (:name ,:email,:country,:city,:salary,:tax)";
    $stmt_add = $connect->prepare($sql_add);

    $stmt_add->execute(array(
        ':name'   => $name,
        ':email'  => $email,
        ':country'  => $country,
        ':city'  => $city,
        ':salary'  => $salary,
        ':tax'  => $tax
    ));

    if ($result = $stmt_add->rowCount() == 1){
        $message ="<span class='text-primary'>the user named $name insert with success</span>";

    }elseif($result = $stmt_add->rowCount() == 0){
        $message_error = '<span class="text-danger">Oops error DB and sql </span>';
    }


}

// get all employee
$sql_get = "SELECT * FROM employee";
$stmt = $connect->prepare($sql_get);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_CLASS , 'Employee');
//print_r($data);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/all.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/flag-icon.css">
    <title>PDO ORM</title>
</head>
<body>
 <div class="container-fluid">
     <!-- start menu -->
     <div class="menu">
         <nav class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
             <a class="navbar-brand" href="#"><?= trans('DASHBOARD'); ?></a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarText">
                 <ul class="navbar-nav mr-auto">
                     <li class="nav-item active">
                         <a class="nav-link" href="index.php"><i class="fa fa-home mr-1 text-success"></i><?= trans('HOME'); ?><span class="sr-only">(current)</span></a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="#"><i class="fas fa-user mr-1 text-primary"></i><?= trans('EMPLOYEE'); ?></a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="#"><i class="fas fa-user mr-1 text-success"></i><?= trans('ADD_EMP'); ?></a>
                     </li>
                 </ul>
                 <span class="navbar-text">
                    <!-- start trans -->

                      <div class="dropdown">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Language <i class="fas fa-language" style="color: gold;"></i>
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item text-center" href="?lang=en">English <span class="flag-icon flag-icon-us ml-3"></span></a>
                            <a class="dropdown-item text-center" href="?lang=ar">Arabic <span class="flag-icon flag-icon-ma ml-4"></span></a>
                          </div>
                          <span class="text-success mx-2"><a href="<?=  $_SERVER['PHP_SELF']; ?>/login.php">Login </a><i class="fa fa-user mx-2"></i></span>
                           <span class="btn text-success mx-2"><a href="<?=  $_SERVER['PHP_SELF']; ?>/register.php">Register </a><i class="fa fa-registered mx-2"></i></span>
                       </div>

                     <!-- end trans -->
                   </span>
             </div>
         </nav>
     </div>
     <!-- end menu -->

     <!-- start content -->
       <div class="content">
           <h1 class="text-primary text-center my-2"><?= trans('MANAGE_EMP'); ?></h1>
           <!-- row 1-->
           <hr style="border: solid gold 2px;">
           <div class="col-md-12">
            <div class="row">

                    <div class="col-md-2">
                    <h3 class="text-success">Add employee</h3>
                     <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

                         <?php if ($result == 1){ ?>
                          <div class="form-group">
                             <?= $message; ?>
                          </div>
                         <?php }elseif($result == 0){ ?>
                             <div class="form-group">
                                 <?= $message_error; ?>
                             </div>
                         <?php }; ?>

                          <div class="form-group">
                              <label for="name">Name:</label>
                              <input type="text" name="name" id="name" class="form-control" placeholder="Writ your name">
                          </div>

                         <div class="form-group">
                             <label for="email">Email:</label>
                             <input type="email" name="email" id="email" class="form-control" placeholder="Writ your email">
                         </div>

                         <div class="form-group">
                             <label for="country">Country:</label>
                             <input type="text" name="country" id="country" class="form-control" placeholder="Writ your country">
                         </div>

                         <div class="form-group">
                             <label for="city">City:</label>
                             <input type="text" name="city" id="city" class="form-control" placeholder="Writ your city">
                         </div>

                         <div class="form-group">
                             <label for="salary">Salary:</label>
                             <input type="text" name="salary" id="salary" class="form-control" placeholder="Writ your salary">
                         </div>

                         <div class="form-group">
                             <label for="tax">Tax:</label>
                             <input type="number" name="tax" id="tax" class="form-control" placeholder="Writ your tax">
                         </div>

                         <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Add Employee">
                         </div>
                     </form>
                    </div>
           <!-- row 1-->

           <!-- row 2-->
                    <div class="col-md-10">
                    <h3 class="text-success">Show employee</h3>
                     <table class="table table-hover table-bordered">
                      <thead>
                         <tr style="background-color: #e3f2fd;">
                             <td>ID</td>
                             <td>Name</td>
                             <td>Email</td>
                             <td>Country</td>
                             <td>City</td>
                             <td>Salary</td>
                             <td>Tax %</td>
                             <td>Created_att</td>
                             <td>the_tax</td>
                             <td>Net Salary</td>
                             <td class="text-white bg-secondary">Actions</td>
                         </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row->id ?></td>
                            <td><?= $row->name ?></td>
                            <td><?= $row->email ?></td>
                            <td><?= $row->country ?></td>
                            <td><?= $row->city ?></td>
                            <td><?= $row->salary ?></td>
                            <td><?= $row->tax ?> %</td>
                            <td><?= $row->created_at ?></td>
                            <td><?= $row->the_tax() ?></td>
                            <td><?= $row->calculateTax() ?> $ US</td>
                            <td>
                                <div class="row offset-2">
                                    <a href="<?= $row->id ?>" class="btn btn-success mr-2">Edit</a>

                                    <a href="<?= $row->id ?>" class="btn btn-danger">Delete</a>
                                </div>
                            </td>
                        </tr>
                       <?php endforeach; ?>
                      </tbody>
                     </table>
                    </div>
           <!-- row 2-->
           </div>
          </div>
       </div>
     <!-- end content -->

 </div>


<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/all.js"></script>
</body>
</html>