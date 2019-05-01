<?php
include 'utils.php';

authenticate();

html_header('Teams');

if (isset($_GET[season])) {
    $conn = connect();
    if ($stmt = $conn->prepare("CALL `Season_Teams`(?)")) {

        // execute prepared statement
        $stmt->bind_param("i", $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        // turn result set to table
        table_begin();
        table_header(array("Team Name"));
        to_table(array("Name"), $result, './schedule.php?team=%u&season=' . $_GET[season], 'TeamID');
        table_end();

        $stmt->close();
    }
    $conn->close();
}

html_footer();
