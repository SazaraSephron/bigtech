<?php
session_start();

function sync_database()
{
    $additional_ex_bookings = array();
    $additional_in_bookings = array();
    $additional_ex_users = array();
    $additional_in_users = array();

    $ls_conn = $_SESSION['database'];
    $ls_ex_conn = $_SESSION['database_external'];

    $website_bookings = getBookings($ls_conn, "booking");
    $app_bookings = getBookings($ls_ex_conn, "booking");

    $website_users = getUsers($ls_conn, "wp_users");
    $app_users = getUsers($ls_ex_conn, "user");

    $additional_ex_bookings = getAdditionalExternalBookings($website_bookings, $app_bookings);
    $additional_in_bookings = getAdditionalInternalBookings($website_bookings, $app_bookings);

    $additional_ex_users = getAdditionalExternalUsers($website_users, $app_users);
    $additional_in_users = getAdditionalInternalUsers($website_users, $app_users);

    if (count($additional_ex_bookings) != 0 || count($additional_in_bookings) != 0 || count($additional_ex_users) != 0 || count($additional_in_users) != 0) {
        echo "<br>Synchronising databases<br>";
        manageAdditionalUsers($additional_ex_users, $additional_in_users);
        manageAdditionalBookings($additional_ex_bookings, $additional_in_bookings);
    } else {
        echo "<br>There is nothing to sync<br>";
    }
}


function getBookings($conn, $table_name)
{
  
    $qry = "SELECT booking_id FROM $table_name";
    $result = mysqli_query($conn, $qry) or die(mysqli_error($conn));

    foreach ($result as $row) {

        $bookings[] = $row;
    }

    return $bookings;
}

function getUsers($conn, $table_name)
{
 
    if ($table_name == "wp_users") {
        $qry = "SELECT user_email FROM $table_name";
    } else if ($table_name == "user") {
        $qry = "SELECT email FROM $table_name";
    }
    $result = mysqli_query($conn, $qry) or die(mysqli_error($conn));

    foreach ($result as $row) {
        if ($table_name == "wp_users") {
            $users[] = $row["user_email"];
        } else if ($table_name == "user") {
            $users[] = $row["email"];
        }
    }

    return $users;
}


function getAdditionalExternalUsers($first_table_users, $second_table_users)
{

    $additional = array();
    $additional = array_values(array_diff($second_table_users, $first_table_users));

    return $additional;
}

function getAdditionalInternalUsers($first_table_users, $second_table_users)
{

    $additional = array();
    $additional = array_values(array_diff($first_table_users, $second_table_users));

    return $additional;
}

function getAdditionalExternalBookings($first_table_bookings, $second_table_bookings)
{

    $additional = array();

    $map1 = array_map('serialize', $second_table_bookings);
    $map2 = array_map('serialize', $first_table_bookings);
    $additional = array_values(array_diff($map1, $map2));


    $additional = array_map('unserialize', $additional);

    return $additional;
}

function getAdditionalInternalBookings($first_table_bookings, $second_table_bookings)
{

    $additional = array();

    $map1 = array_map('serialize', $first_table_bookings);
    $map2 = array_map('serialize', $second_table_bookings);

    $additional = array_values(array_diff($map1, $map2));

    $additional = array_map('unserialize', $additional);

    return $additional;
}


