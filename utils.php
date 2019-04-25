<?php

function connect()
{
    // get connection info and connect
    $config = parse_ini_file('admin/config.ini');
    $conn = new mysqli($config[hostname], $config[username], $config[password], $config[database]);

    // error checking
    if (!$conn) {
        echo 'Error: Unable to connect to MySQL.' . PHP_EOL;
        echo 'Debugging errno: ' . $conn->errno() . PHP_EOL;
        echo 'Debugging error: ' . $conn->error() . PHP_EOL;
        exit;
    }

    return $conn;
}

function html_header($title)
{
    echo
    '<!DOCTYPE html>
      <html lang="en" dir="ltr">
      <head>
        <meta charset="utf-8">
        <title>' . $title . '</title>
        <link rel="stylesheet" type="text/css" href="style.css">
      </head>
    <body>';
}

function html_footer()
{
    echo
    '</body>
    </html>';
}

function webpage_header()
{
    echo '<div id="header"><a href="index.php">Teamrank</a></div>';
}

function table_begin()
{
    echo '<table cellspacing="0">';
}

// creates header row for table given list of column names
function table_header($columns)
{
    echo '<thead><tr>';

    foreach ($columns as $column) {
        echo '<th>' . $column . '</th>';
    }

    echo '</tr></thead>';
}

// turns mysqli result set to table body
function to_table($keys, $data)
{
    echo '<tbody>';

    while ($row = $data->fetch_assoc()) {
        echo '<tr>';

        foreach ($keys as $key) {
            echo '<td>' . $row[$key] . '</td>';
        }

        echo '</tr>';
    }

    echo '</tbody>';
}

// creates table footer with links
function table_footer($links, $cols)
{
    echo '<tfoot>
    <tr>
    <td colspan="' . $cols . '">
    <div class="links">
    <a href="#">&laquo;</a>';

    foreach ($links as $page => $link) {
        echo '<a href="' . $link . '">' . $page . '</a>';
    }
    echo '<a href="#">&raquo;</a>
    </div>
    </td>
    </tr>
    </tfoot>';
}

function table_end()
{
    echo '</table>';
}
