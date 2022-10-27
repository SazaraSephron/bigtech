<?php
session_start();

function conv_and_sync()
{
    session_start();


    echo "Converting and Syncing";
    convert_data();
    sync_database();
}


function convert_data()
{
    $ls_conn = $_SESSION['database'];

    $ls_sql = "SELECT data_id, meta_key, meta_value FROM wp_fv_entry_meta m INNER JOIN wp_fv_enteries e ON m.data_id = e.id WHERE e.form_id = 1406;";
    $ls_result = $ls_conn->query($ls_sql);
    if ($ls_result !== false && $ls_result->num_rows > 0) {
        echo "<br>Data has been converted<br>";
        while ($ls_row = $ls_result->fetch_assoc()) {

            $current_id = $ls_row["data_id"];

            // Puts data in 
            $ls_data = $ls_row["meta_key"];

            switch ($ls_data) {
                case (str_contains($ls_row["meta_key"], 'Email') !== false):
                    $ls_data_email = $ls_row["meta_value"];
                    $get_id = "SELECT ID FROM wp_users WHERE user_email = '$ls_data_email'";
                    if ($ls_user_id = $ls_conn->query($get_id)->fetch_row()[0]) {
                    } else {
                        echo "Error: " . $get_id . "<br>" . $ls_conn->error;
                    }
                    break;
                case (str_contains($ls_row["meta_key"], 'Phone') !== false):
                    $ls_data_phone = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'First_Name') !== false):
                    $ls_data_fname = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Last_Name') !== false):
                    $ls_data_lname = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Device') !== false):
                    $ls_data_device = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Model') !== false):
                    $ls_data_model = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Serial') !== false):
                    $ls_data_serial = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Service') !== false):
                    $ls_data_service = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Booking_Date') !== false):
                    $ls_data_bookdate = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Booking_Time') !== false):
                    $ls_data_booktime = $ls_row["meta_value"];
                    break;
                case (str_contains($ls_row["meta_key"], 'Comment') !== false):
                    $ls_data_comment = $ls_row["meta_value"];
                    if ($ls_data_comment == "") {
                        $ls_data_comment = NULL;
                    }
                    break;
                case (str_contains($ls_row["meta_key"], 'Store') !== false):
                    $ls_data_store = $ls_row["meta_value"];

                    $table_result = mysqli_query($ls_conn, "SHOW TABLE STATUS LIKE 'booking'");
                    $data = mysqli_fetch_assoc($table_result);
                    $next_increment = $data['Auto_increment'];

                    $cre_id = "INSERT new_bookings (booking_id) VALUES ({$next_increment})";
                    $cre_id_result = $ls_conn->query($cre_id);

                    $ls_sql2 = "INSERT INTO `booking` (`user_id`, `technician_id`, `date_booked`, `time_booked`, `device_type`, `device_service`, `comment`, `status`, `model_number`, `serial_number`, `store_location`) VALUES ";
                    $ls_sql2 = $ls_sql2 . "('$ls_user_id', '0', '$ls_data_bookdate', '$ls_data_booktime', '$ls_data_device', '$ls_data_service', '$ls_data_comment', 'Pending', '$ls_data_model', '$ls_data_serial', '$ls_data_store');";



                    if ($ls_conn->query($ls_sql2) === TRUE) {

                        $ls_sql3 = "DELETE FROM wp_fv_entry_meta WHERE data_id = '$current_id'";
                        if ($ls_conn->query($ls_sql3) === TRUE) {

                            $ls_sql4 = "DELETE FROM wp_fv_enteries WHERE id = '$current_id' AND form_id = 1406";
                            if ($ls_conn->query($ls_sql4) === TRUE) {
                            } else {
                                echo "Error: " . $ls_sql4 . "<br>" . $ls_conn->error;
                            }
                        } else {
                            echo "Error: " . $ls_sql3 . "<br>" . $ls_conn->error;
                        }
                    } else {
                        echo "Error: " . $ls_sql2 . "<br>" . $ls_conn->error;
                    }

                    break;
            }
        }
    } else {
        echo "<br>There is no data to convert<br>";
    }
}
