<?php
	session_start();
	include 'config.php';
	$db = connect_thonkdb();

			if(isset($_POST['btn_signup']))
			{
				$username = htmlspecialchars($_POST['username']);
				$email = htmlspecialchars($_POST['email']);
				if (find_username($db,$username) == true || check_email($db,$email) == true)
				{
					if (find_username($db,$username) == true)
					{
						echo '<script type="text/javascript">';
						echo 'document.getElementById("username_availability_result").innerHTML = " *Username already existed"';
						echo '</script>';
					}
					else if(check_email($db,$email) == true)
					{

						echo '<span style="color:red">This email is already used</span><br>';
						echo '<input type="button" value="Go Back" class="button special" onclick="window.history.back()"/>';

					}
				}
				else
				{ 
						$pw = htmlspecialchars($_POST['pw1']);	
						insert_user($db,$username,$pw,$email);
						$id = get_id_from_username($db,$username);
						header('Location: eVerification.php?u=' . $id);
				}
			}
			else //!isset-> show form
			{
?>
<!DOCTYPE html>
<html>
<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/jquery.scrollex.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
	<title>Signup</title>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript">
		function validation()
		{
				var count = 0;
				var pw_str = (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/);
				var str_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
				document.getElementById("pw1_error").innerHTML = "";
				document.getElementById("pw2_error").innerHTML = "";
				document.getElementById("email_error").innerHTML = "";
				document.getElementById("confirm_error").innerHTML = "";
				document.getElementById("username_availability_result").innerHTML = "";

				var email = document.getElementById("email").value;
				var username = document.getElementById("username").value;
				var pw1 = document.getElementById("pw1").value;
				var pw2 = document.getElementById("pw2").value;

				if(username.length == 0 || username.replace(" ","") == "")
				{
					count++;
					document.getElementById("username_availability_result").innerHTML = " *Please fill in your username";
				}

				if (email.replace(" ","") == "" || email.length === 0)
				{
					count++;
					document.getElementById("email_error").innerHTML = "*Please fill in your email address"; 
				}
				else if (str_email.test(email) == false)
				{
					count++;
					document.getElementById("email_error").innerHTML = "*Invalid Email";	
				}

				if (pw_str.test(pw1) == false)
				{
					count++;
					document.getElementById("pw1_error").innerHTML = "*Password must have at least 1 digit, 1 lower case & 1 upper case"; 
					document.getElementById("pw2_error").innerHTML = "";
				}

				if (pw1.length > 0 && pw1.length < 8)
				{
					count++;
					document.getElementById("pw1_error").innerHTML = "*Needs at least 8 characters"; 
					document.getElementById("pw2_error").innerHTML = "*Needs 1 digit, 1 lower case & 1 upper case"; 
				}

				if (pw1 !== pw2)
				{
					count++;
					document.getElementById("pw1_error").innerHTML = "*Passwords don't match"; 
				}
				
				if (pw1.length === 0 )
				{
					count++;
					document.getElementById("pw1_error").innerHTML = "*Please fill in your password"; 
				}

				if (pw1.length === 0 )
				{
					count++;
					document.getElementById("pw1_error").innerHTML = "*Please fill in your password"; 
				}

				if (pw2.length === 0 )
				{
					count++;
					document.getElementById("pw2_error").innerHTML = "*Please fill in your repeat password"; 
				}

				if (count == 0)
					return true;
				return false;
	}//end validation function


			$(document).ready(function() {
					//the min chars for username
					var min_chars = 1;
					//result texts
					var characters_error = ' *Please fill out this field';
					var checking_html = ' Checking...';

					//when button is clicked
					$('#check_username_availability').click(function(){
						//run the character number check
						if($('#username').val().length < min_chars){
							//if it's bellow the minimum show characters_error text '
							$('#username_availability_result').html(characters_error);
						}else{
							//else show the cheking_text and run the function to check
							$('#username_availability_result').html(checking_html);
							check_availability();
						}
					});

			  });

					//function to check username availability
					function check_availability(){
							document.getElementById("pw1_error").innerHTML = "";
							document.getElementById("pw2_error").innerHTML = "";
							document.getElementById("email_error").innerHTML = "";
							document.getElementById("confirm_error").innerHTML = "";
							//get the username
							var username = $('#username').val();

							//use ajax to run the check
							$.post("usernameCheck.php", { username: username },
								function(result){
									//if the result is 1
									if(result == 1){
										//show that the username is available
										$('#username_availability_result').html(' *This username is available');
										$('#username_availability_result').css('color','green');
										$('#btn_signup').removeAttr('disabled');
									}else {
										//show that the username is NOT available
										$('#username_availability_result').html(' *This username is already existed');
										$('#username_availability_result').css('color','red');
										$('#btn_signup').attr('disabled','disabled');
									}
							});

			}
			</script>
			<style>
		@media only screen and (max-device-width: 480px) {#main{width:90%}input[type="text"],input[type="password"]{width:99%;}input[type="submit"]{width:25%;}#suggestion{font-size:0.5em;}}
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:60%;margin:0 auto;padding-left:10%;padding-top:11%;padding-bottom:4%;}#suggestion{font-size:0.7em;}}
		@media only screen and (min-device-width: 1025px){ input[type="text"],input[type="password"]{width:80%;}
		#main{width:50%;margin:0 auto;padding-left:10%;padding-top:11%;padding-bottom:4%;}#suggestion{font-size:0.9em;}}
			h1{color:#77903D;font-weight: bold;font-size:1.5em;}
			/*#main{width:40%;margin:0 auto;}*/
			.error {color:red;font-size:0.8em;}

			</style>
</head>	
<body class="landing">
			<!-- Header -->
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

						<li><a href="./start_node.php">View Nodes</a></li>
                        						
                           <li><a href="">Info</a>
							<ul>
								<li><a href="./404.html">Search</a></li>
								<li><a href="./FAQ.php">FAQ</a></li>
								<li><a href="./rules.php">Rules</a></li>
								<li><a href="./contact.php">Contact</a></li>
							
							</ul></li>
                            <li><a href="./userSettings.php">Account</a></li>
						<li>
						<?php
							if(isset($_SESSION['username']))
								echo '<a href="./logout.php" class="button special">Sign Out</a>';
							else echo '<a href="./login.php" class="button special">Sign In</a>'
						?>
						</li>
					</ul>
				</nav>
			</header>
	<div class="wrapper" id="main">

			<h1>Registration</h1>
			<form onsubmit="return validation()" id="form_signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

						<label>Username</label>
						<input type="text" name="username" id="username"/>
						<br><input type='button' id='check_username_availability' value='Check Availability' class="button special"><span id='username_availability_result' class="error"></span>
					
						<br><br>
						<label>Password</label>
						<input type="password" name="pw1" id="pw1"/>
						<td><span id="pw1_error" class="error"></span>
					
						<br>
						<label>Password Repeat</label>
						<input type="password" name="pw2" id="pw2"/>
						<td><span id="pw2_error" class="error"></span>
					
						<br>
						<label>Email</label>
						<input type="text" name="email" id="email"/>
						<span id="email_error" class="error"></span>
					
						<br>
						<input type="submit" name="btn_signup" class="button special" id="btn_signup" value="Signup" disabled/>
						<br>
						<span id="confirm_error" class="confirm"></span>
					
				</table>
			</form>
		</fieldset>
		</div>
	</div>
	<!-- Footer -->
			<footer id="footer">
            
			  <ul class="icons">
					<li><a href="./"><img src="http://sdn-thonktest.rhcloud.com/media/images/logo-02.png" width=150px;></a></li><br><li><a href="./" title="Home" class="icon alt fa-home"><span class="label">Home</span></a></li><li><a href="https://twitter.com/thonk" title="Twitter" class="icon alt fa-twitter" target="new"><span class="label">Twitter</span></a></li>
					<li><a href="https://www.facebook.com/pages/THONK/258177714363949?fref=ts" title="Facebook" class="icon alt fa-facebook" target="new"><span class="label">Facebook</span></a></li>
					<li><a href="./contact.php" title="Email" class="icon alt fa-envelope" target="new"><span class="label">Email</span></a></li>
                    <li><a href="https://www.indiegogo.com/projects/thonk" title="Donate" class="icon alt fa-credit-card" target="new"><span class="label">Donate</span></a></li><li><a href="./404.html" title="Search" class="icon alt fa-search" target="new"><span class="label">Search</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; Thonk 2015. All rights reserved.</li>
				</ul>
			</footer>
</body>
</html>
<?php
} //end else
?> 