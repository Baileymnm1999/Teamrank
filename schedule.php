<?php
include 'utils.php';

$conn = connect();

html_header('Schedule');

webpage_begin();

if (isset($_GET[team]) && isset($_GET[season])) {
    if ($stmt = $conn->prepare("CALL `Team_Schedule`(?, ?)")) {

        // execute prepared statement
        $stmt->bind_param("ii", $_GET[team], $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        // turn result set to table
        table_begin();
        table_header(array("Opponent", "Date", "Result", "Game Type"));
        to_table(array("Opponent", "Date", "Result", "GameType"), $result);
        table_end();

        mysqli_stmt_close($stmt);
    }
}

html_footer();

mysqli_close($conn);
