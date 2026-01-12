<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<title>Simon | 4 Child</title>

<link href="lib/foundation-6/css/foundation.min.css" rel="stylesheet" media="all" type="text/css">
<link href="lib/foundation-6/css/app.css" rel="stylesheet" media="all" type="text/css">
<link href="style.css" rel="stylesheet" media="all" type="text/css"> 

<link rel="apple-touch-icon" sizes="60x60" href="img/touch-icon-ipad.png">

<!-- Chargement des donnees de l'experience -->
<?php include('load_data.php'); ?>

</head>
<body>

	<div id="refresh_id"><?php echo uniqid(); ?></div>
	
	<!-- INTRO SPLASHSCREEN -->
	<div id="splashscreen">
		<div class="screen_container">
			<h4 id="splash_loading" style="position: relative;">Loading <img alt="" src="img/wait.gif" style="width: 30px;"></h4>
		</div>
	</div>
	
	<div id="login">
		<div class="screen_container">
		<br /><br /><br />
				<h3>Memory test</h3>
				<br />
				<img alt="coffre-l" src="img/coffre_l.png" class="coffre" /><img alt="coffre-c" src="img/coffre_c.png" class="coffre" /><img alt="coffre-c" src="img/coffre_c.png" class="coffre" /><img alt="coffre-c" src="img/coffre_c.png" class="coffre" /><img alt="coffre-r" src="img/coffre_r.png" class="coffre" /><br /><br />
				<a data-open="loginReveal" class="button" >Play !</a>
		</div>
	</div>
	
	<div id="jeux">
	
		<div id="viseur">
			<div style="position: absolute; width: 100%; text-align: 100%; top: -50px;">
				<h4>Ready ?</h4>
			</div>
		</div>
		<div id="exp_questions"></div>
		<div id="exp_reponses"></div>
		
		<div id="envoyer" class="row">
			<div class="small-12 columns">
	      		<button type="button" class="button" onclick="next();">Next</button>
			</div>
		</div>
	</div>
	

	
	<!-- POPUP -->
	<div id="loginReveal" class="tiny reveal" data-reveal >
		<h4>Identifiant/Age ?</h4>
		<input id="identifiant_tmp" type="text" value="" placeholder="Identifiant" />
		<input id="age_tmp" type="date" value="" />
		<input type="hidden" name="start" value="true" />
		<input type="button" class="tiny button" value="Commencer" onclick="start_exp = true; $('#loginReveal').foundation('close'); $('#login').hide(); next();" />
	</div>
	
	<div class="tiny reveal" id="choiceselection" data-reveal data-options="closeOnClick: false">
		<div class="row">
			<?php 
				$files = scandir('files/');
				foreach($files as $file) {
					if ($file != "." && $file != "..") { ?>
						<div id="choice_<?php echo str_replace(".png", "", $file) ?>" class="choice">
							<img class="image_exp" alt="<?php echo str_replace(".png", "", $file) ?>" src="files/<?php echo $file ?>" onclick="setChoice(this);" />
						</div>
						<?php
					}
				}
			?>
		</div>
    </div>
    
    <div id="bravo" class="tiny reveal" data-reveal data-options="closeOnClick: false">
	   	<h4>Great !</h4>
	   	<img alt="coffre-ouvert" src="img/coffre_ouvert.png" width="200" />
   		<div class="small-12 columns">
		  	<button type="button" class="success small button" data-close onclick="next();" style="background-color: #773d05;">Continue</button>
		  	<!-- <button type="button" class="alert small button" data-open="abandon">Quitter</button>  -->
		  	<a href="#" data-open="abandon" style="position: absolute; top: 0.2em; right: 0.5em; color: #4f4f4f;">X</a>
	  	</div>
	</div>
	        
    <div id="perdu" class="reveal" data-reveal data-options="closeOnClick: false">
       	<h4>Not really...</h4>
   		<div class="small-12 columns">
		  	<button type="button" class="success small button" data-close onclick="next();" style="background-color: #773d05;" >Continue</button>
		  	<!-- <button type="button" class="alert small button" data-open="abandon">Quitter</button> -->
		  	<a href="#" data-open="abandon" style="position: absolute; top: 0.2em; right: 0.5em; color: #4f4f4f;">X</a>
	  	</div>
    </div>
    
    <div id="abandon" class="tiny reveal" data-reveal data-options="closeOnClick: false">
       	<h4>Quit ?</h4>
       	<h5>Are you sure ?</h5>
   		<div class="small-12 columns">
   			<button type="button" class="success alert button" data-close onclick="abandonner();">Oui</button>
		  	<button type="button" class="success success button" data-close onclick="next();">Non</button>
	  	</div>
    </div>
    
    <div id="empanReveal" class="tiny reveal" data-reveal data-options="closeOnClick: false">
       	<h4>Félicitation tu as terminé !</h4>
		<form action="." method="post">
			<input id="identifiant" type="hidden" name="identifiant" value="" />
			<input id="age" type="hidden" name="age" value="" />
			<input type="hidden" id="result_csv" name="result_csv" />
	    	<input type="submit" class="success small button" onclick="$('#identifiant').val($('#identifiant_tmp').val()); $('#age').val($('#age_tmp').val());" value="Valider le résultat" />
	    </form>
    </div>
    
    <script src="lib/jquery.min.js"></script>
	<script src="lib/foundation-6/js/foundation.min.js"></script>
	<script src="lib/foundation-6/js/app.js"></script>
	<script src="lib/jquery.flip.min.js"></script>
	<script src="lib/fastclick.js"></script>
	<script type="application/javascript">
		window.addEventListener('load', function () {
			FastClick.attach(document.body);
		}, false);
	</script> 
	<script src="exp.js?<?php echo uniqid(); ?>"></script>
	
	<!-- Lancement de l'experience -->
	<script type="text/javascript">
  		$( document ).ready(function(){ 

  			$(document).foundation();

  			$("#viseur").css("top", $(window).height()/2 - 10 + "px");
  			
  			$(".screen_container").css("height", ($(window).height() - 100) + "px");
  			$("#splash_loading").css("top", (($(window).height() - 200) / 2) + "px");
  	  		
  			// -----------------------------
  			// on attrape l'évenement espace
  			// pour relancer l'exp
  			// -----------------------------
  			/*
	  		$(window).keydown(function(e) {
	  		  if (e.keyCode === 0 || e.keyCode === 32) {
	  			  next();
	  		  }
	  		});
	  		*/

  			// -----------------------------
  			// Contre le scroll automatique
  			// -----------------------------  			
  			window.onkeydown = function(e) { 
  			  return !(e.keyCode == 32);
  			};

  			loading();
	  		//next();
	  	});
	</script>
	
	
	
</body>
</html>