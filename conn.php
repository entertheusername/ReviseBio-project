<?php
$con = mysqli_connect("localhost", "root", "", "rwddassignment");

//Check connection(optional)
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL:" . mysqli_connect_error();
}
