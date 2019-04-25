<?php
include 'utils.php';

$conn = connect();

html_header('Teams');

if (isset($_GET[season])) {
    if ($stmt = $conn->prepare("CALL `Season_Teams`(?)")) {

        // execute prepared statement
        $stmt->bind_param("i", $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        // turn result set to table
        table_begin();
        table_header(array("Team Name"));
        to_table(array("Name"), $result);
        table_end();

        mysqli_stmt_close($stmt);
    }
}

html_footer();

mysqli_close($conn);
