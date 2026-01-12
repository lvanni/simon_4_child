<?php 
// Désactiver le rapport d'erreurs
error_reporting(0);

// Rapporte les erreurs d'exécution de script
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Rapporter les E_NOTICE peut vous aider à améliorer vos scripts
// (variables non initialisées, variables mal orthographiées..)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Rapporte toutes les erreurs à part les E_NOTICE
// C'est la configuration par défaut de php.ini
error_reporting(E_ALL & ~E_NOTICE);

// Reporte toutes les erreurs PHP (Voir l'historique des modifications)
error_reporting(E_ALL);

// Reporte toutes les erreurs PHP
error_reporting(-1);

// Même chose que error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

// Récupération des conditions (permutations) depuis le fichier CSV
$ordering = array();
$exp_line = array();
$exp_line_tmp = array();
$handle = file_get_contents("config.csv");
$handle_lines = preg_split('/\r\n|\r|\n/', $handle);
$sequence_id = 0;
$sequence_length = 0;

$i = 0;
$j = -1;
foreach ($handle_lines as $line) {
	
	$line_args = explode(';', $line);
	if ($i%6 == 0) {
		$j++;
		$ordering[$j] = array();
		$exp_line[$j] = array();
	}
	
	$line_args[4] = str_replace("-", "", $line_args[4]);
	array_push($ordering[$j], $line_args[4]);
	array_push($exp_line[$j], $line);
	$i++;
}

$filenameArray = array();
$handle_img = opendir('files/');
while($file = readdir($handle_img)) {
	if($file !== '.' && $file !== '..') {
		array_push($filenameArray, "files/" . $file);
	}
}
$handle_img = opendir('img/');
while($file = readdir($handle_img)) {
	if($file !== '.' && $file !== '..') {
		array_push($filenameArray, "img/" . $file);
	}
}

$selected_exp = rand(0 , count($ordering));
echo '<script type="text/javascript">var exp_line = ' . json_encode($exp_line[$selected_exp])  . '</script>';
echo '<script type="text/javascript">var ordering = ' . json_encode($ordering[$selected_exp])  . '</script>';
echo '<script type="text/javascript">var images = ' . json_encode($filenameArray)  . '</script>';
// --------------------------------------------------------------------

// Enegistrement des resultats
if (isset($_POST["result_csv"])) {
	
	// nom du fichier
	if(!empty($_POST["identifiant"])) {
		$file = uniqid() . ".csv";
	} else {
		$file = "test.csv";
	}
	
	file_put_contents("admin/results/" . $file, $_POST["result_csv"]);
	system('rm -rf admin/results/all_results.csv');
	system('cat admin/results/* > admin/results/all_results.csv');
} 
?>