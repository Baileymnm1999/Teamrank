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
        <link rel="stylesheet" href="./css/bootstrap.min.css">
      </head>
    <body>';
}

function html_footer()
{
    echo
    '</body>
    </html>';
}

function webpage_begin($active)
{
    echo
  '<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ' . (($active == 'Home') ? 'active' : '') . '">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Link</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>';
}

function table_begin()
{
    echo '<table class="table table-hover">';
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
