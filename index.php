<?php
session_start();

// db
require_once ('classes/db.php');
require_once ('classes/employee.php');
// get language
$lang = $_GET['lang'];
if ($lang == 'ar'){
    require_once ('languages/ar.php');
}else{
    require_once ('languages/en.php');
}


// insert new employee or update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get all form data
        $name = filter_input(INPUT_POST , 'name' , FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST , 'email' , FILTER_SANITIZE_EMAIL);
        $country = filter_input(INPUT_POST , 'country' , FILTER_SANITIZE_STRING);
        $city = filter_input(INPUT_POST , 'city' , FILTER_SANITIZE_STRING);
        $salary = filter_input(INPUT_POST , 'salary' , FILTER_SANITIZE_NUMBER_INT);
        $tax = filter_input(INPUT_POST , 'tax' , FILTER_SANITIZE_NUMBER_INT);

            $params = array(
                ':name'   => $name,
                ':email'  => $email,
                ':country'  => $country,
                ':city'  => $city,
                ':salary'  => $salary,
                ':tax'  => $tax
            );
     //sql insert statement or update statement
    if (isset($_GET['action'])  && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $sql = "UPDATE employee SET name = :name , email = :email ,country = :country , city = :city , salary = :salary , tax = :tax WHERE id = :id";
        $params[':id'] = $id;
    }else{
        $sql = "INSERT INTO employee(name,email,country,city,salary,tax) values (:name ,:email,:country,:city,:salary,:tax)";
    }
    $stm = $connect->prepare($sql);
    $result = $stm->execute($params);

    if ( $result === true){
        $_SESSION['message'] ="<span class='text-primary'>the $name saved with success</span>";
        header('location: index.php');
        session_write_close();
        exit();
    }else{
        $error = true;
        $_SESSION['message'] = '<span class="text-danger">Oops error DB and sql </span>';
    }

}
// end post request insert and update *****************************************************************//



// get employee by id  ******************************************************************************//
if (isset($_GET['action'])  && $_GET['action'] === 'edit' && isset($_GET['id']) ){
    $id = filter_input(INPUT_GET , id , FILTER_SANITIZE_NUMBER_INT);
    if ($id > 0){
        $sql_emp_by_id = "SELECT * FROM employee WHERE id = :id";
        $stmt = $connect->prepare($sql_emp_by_id);
        $found_emp = $stmt->execute(array(
            ':id' => $id
        ));
        if ($found_emp === true){
            $emp_by_id = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,'Employee' , array('name','email','country','city','salary','tax'));
            $emp_by_id = array_shift($emp_by_id);
        }
    }
}
//***************************************************************************************************************//

//**  delete  ****************************************************************************************//
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])){
    $id = filter_input(INPUT_GET , 'id' , FILTER_SANITIZE_NUMBER_INT);
    // delete employee
    $sql = "DELETE  FROM employee WHERE id=:id";
    $stm = $connect->prepare($sql);
    $result = $stm->execute(array('id' => $id));
    if ($result === true){
        $_SESSION['message'] = '<span class="text-danger">Employee deleted with success</span>';
        header('location: index.php');
        session_write_close();
        exit();
    }else{
        $error = true;
        $_SESSION['message'] = '<span class="text-danger">Oops error DB or sql the delete not executed </span>';
    }
}
//*************************************************************************************************************//

// get all employee ***********************************************************************************//
$sql_get = "SELECT * FROM employee";
$stmt = $connect->prepare($sql_get);
$stmt->execute();
$params = array('name','email','country','city','salary','tax');
$data = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Employee' , $params);
//print_r($data);
//****************************************************************************************************//

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
                     <form action="" method="POST">

                         <?php if (isset($_SESSION['message'])){ ?>
                          <div class="form-group">
                             <?= $_SESSION['message']; ?>
                          </div>
                         <?php }
                         $_SESSION['message'] = '';
                         ?>

                          <div class="form-group">
                              <label for="name">Name:</label>
                              <input type="text" name="name" id="name" class="form-control text-success" value="<?= $emp_by_id->name ?>" placeholder="Writ your name">
                              <input type="hidden" name="id" id="id" value="<?= $emp_by_id->id ?>">
                          </div>

                         <div class="form-group">
                             <label for="email">Email:</label>
                             <input type="email" name="email" id="email" class="form-control text-success" value="<?= $emp_by_id->email ?>" placeholder="Writ your email">
                         </div>

                         <div class="form-group">
                             <label for="country">Country:</label>
                             <input type="text" name="country" id="country" class="form-control text-success" value="<?= $emp_by_id->country ?>" placeholder="Writ your country">
                         </div>

                         <div class="form-group">
                             <label for="city">City:</label>
                             <input type="text" name="city" id="city" class="form-control text-success" value="<?= $emp_by_id->city ?>" placeholder="Writ your city">
                         </div>

                         <div class="form-group">
                             <label for="salary">Salary:</label>
                             <input type="text" name="salary" id="salary" class="form-control text-success" value="<?= $emp_by_id->salary ?>" placeholder="Writ your salary">
                         </div>

                         <div class="form-group">
                             <label for="tax">Tax:</label>
                             <input type="number" name="tax" id="tax" class="form-control text-success" value="<?= $emp_by_id->tax ?>" placeholder="Writ your tax">
                         </div>
                         <div class="form-group offset-2">
                             <input type="submit" class="btn btn-success" value="Update Employee">
                         </div>


<!--                         <div class="form-group offset-2">-->
<!--                             --><?php //if ($_GET['action'] == 'edit'){
//                                 echo '<input type="submit" class="btn btn-success" value="Update Employee">';
//                             }else{
//                                 echo '<input type="submit" class="btn btn-primary" value="Add Employee">';
//                             }  ?>
<!--                         </div>-->


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
                                    <a href="?action=edit&id=<?= $row->id; ?>" class="text-success mr-2">Edit<i class="fas fa-edit ml-1"></i></a>

                                    <a href="?action=delete&id=<?= $row->id; ?>" onclick="if (!confirm('Are you sur to delete this employee')) return false;" class="text-danger">Delete<i class="fas fa-trash ml-1"></i></a>
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