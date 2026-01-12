// --------------------------------------------------------------------
var FLASH_DELAY = 4000; // ms
var positions_x = [0,1,2,3,4,5,6,7,8,9,10,11];
var positions_y = [20,25,30,35,40,45,50,55,60,65,70,75,80];

if (!Date.now) {
    Date.now = function() { return new Date().getTime(); };
}
var start_clic_time = Date.now();

var sequence_pid = 0;

var start_exp = false;

var test_i = -1;
var flash = true;
var abandon = false;

var target;

var current_ordering_list = [];
var current_response_list = [];

var image_ordering_mapping = [];
var score = 0;
var score_tmp = 0;

var questions = [];
var reponses = [];

//--------------------------------------------------------------------

function shuffle_array(a) {
	return a.sort(function() {
		  return .5 - Math.random();
	});
}

function abandonner() {
	abandon = true;
	next();
}

/**
 * Memmorise la target a modifier
 */
function  setTarget(t) {
	target = t;
}

function setChoice(image) {
	
	// GET CLIC TIME
	clic_times = Date.now() - start_clic_time;
	
	response_indice = parseInt(target.replace("#reponse_", ""));
	response_value = $(image).attr("alt");
	
	current_response_list[response_indice] = response_value;
	
	// MEMORIZE RESPONSE
	reponse = reponses[test_i];
	if (reponse != "") {
		reponse += ";";
	}
	reponse += $(target).attr("data-x") + ":" + response_value + ":" + clic_times;
	reponses[test_i] = reponse;
	
	if (current_response_list.length == current_ordering_list.length) {
		$("#envoyer").fadeIn();
	}
	
	$(target).attr("src", $(image).attr("src"));
	$(target + "_").hide();
	$(target).show();
	$("#choiceselection").foundation('close');
}

/**
 * Affiche une image aleatoirement
 */
function tick() {
	
	$("#exp_questions").hide();
	$("#exp_reponses").hide();
	$(".img").remove();
	$("#viseur").hide();
	
	current_ordering_list = ordering[test_i].split('');
	
	// Question
	if (flash) {
		
		$(".choice").hide();
		$("#exp_questions").show();
		$("#exp_questions").css("top", ($(window).height()-300) /2);
		
		/*
		cpt = 0;
		for (var i=0 ; i<12 ; i++) {
			if(i > 3 && i < 3 + current_ordering_list.length + 1) {
				image_src = current_ordering_list[cpt];
				console.log("#choice_" + current_ordering_list[cpt]);
				$("#choice_" + current_ordering_list[cpt]).show();
				console.log(image_src);
				$("#exp_questions").append("<div class='small-1 columns img'><img id='question_" + i + "' src='files/" + image_src + ".png' class='image_exp' /></div>");
				cpt++;
			} else {
				$("#exp_questions").append("<div class='small-1 columns img'>&nbsp;</div>");
			}
		}
		*/
		for (var i=0 ; i<current_ordering_list.length ; i++) {
			image_src = current_ordering_list[i];
			console.log("#choice_" + current_ordering_list[i]);
			$("#choice_" + current_ordering_list[i]).show();
			console.log(image_src);
			html = "<img id='question_" + i + "' src='files/" + image_src + ".png' class='img image_exp' />";
			$("#exp_questions").append(html);
		}
		
		questions[test_i] = ordering;
		
		flash = false;
		
	// Reponse
	} else {
		
		start_clic_time = Date.now(); // start timer
		
		reponses[test_i] = "";
		
		$("#exp_reponses").show();
		$("#exp_reponses").css("top", ($(window).height()-300) /2);
		
		for (var i=0 ; i<current_ordering_list.length ; i++) {
			// Choice selection
			html = "<a data-open='choiceselection' onclick='setTarget(\"#reponse_" + i + "\")'>";
			html += "<img id='reponse_" + i + "' src='img/question.png' class='img image_exp' data-x='" + i + "' />";
			html += "</a>";
			$("#exp_reponses").append(html);
			
		}
		
		flash = true;
		clearInterval(sequence_pid);
	}
}	

function loading() {
	
	$.each(images, function(i, image ) {
		img = new Image();
		img.src = image;
	});

	// parametre de l'exp
	console.log(ordering);
	console.log(images);
	
	setTimeout(function(){  $("#splashscreen").fadeOut(); }, 2000);
	setTimeout(function(){  $("#login").fadeIn(); }, 2000);
}

/**
 * Lancement de la fonction tick(), periodique
 */
function start_interval() {
	$("#viseur").hide();
	tick();
	sequence_pid = setInterval(tick, FLASH_DELAY);	
}

/**
 * start exp
 */
function next() {
	
	if (!start_exp) return;
	
	$("#login").hide();
	$("#consignes").hide();
	$("#envoyer").hide();
	$("#exp_questions").hide();
	$("#exp_reponses").hide();
	
	// AFFICHER RESULTAT
	if (current_ordering_list.length != 0) {
		
		result = "ok";
		if (current_ordering_list.toString() == current_response_list.toString()) {
			$('#bravo').foundation("open");
		} else {
			result = "ko";
			$('#perdu').foundation("open");
		}
		result_csv = $("#result_csv").val();
		var currentdate = new Date(); 
	    var datetime = currentdate.getDate() + "-"
	                + (currentdate.getMonth()+1)  + "-" 
	                + currentdate.getFullYear() + " "  
	                + currentdate.getHours() + ":"  
	                + currentdate.getMinutes() + ":" 
	                + currentdate.getSeconds();
		result_csv += datetime + "," + $('#identifiant_tmp').val() + "," + $('#age_tmp').val() + "," + exp_line[test_i] + "," + reponses[test_i] + "," + result + "," + current_response_list.join("")  + "\r\n";
		$("#result_csv").val(result_csv);
		
		current_ordering_list = [];
		current_response_list = [];
		
	// AFFICHER TEST
	} else {
		test_i++;
		
		// FIN DE L'EXP
		if (test_i>=ordering.length || abandon) {
			$("#empan").text(score);
			$("#empanReveal").foundation("open");
			
		// TEST SUIVANT
		} else {
			
			current_ordering_list = ordering[test_i].split('');
			html = "<img src='img/coffre_l.png' class='img image_exp' />";
			//html = "";
			for (var i=0 ; i<current_ordering_list.length ; i++) {
				html += "<img src='img/coffre_c.png' class='img image_exp' />";
				
			}
			html += "<img src='img/coffre_r.png' class='img image_exp' />";
			$("#viseur").append(html);
			$("#viseur").css("top",  ($(window).height()-300) /2);
			
			$("#viseur").show();
			setTimeout(start_interval, 4000);
		}
	}
}

$(document).foundation();