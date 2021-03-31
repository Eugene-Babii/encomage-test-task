<?php
include_once('functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Trainee Web Developer Test Task">
    <meta name="author" content="Eugene Babii">
    <title>Encomage test task</title>

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/f10727c711.js" crossorigin="anonymous"></script>

    <!-- Bootstrap 4.5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>

<body class="bg-dark">

    <div class="container bg-secondary mt-5">

        <!-- Header -->
        <div class="d-flex justify-content-between py-3">
            <h1 class="text-white font-weight-bold d-flex align-items-center">User list</h1>
            <form class="form-inline" id="form_search" method="get">
                <input class="form-control mr-sm-2" name='keywords' id='keywords' type=" search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search pr-2"></i>Search</button>
            </form>
            <button type="button" class="btn btn-primary my-2 my-sm-0" data-toggle="modal" data-target="#createEditUsersForm" data-whatever="addUser" style="border: 1px solid white;"><i class="fas fa-user-plus pr-2"></i>Add user</button>
        </div>

        <!-- Users grid -->
        <table class="table text-white">
            <thead>
                <tr class="bg-dark">
                    <th scope="col">ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Create Date</th>
                    <th scope="col">Update Date</th>
                    <th scope="col">Actions</th>
                </tr>

                <!-- Row with filters -->
                <form id="form_filters" name="form_filters" action="" method="">
                    <tr class="mb-1 bg-dark">
                        <td><input type="text" id="f_id" name="f_id" style="width:40px;" oninput="filter();"></td>
                        <td><input type="text" id="f_first_name" name="f_first_name" class="w-75" oninput="filter();"></td>
                        <td><input type=" text" id="f_last_name" name="f_last_name" class="w-75" oninput="filter();"></td>
                        <td><input type="text" id="f_email" name="f_email" class="w-75" oninput="filter();"></td>
                        <td style="border-top: 0px;">
                            <div class="d-flex justify-content-end ">
                                <label for="from_create_date" class="pr-1">From:</label>
                                <input type="date" id="from_create_date" name="from_create_date">
                            </div>
                            <div class="d-flex justify-content-end mb-1">
                                <label for="to_create_date" class="pr-1">To:</label>
                                <input type="date" id="to_create_date" name="to_create_date">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <label for="from_update_date" class="pr-1">From:</label>
                                <input type="date" id="from_update_date" name="from_update_date">
                            </div>
                            <div class="d-flex justify-content-end">
                                <label for=" to_update_date" class="pr-1">To:</label>
                                <input type="date" id="to_update_date" name="to_update_date">
                            </div>
                        </td>
                        <td class="d-flex flex-column justify-content-center" style="border-top: 0px;">
                            <button class="btn btn-secondary mb-1 text-nowrap" style="border: 1px solid white;" type="reset" name="Reset">Reset filters</button>
                        </td>
                    </tr>
                </form>
            </thead>
            <!-- Rows with users data -->
            <tbody id='users_grid'></tbody>
            <div id='users_grid2' class="bg-light"></div>

        </table>
        <!-- End .container -->
    </div>

    <!-- Modal window (create and edit users form)-->
    <div class="modal fade" id="createEditUsersForm" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="createEditUsersFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEditUsersFormLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="false">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="" id="form_users_data" name="form_users_data">
                        <div class="form-group">
                            <label for="first_name" class="col-form-label">First name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-form-label">Last name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="">
                        </div>
                        <div class="modal-footer">
                            <input value="" name='id' id="id" type='hidden'>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-outline-primary" id="btn_submit" name="btn_submit"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- JQuery & Bootstrap scripts-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Custom scripts -->
    <script src="js/app.js"></script>

</body>

</html>