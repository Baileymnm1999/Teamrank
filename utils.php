<?php

function connect()
{
    // get connection info and connect
    $config = parse_ini_file('admin/config.ini');
    $conn = new mysqli($config[hostname], $config[username], $config[password], $config[database]);

    // error checking
    if (!$conn) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . $conn->errno() . PHP_EOL;
        echo "Debugging error: " . $conn->error() . PHP_EOL;
        exit;
    }

    return $conn;
}

// creates header row for table given list of column names
function table_header($columns)
{
    echo "<table><thead><tr>";

    foreach ($columns as $column) {
        echo "<th>" . $column . "</th>";
    }

    echo "</tr></thead><tbody>";
}

// turns mysqli result set to table body
function to_table($keys, $data)
{
    while ($row = $data->fetch_assoc()) {
        echo "<tr>";

        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }

        echo "</tr>";
    }
}

// ends off a table
function table_footer()
{
    echo "</tbody></table>";
}
