

<?php
  session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Thonk - View Nodes</title>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <style>
    @media only screen and (max-device-width: 480px) {#map{min-height:800px;}#footer{margin-top:850px;}text{font-size:0.7em;}}
    @media only screen and (min-device-width: 768px) and (max-device-width: 1024px){#map{min-height:800px;}#footer{margin-top: 875px}text{font-size:0.85em;}}
    @media only screen and (min-device-width: 1025px){#map{min-height:1024px;}#footer{margin-top:1075px;}}
  </style>
</head>

<body>
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
  <?php 
    include 'config_map.php';
  ?>
  <script src="http://d3js.org/d3.v3.min.js"></script>
  <script src="myscript.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

  <script src="js/jquery.min.js"></script>
    <script src="js/jquery.scrolly.min.js"></script>
    <script src="js/jquery.dropotron.min.js"></script>
    <script src="js/jquery.scrollex.min.js"></script>
    <script src="js/skel.min.js"></script>
    <script src="js/skel-layers.min.js"></script>
    <script src="js/init.js"></script>

  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" type="text/css">
          <noscript>
      <link rel="stylesheet" href="css/skel.css" />
      <link rel="stylesheet" href="css/style.css" />
      <link rel="stylesheet" href="css/style-xlarge.css" />     
    </noscript>

      <header id="header">
        <h1 id="logo"><a href="./"><img src="http://sdn-thonktest.rhcloud.com/media/images/logo-02.png" width=150px;></a></h1>

          <nav id="nav">
          <ul>
            <li>
              <?php
                if(isset($_SESSION['username']))
                  echo '<a href="userSettings.php">Logged in as ' . htmlspecialchars($_SESSION['username']) .'</a>';
                else echo '<a href="login.php">You are not logged in</a>';
              ?>
            </li>
            <li><a href="./">Home</a></li>
            <?php
              if(isset($_SESSION['username']))
              {
                include 'config.php';
                $db = connect_thonkdb();
                if(get_privilege_from_username($db,$_SESSION['username']) != 0)
                {
                  echo '<li><a href="./manageuser.php">User Manager</a></li>';
                  echo '<li><a href="./managenode.php">Node Manager</a></li>';
                }
              }
            ?>
            <li><a href="./start_node.php">View Nodes</a></li>
                                    
                                                            <li>
              <a href="">Info</a>
              <ul>
                <li><a href="./404.html">Search</a></li>
                <li><a href="./FAQ.php">FAQ</a></li>
                <li><a href="./rules.php">Rules</a></li>
                <li><a href="./contact.php">Contact</a></li>
              
              </ul>
                            
                            <li>
              <a href="./userSettings.php">Account</a>
            </li>
            <li>
            <?php
              if(isset($_SESSION['username']))
                echo '<a href="./logout.php" class="button special">Sign Out</a>';
              else echo '<a href="./login.php" class="button special">Sign In</a>'
            ?>
            </li>
          </ul>
        </nav></header>


  <div id="map">
        <i id="addNodeBtt" class="fa fa-plus fa-2x"></i>
        <i id="return_upper" class="fa fa-backward fa-2x"></i>
        <div id="waiting"><img src="giphy.gif" height="42" width="42"><h4>Loading ...</h4></div>
    
    <div id="hover-title" style="display: none">
        <span id="hint"></span>
    </div>

    <div id="infoPopup" style="display: none">

           <div id="information">
              <div id="title">Node title: </div>
              <div id="node_id" hidden>ID: </div>
              <div id="type">Node type: </div>
              <div id="status">Status: </div>
              <!--<div id="src">Source URL:</div>
              <div id="img">Image URL:</div>
              <div id="vid"Video URL:>Video URL:</div>
              <br>
               <button id="viewNode" class="btn btn-default dropdown-toggle" type="button">See Child Nodes</button>
              <br>
              <button id="viewInfo" class="btn btn-default dropdown-toggle" type="button">View node information</button>
              <br>
              <button id="modifyNodeBtt" class="btn btn-default dropdown-toggle" type="button">Modify node information</button>
              <div class="dropdown">
                <br>
                 <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Action
                  <span class="caret"></span></button>
                   <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">  
                    <li role="presentation"><a   id="viewInfo" role="menuitem" tabindex="-1" >View node information</a></li>
                    <li role="presentation" class="divider"></li>    
                    <li role="presentation"><a role="menuitem" id="modifyNodeBtt" tabindex="-1" >Modify selected node</a></li>
                  </ul>

              </div>

              <input id="up" type="image" src="up.jpg" width="40px" height="40px" title="Support the selected node"/>
              <input id="down" type="image" src="down.jpg" width="40px" height="40px" title="Discredit the selected node"/>
            -->
          </div>
    </div>


    <div id="infoPanel"   >
           <div id="action">

              <div id="selected_node">Selected Node: </div>
              <!--<div id="src">Source URL:</div>
              <div id="img">Image URL:</div>
              <div id="vid"Video URL:>Video URL:</div>-->
              
              <div id="type_panel">Node type: </div>
              
              <div class="dropdown" style="display:inline-block">
                 <button class="btn btn-default dropdown-toggle" type="button" id="nonparent_menu" data-toggle="dropdown" style:"z-index=100">Action
                  <span class="caret"></span></button>
                   <ul id="action_menu" class="dropdown-menu" role="menu" aria-labelledby="nonparent_menu">  
                    <li role="presentation"><a   id="viewInfo" role="menuitem" tabindex="-1" style:"z-index=100" >View node information</a></li>
                    <li role="presentation" class="divider"></li>    
                    <li role="presentation"><a role="menuitem" id="modifyNodeBtt" tabindex="-1"  style:"z-index=100">Modify selected node</a></li>
                  </ul>
              </div>

              <button id="viewNode" class="btn btn-default dropdown-toggle" type="button">See Child Nodes</button>

              <!--<button id="up" class="btn btn-default dropdown-toggle" type="button">Thumb up</button>
              <button id="down" class="btn btn-default dropdown-toggle" type="button">Thumb down</button> -->

             <!-- <input id="up" type="image" src="up.jpg" width="40px" height="40px" title="Support the selected node"/>
              <input id="down" type="image" src="down.jpg" width="40px" height="40px" title="Discredit the selected node"/>-->
            
          </div>
    </div>

  </div>

  <script type="text/javascript">
    var width = 500, height = 800;
    var layer_level =0;
    var current_selected_id;
    var refresh_current_layer=false;

    start_of_tree();
  </script><br><br>

        <div id="footer">
            
        <ul class="icons">
          <li><a href="./"><img src="http://sdn-thonktest.rhcloud.com/media/images/logo-02.png" width=150px;></a></li><br><br><li><a href="./" title="Home" class="icon alt fa-home"><span class="label">Home</span></a></li><li><a href="https://twitter.com/thonk" title="Twitter" class="icon alt fa-twitter" target="new"><span class="label">Twitter</span></a></li>
          <li><a href="https://www.facebook.com/pages/THONK/258177714363949?fref=ts" title="Facebook" class="icon alt fa-facebook" target="new"><span class="label">Facebook</span></a></li>
          <li><a href="./contact.php" title="Email" class="icon alt fa-envelope" target="new"><span class="label">Email</span></a></li>
                    <li><a href="https://www.indiegogo.com/projects/thonk" title="Donate" class="icon alt fa-credit-card" target="new"><span class="label">Donate</span></a></li><li><a href="./404.html" title="Search" class="icon alt fa-search" target="new"><span class="label">Search</span></a></li>
        </ul>
        <ul class="copyright">
          <li>&copy; Thonk 2015. All rights reserved.</li>
        </ul>
      </div>


</body>
</html>