function manageAdditionalUsers($additional_ex, $additional_in)
{
    $ls_conn = $_SESSION['database'];
    $ls_ex_conn = $_SESSION['database_external'];

    if (count($additional_ex) != 0) {


        for ($k = 0; $k < count($additional_ex); $k++) {


            // GET BOOKING DATA FROM EXTERNAL TABLE
            $ext_user_data = array();
            $sql_query = "SELECT * FROM user WHERE email = '$additional_ex[$k]'";
            if (($ex_user_data = $ls_ex_conn->query($sql_query)) == true) {
                foreach ($ex_user_data as $row) {
                    $ext_user_data[] = $row;
                }

                $username = strtok($additional_ex[$k], '@');
				$c = 1;
                while ( username_exists( $username ) == true) {
                    $username = strtok($additional_ex[$k], '@') . $c;
                    $c++;
                }
                $password = $ext_user_data[0]["password"];
                $email = $additional_ex[$k];
                $first_name = $ext_user_data[0]["first_name"];
                $last_name = $ext_user_data[0]["last_name"];
                $display_name = $first_name . " " . $last_name;
                $nicename = strtolower($first_name) . "-" . strtolower($last_name);
                $nickname = $first_name . $last_name;
                $role = "subscriber";
                $phone = $ext_user_data[0]["phone_number"];

                $user_id = wp_insert_user(array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'display_name' => $display_name,
                    'role' => $role,
                    'nicename' => $nicename,
                    'nickname' => $nickname,
                    'phone' => $phone
                ));
            } else {
                echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
            }
        }
    }
    if (count($additional_in) != 0) {

        for ($k = 0; $k < count($additional_in); $k++) {


            $sql_query = "SELECT user_pass FROM wp_users WHERE user_email = '$additional_in[$k]'";
            if (($password = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
            } else {
                echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
            }

            $sql_query = "SELECT ID FROM wp_users WHERE user_email = '$additional_in[$k]'";
            if (($ext_user_id = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
            } else {
                echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
            }


            $sql_query = "SELECT meta_value FROM wp_usermeta WHERE user_id = '$ext_user_id' AND meta_key = 'first_name'";
            if (($first_name = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
            } else {
                $first_name = "";
            }

            $sql_query = "SELECT meta_value FROM wp_usermeta WHERE user_id = '$ext_user_id' AND meta_key = 'last_name'";
            if (($last_name = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
            } else {
                $last_name = "";
            }

            $sql_query = "SELECT count(meta_value) FROM wp_usermeta WHERE user_id = '$ext_user_id' AND meta_key = 'phone'";
            $if_phone = $ls_conn->query($sql_query)->fetch_row()[0];

            if ($if_phone != 0) {
                $sql_query = "SELECT meta_value FROM wp_usermeta WHERE user_id = '$ext_user_id' AND meta_key = 'phone'";
                if (($phone = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
                } else {
                    echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
                }
            } else {
                $phone = "";
            }
            $email = $additional_in[$k];

            $sql_insert = "INSERT INTO `user`(`first_name`, `last_name`, `password`, `phone_number`, `email`, `account_type`, `push_token`) VALUES ('$first_name','$last_name','$password','$phone','$email','0','')";
            if (($last_name = $ls_ex_conn->query($sql_insert)) == true) {
            } else {
                echo "Error: " . $sql_insert . "<br>" . $ls_ex_conn->error;
            }
        }
    }
}

function manageAdditionalBookings($additional_ex, $additional_in)
{

    $ls_conn = $_SESSION['database'];
    $ls_ex_conn = $_SESSION['database_external'];

    if (count($additional_ex) != 0) {

        $del_array = array();
        $get_del = "SELECT booking_id FROM new_deletions";
        if (($del = $ls_conn->query($get_del)) == true) {
            foreach ($del as $row) {

                $del_array[] = $row;
            }
        } else {
            echo "Error: " . $get_del . "<br>" . $ls_conn->error;
        }
        $del_array_struct = array();
        for ($l = 0; $l < count($del_array); $l++) {
            $del_array_struct[] = $del_array[$l]["booking_id"];
        }
        // FORMAT ARRAY TO NUMBERS INSTEAD OF COLUMN NAME
        $del_array_sf = array_values($del_array_struct);
        $new_deletions = $del_array_sf;

        $count_add = count($additional_ex);
        for ($i = 0; $i < $count_add; $i++) {
            $ignore = false;
            if (isset($new_deletions[0]) == true) {
                for ($k = 0; $k < count($new_deletions); $k++) {
                    if ($additional_ex[$i]["booking_id"] == $new_deletions[$k]) {

                        // DELETED RECENTLY, DELETE FROM EXTERNAL TABLE

                        $booking_id = $additional_ex[$i]["booking_id"];

                        // DELETE EXTERNAL BOOKING
                        $sql_delete = "DELETE FROM booking WHERE booking_id = '$booking_id'";
                        if (($result = $ls_ex_conn->query($sql_delete)) == true) {
                        } else {
                            echo "Error: " . $sql_delete . "<br>" . $ls_ex_conn->error;
                        }


                        $ignore = true;
                    }
                }
            }

            if ($ignore == false) {
                // INSERT INTO INTERNAL TABLE
                $booking_id = $additional_ex[$i]["booking_id"];

                // GET EXTERNAL USER ID FROM BOOKING
                $sql_query = "SELECT user_id FROM booking WHERE booking_id = '$booking_id'";
                if (($ex_user_id = $ls_ex_conn->query($sql_query)->fetch_row()[0]) == true) {
                } else {
                    echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
                }


                // GET EMAIL FROM EXTERNAL USER ID
                $sql_query = "SELECT email FROM user WHERE user_id = '$ex_user_id'";
                if (($user_email = $ls_ex_conn->query($sql_query)) == true) {
                    $user_email = $user_email->fetch_row()[0];
                } else {
                    echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
                }

                // GET INTERNAL USER ID FROM EMAIL
                $sql_query = "SELECT ID FROM wp_users WHERE user_email = '$user_email'";
                if (($in_user_id = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
                } else {
                    echo "Error: " . $sql_query . "<br>" . $ls_conn->error;
                }

                // GET BOOKING DATA FROM EXTERNAL TABLE
                $sql_query = "SELECT * FROM booking WHERE booking_id = '$booking_id'";
                if (($ex_user_data = $ls_ex_conn->query($sql_query)) == true) {
                    foreach ($ex_user_data as $row) {

                        $ex_user_data_array[] = $row;
                    }
                } else {
                    echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
                }
                // SET USER ID TO INTERNAL USER ID
                $ex_user_data_array[0]["user_id"] = $in_user_id;

                // FORMAT ARRAY TO NUMBERS INSTEAD OF COLUMN NAME
                $arr_formatted = array_values($ex_user_data_array[0]);


                // INSERT BOOKING INTO INTERNAL TABLE
                $sql_insert = "INSERT INTO `booking`(`booking_id`, `user_id`, `date_booked`, `time_booked`, `device_type`, `device_service`, `comment`, `technician_id`, `status`, `model_number`, `date_completed`, `serial_number`, `store_location`, `qr_code`, `cost`, `problem`, `estimated_completion`, `job_request_response`, `pickup_date`, `pickup_time`) VALUES ";
                $sql_insert = $sql_insert . "('$arr_formatted[0]'";
                for ($j = 1; $j < count($arr_formatted); $j++) {
                    $sql_insert = $sql_insert . ",'$arr_formatted[$j]'";
                }
                $sql_insert = $sql_insert . ")";
                if (($result = $ls_conn->query($sql_insert)) == true) {
                } else {
                    echo "Error: " . $sql_insert . "<br>" . $ls_conn->error;
                }
            }
        }

        // EMPTIES NEW DELETIONS ARRAY
        $del_id = "DELETE FROM new_deletions";
        $delete_result = $ls_conn->query($del_id);
    }

    if (count($additional_in) != 0) {

        $cre_array = array();
        $get_cre = "SELECT booking_id FROM new_bookings";
        if (($cre = $ls_conn->query($get_cre)) == true) {
            foreach ($cre as $row) {

                $cre_array[] = $row;
            }
        } else {
            echo "Error: " . $get_cre . "<br>" . $ls_conn->error;
        }
        $cre_array_struct = array();
        for ($l = 0; $l < count($cre_array); $l++) {
            $cre_array_struct[] = $cre_array[$l]["booking_id"];
        }
        // FORMAT ARRAY TO NUMBERS INSTEAD OF COLUMN NAME
        $cre_array_sf = array_values($cre_array_struct);
        $new_bookings = $cre_array_sf;

        for ($i = 0; $i < count($additional_in); $i++) {
            $ignore = true;
            if (isset($new_bookings)) {

                for ($k = 0; $k < count($new_bookings); $k++) {
                    if ($additional_in[$i]["booking_id"] == $new_bookings[$k]) {
                        // RECENTLY CREATED, INSERT INTO EXTERNAL TABLE
                        $booking_id = $additional_in[$i]["booking_id"];
                        // GET INTERNAL USER ID FROM BOOKING
                        $sql_query = "SELECT user_id FROM booking WHERE booking_id = '$booking_id'";
                        if (($in_user_id = $ls_conn->query($sql_query)->fetch_row()[0]) == true) {
                        } else {
                            echo "Error: " . $sql_query . "<br>" . $ls_conn->error;
                        }
                        // GET EMAIL FROM INTERNAL USER ID
                        $sql_query = "SELECT user_email FROM wp_users WHERE ID = '$in_user_id'";
                        if (($user_email = $ls_conn->query($sql_query)) == true) {
                            $user_email = $user_email->fetch_row()[0];
                        } else {
                            echo "Error: " . $sql_query . "<br>" . $ls_conn->error;
                        }
                        // GET EXTERNAL USER ID FROM EMAIL
                        $sql_query = "SELECT user_id FROM user WHERE email = '$user_email'";
                        if (($ex_user_id = $ls_ex_conn->query($sql_query)->fetch_row()[0]) == true) {
                        } else {
                            echo "Error: " . $sql_query . "<br>" . $ls_ex_conn->error;
                        }
                        // GET BOOKING DATA FROM INTERNAL TABLE
                        $sql_query = "SELECT * FROM booking WHERE booking_id = '$booking_id'";
                        if (($in_user_data = $ls_conn->query($sql_query)) == true) {
                            foreach ($in_user_data as $row) {
                                $in_user_data_array[] = $row;
                            }
                        } else {
                            echo "Error: " . $sql_query . "<br>" . $ls_conn->error;
                        }
                        // SET USER ID TO EXTERNAL USER ID
                        $in_user_data_array[0]["user_id"] = $ex_user_id;
                        // FORMAT ARRAY TO NUMBERS INSTEAD OF COLUMN NAME
                        $arr_formatted = array_values($in_user_data_array[0]);
                        // INSERT BOOKING INTO EXTERNAL TABLE
                        $sql_insert = "INSERT INTO `booking`(`booking_id`, `user_id`, `date_booked`, `time_booked`, `device_type`, `device_service`, `comment`, `technician_id`, `status`, `model_number`, `date_completed`, `serial_number`, `store_location`, `qr_code`, `cost`, `problem`, `estimated_completion`, `job_request_response`, `pickup_date`, `pickup_time`) VALUES ";
                        $sql_insert = $sql_insert . "('$arr_formatted[0]'";
                        for ($j = 1; $j < count($arr_formatted); $j++) {
                            $sql_insert = $sql_insert . ",'$arr_formatted[$j]'";
                        }
                        $sql_insert = $sql_insert . ")";
                        if (($result = $ls_ex_conn->query($sql_insert)) == true) {
                        } else {
                            echo "Error: " . $sql_insert . "<br>" . $ls_ex_conn->error;
                        }
                        $ignore = false;
                    }
                }
            }

            if ($ignore == true) {
                // DELETE FROM INTERNAL TABLE

                $booking_id = $additional_in[$i]["booking_id"];
                // DELETE INTERNAL BOOKING
                $sql_delete = "DELETE FROM booking WHERE booking_id = '$booking_id'";
                if (($result = $ls_conn->query($sql_delete)) == true) {
                } else {
                    echo "Error: " . $sql_delete . "<br>" . $ls_conn->error;
                }
            }
        }

        // EMPTIES NEW BOOKINGS ARRAY 
        $del_id = "DELETE FROM new_bookings";
        $delete_result = $ls_conn->query($del_id);
    }
}
