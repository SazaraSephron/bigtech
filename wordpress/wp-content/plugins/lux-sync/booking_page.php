<?php
session_start();
ob_start();

add_action("admin_menu", "booking_menu");


function booking_menu()
{

    add_menu_page(
        "LuxSync",
        "LuxSync",
        "administrator",
        "luxsync",
        "luxsync_menu",
        "dashicons-media-spreadsheet",
        26
    );
}

function luxsync_menu()
{


    if (isset($_POST["next"])) {
        $_SESSION["ls_page_start"] += 10;
    } else if (isset($_POST["sel_page"])) {
        $_SESSION["ls_page_start"] = (int)$_POST["sel_page"];
    } else if (isset($_POST["back"])) {
        $_SESSION["ls_page_start"] = $_SESSION["ls_page_start"] - 10;
    } else {
        $_SESSION["ls_page_start"] = 0;
    }
    $start = $_SESSION["ls_page_start"];

    $begin = ($start - 0);
    $limit = 10;
    $back = $begin - $limit;
    $next = $begin + $limit;



    $ls_conn = $_SESSION["database"];
    $tableName = "booking";
    $columns = ["*"];
    $search = false;
    if (isset($_POST["data_search"])) {
        $search = true;
    }

    if (empty($ls_conn)) {
        $fetchData = "Database connection error";
    } elseif (empty($columns) || !is_array($columns)) {
        $fetchData = "columns Name must be defined in an indexed array";
    } elseif (empty($tableName)) {
        $fetchData = "Table Name is empty";
    } else {
        $columnName = implode(", ", $columns);
        if ($search) {
            $search_col = $_POST["search_in"];
            $search_val = $_POST["search"];

            $search_val = htmlspecialchars($search_val);
            $search_val  = mysqli_real_escape_string($ls_conn, $search_val);
            $search_val = "%" . $search_val . "%";

            $sql = "SELECT b.$columnName, u.display_name FROM $tableName b LEFT JOIN wp_users u ON b.user_id = u.ID WHERE $search_col LIKE (?) ORDER BY b.booking_id DESC LIMIT $begin, $limit";
            if ($query = $ls_conn->prepare($sql)) {
                $query->bind_param("s", $search_val);
            } else {
                $error = $ls_conn->errno . " " . $ls_conn->error;
                echo $error;
            }
            if ($count_query = $ls_conn->prepare("SELECT count(b.booking_id) FROM $tableName b LEFT JOIN wp_users u ON b.user_id = u.ID WHERE $search_col LIKE (?)")) {
                $count_query->bind_param("s", $search_val);
            }
            $ls_num_records = $count_query->execute();
            $ls_num_records = $count_query->get_result()->fetch_row()[0];
        } else {
            $sql = "SELECT b.$columnName, u.display_name FROM $tableName b INNER JOIN wp_users u WHERE b.user_id = u.ID ORDER BY b.booking_id DESC LIMIT $begin, $limit";

            // $sql = "SELECT b.$columnName FROM $tableName b ORDER BY b.booking_id DESC LIMIT $begin, $limit";

            if ($query = $ls_conn->prepare($sql)) {
            } else {
                $error = $ls_conn->errno . " " . $ls_conn->error;
                echo $error;
            }
            $ls_num_records = $ls_conn->query("SELECT count(booking_id) FROM $tableName")->fetch_row()[0];
        }
        $result = $query->execute();
        $result = $query->get_result();

        if ($result == true) {
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
                $fetchData = $row;
            } else {
                $fetchData = "No Data Found";
            }
        } else {
            $fetchData = mysqli_error($ls_conn);
        }
    }

    if (isset($_POST["data_delete"])) {
        
        
        $del_id = "INSERT new_deletions (booking_id) VALUES ({$_POST["data_id"]})";
        $del_id_result = $ls_conn->query($del_id);
        $query = "DELETE FROM booking WHERE booking_id={$_POST["data_id"]}";
        $result = $ls_conn->query($query);
        wp_redirect($_SERVER["HTTP_REFERER"]);
    }

    if (isset($_POST["update"])) {

        conv_and_sync();
        wp_redirect($_SERVER["HTTP_REFERER"]);
    }

