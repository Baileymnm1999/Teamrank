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
        <link rel="stylesheet" href="./css/styles.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="/favicon.ico" type="image/x-icon" rel="icon" />
        <script type="text/javascript" src="./js/jquery-3.4.0.js"></script>
        <script type="text/javascript" src="./js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="./js/scripts.js"></script>
      </head>
    <body>
    <div id="wrap">
    <div id="main" class="container clear-top">';
}

function html_footer()
{
    echo
    '</div>
    </div>
    <footer class="page-footer font-small footer-bottom bg-light">
        <div class="footer-copyright text-center py-3">Teamrank&trade;&nbsp;&nbsp;
            <a href="https://github.com/Baileymnm1999/Teamrank/" ><i class="fab fa-github"></i></a>
        </div>
    </footer>
    </body>
    </html>';
}

function webpage_begin($active)
{
    echo
  '<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Teamrank&trade;</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link ' . (($active == 'Home') ? 'active' : '') . '" href="home">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link ' . (($active == 'Browse') ? 'active' : '') . '" href="browse">Browse</a>
      </li>
      <li class="nav-item">
        <a class="nav-link ' . (($active == 'My Leagues') ? 'active' : '') . '" href="my-leagues">My Leagues</a>
      </li>
      <li class="nav-item">
        <a class="nav-link ' . (($active == 'Manage') ? 'active' : '') . '" href="manage" >Manage</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout" >Logout</a>
      </li>
    </ul>
    <form action="browse" method="POST" class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search" aria-label="Search">
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
function to_table($keys, $data, $href, $url_param)
{
    echo '<tbody>';

    while ($row = $data->fetch_assoc()) {
        echo '<tr class="clickable-row" data-href="' . sprintf($href, $row[$url_param]) . '">';

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

function authenticate()
{
    if (isset($_COOKIE[user]) && isset($_COOKIE[username])) {
        $conn = connect();

        if ($stmt = $conn->prepare('SELECT `Username`, `Cookie` FROM `User` WHERE `Cookie` = ?')) {
            $stmt->bind_param('s', $_COOKIE[user]);
            $stmt->execute();
            $result = $stmt->get_result();

            $rows = $result->num_rows;
            $user = $result->fetch_assoc();
            if ($rows == 0 || $_COOKIE[user] != $user[Cookie] || $_COOKIE[username] != $user[Username]) {
                setcookie('error', 'Please sign in', time() + 60);
                header('Location: ./sign-in');
                $stmt->close();
                $conn->close();
                exit();
            }

            $stmt->close();
        }
        $conn->close();
    } else {
        setcookie('error', 'Please sign in', time() + 60);
        header('Location: ./sign-in');
        exit();
    }
}

function make_cookie()
{
    return substr(md5(rand()), 0, 16);
}

function add_season($post_url)
{
    echo
    '<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!-- date picker plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"/>

    <div class="modal" tabindex="-1" role="dialog" style="display: unset;margin-top: 50px">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add a season</h5>
          </div>
          <div class="modal-body">
              <!-- HTML Form (wrapped in a .bootstrap-iso div) -->
              <div class="bootstrap-iso">
              <div class="container-fluid">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <form method="post" action="' . $post_url . '">
               <div class="form-group ">
                <label class="control-label requiredField" for="startdate">
                 Start Date
                 <span class="asteriskField">
                  *
                 </span>
                </label>
                <input class="form-control" id="startdate" name="startdate" placeholder="MM/DD/YYYY" type="text"/>
               </div>
               <div class="form-group ">
                <label class="control-label " for="enddate">
                 End Date
                </label>
                <input class="form-control" id="enddate" name="enddate" placeholder="MM/DD/YYYY" type="text"/>
               </div>
               <div class="form-group">
                <div>
                 <button class="btn btn-primary btn-md btn-block bg-info" name="submit" type="submit">
                  Add Season
                 </button>
                </div>
               </div>
              </form>
              </div>
              </div>
              </div>
              </div>
          </div>
        </div>
      </div>
    </div>
';
}

function add_team($post_url)
{
    echo
    '<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!-- date picker plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"/>

    <div class="modal" tabindex="-1" role="dialog" style="display: unset;margin-top: 50px">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add a team</h5>
          </div>
          <div class="modal-body">
              <!-- HTML Form (wrapped in a .bootstrap-iso div) -->
              <div class="bootstrap-iso">
              <div class="container-fluid">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <form method="post" action="' . $post_url . '">
              <div class="form-group ">
                <label class="control-label requiredField" for="teamname">
                  Team Name
                  <span class="asteriskField">
                    *
                  </span>
                </label>
                <input class="form-control" id="teamname" name="teamname" placeholder="Cloud9" type="text"/>
              </div>
               <div class="form-group">
                <div>
                 <button class="btn btn-primary btn-md btn-block bg-info" name="submit" type="submit">
                  Add Team
                 </button>
                </div>
               </div>
              </form>
              </div>
              </div>
              </div>
              </div>
          </div>
        </div>
      </div>
    </div>
';
}

function add_league()
{
    echo
    '<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!-- date picker plugin -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"/>

    <div class="modal" tabindex="-1" role="dialog" style="display: unset;margin-top: 50px">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add a league</h5>
          </div>
          <div class="modal-body">
              <!-- HTML Form (wrapped in a .bootstrap-iso div) -->
              <div class="bootstrap-iso">
              <div class="container-fluid">
              <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
              <form method="post" action="./addleague">
              <div class="form-group ">
                <label class="control-label requiredField" for="leaguename">
                  Team Name
                  <span class="asteriskField">
                    *
                  </span>
                </label>
                <input class="form-control" id="leaguename" name="leaguename" placeholder="RLCS" type="text"/>
              </div>
               <div class="form-group">
                <div>
                 <button class="btn btn-primary btn-md btn-block bg-info" name="submit" type="submit">
                  Add League
                 </button>
                </div>
               </div>
              </form>
              </div>
              </div>
              </div>
              </div>
          </div>
        </div>
      </div>
    </div>
';
}