?>
    <html>

    <head>
        <style>
            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529
            }

            .table td,
            .table th {
                padding: .75rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6
            }

            .table tbody+tbody {
                border-top: 2px solid #dee2e6
            }

            .table-sm td,
            .table-sm th {
                padding: .3rem
            }

            .table-bordered {
                border: 1px solid #dee2e6
            }

            .table-bordered td,
            .table-bordered th {
                border: 1px solid #dee2e6
            }

            .table-bordered thead td,
            .table-bordered thead th {
                border-bottom-width: 2px
            }

            .row {
                display: -ms-flexbox;
                display: flex;
                -ms-flex-wrap: wrap;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px
            }

            .col-sm-8 {
                position: relative;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px
            }

            .table {
                border-collapse: collapse !important
            }

            .table td,
            .table th {
                background-color: #fff !important
            }

            .table-bordered td,
            .table-bordered th {
                border: 1px solid #dee2e6 !important
            }
        </style>
        <link rel="stylesheet" type="text/css" href="css/booking_database.css" />
    </head>

    <body>
        <h1> Booking Table</h1>

        <form action="" method="post">
            <select name="search_in" id="search_in">
                <option value="booking_id">Booking ID</option>
                <option value="display_name">First Name</option>
                <option value="display_name">Last Name</option>
                <option value="technician_id">Technician ID</option>
                <option value="date_booked">Date Booked</option>
                <option value="time_booked">Time Booked</option>
                <option value="device_type">Device Type</option>
                <option value="device_service">Device Service</option>
                <option value="comment">Comment</option>
                <option value="status">Status</option>
                <option value="model_number">Model Number</option>
                <option value="serial_number">Serial Number</option>
                <option value="store_location">Store Location</option>

                <input type="text" id="search" name="search">
                <input type="submit" name="data_search" value="Search">
        </form>
        <form action="" method="post">
            <button type="submit" id="update" name="update">Update and Sync Database</button>
        </form>
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <?php echo $deleteMsg ?? ''; ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: 0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Booking ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Technician ID</th>
                                    <th>Date Booked</th>
                                    <th>Time Booked</th>
                                    <th>Device Type</th>
                                    <th>Device Service</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Model Number</th>
                                    <th>Serial Number</th>
                                    <th>Store Location</th>
                            </thead>
                            <tbody>
                                <?php
                                if (is_array($fetchData)) {
                                    $sn = 1;
                                    foreach ($fetchData as $data) {

                                        $name = $data['display_name'];
                                        $parts = explode(" ", $name);
                                        if (count($parts) > 1) {
                                            $lastname = array_pop($parts);
                                            $firstname = implode(" ", $parts);
                                        } else {
                                            $firstname = $name;
                                            $lastname = " ";
                                        }
                                ?>
                                        <tr>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="data_id" value="<?php echo $data["booking_id"]; ?>">
                                                    <input type="submit" name="data_delete" value="Delete" onclick="return confirm('Are you sure you want to delete this record?')">
                                                </form>
                                            </td>
                                            <td><?php echo $data['booking_id'] ?? ''; ?></td>
                                            <td><?php echo $firstname ?? ''; ?></td>
                                            <td><?php echo $lastname ?? ''; ?></td>
                                            <td><?php echo $data['technician_id'] ?? ''; ?></td>
                                            <td><?php echo $data['date_booked'] ?? ''; ?></td>
                                            <td><?php echo $data['time_booked'] ?? ''; ?></td>
                                            <td><?php echo $data['device_type'] ?? ''; ?></td>
                                            <td><?php echo $data['device_service'] ?? ''; ?></td>
                                            <td><?php echo $data['comment'] ?? ''; ?></td>
                                            <td><?php echo $data['status'] ?? ''; ?></td>
                                            <td><?php echo $data['model_number'] ?? ''; ?></td>
                                            <td><?php echo $data['serial_number'] ?? ''; ?></td>
                                            <td><?php echo $data['store_location'] ?? ''; ?></td>
                                        </tr>
                                    <?php
                                        $sn++;
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="8">
                                            <?php echo $fetchData; ?>
                                        </td>
                                    <tr>
                                    <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php

        if ($ls_num_records > $limit) {

        ?>
            <form action="" method="post">
                <table align='center' width='50%'>
                    <tr>
                        <td align='left' width='30%'>
                            <?php
                            if ($back >= 0) {
                            ?>
                                <button type="submit" name="back" id="back" value="<?php $back ?>">
                                    <font face='Verdana' size='2'>PREV</font>
                                </button>

                            <?php
                            }
                            ?>
                        </td>
                        <td align=center width='30%'>
                            <?php
                            $i = 0;
                            $l = 1;
                            for ($i = 0; $i < $ls_num_records; $i = $i + $limit) {
                                if ($i != $begin) {

                            ?>
                                    <button type="submit" name="sel_page" id="sel_page" value="<?php echo $i ?>" onClick="<?php //$_SESSION["ls_page_start"] = $i 
                                                                                                                            ?>">
                                        <font face='Verdana' size='2'><?php echo $l; ?>
                                    </button>

                                <?php
                                } else {

                                ?>
                                    <font face='Verdana' size='4' color=red><?php echo $l; ?></font>
                            <?php
                                }
                                $l = $l + 1;
                            }

                            ?>
                        </td>
                        <td align="right" width="30%">
                            <?php
                            if ($next < $ls_num_records) {
                            ?>
                                <button type="submit" name="next" id="next" value="<?php $next ?>">
                                    <font face="Verdana" size="2">NEXT</font>
                                </button>

                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </form>
        <?php
        }
        ?>
    </body>

    </html>
<?php
}
