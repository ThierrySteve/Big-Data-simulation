<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>SALM - Suivi Livraion</title>
	<link rel="stylesheet" type="text/css" href="custom.css">
</head>
<body >
	<div class="TopHeader"> 
		<div class="LogoContainer">
			<img src="SALM_logo.jpg" alt="Logo Salm" class="LogoSalm">
		</div>
		<div class="HorodatageContainer">
			<span class="HorodatageLabel">Dernière mise à jour : </span>
			<span class="HorodatageDate"><?php getMyDate()?></span>
			<span class="HorodatageHour"><?php getMyHeure()?></span>
		</div>
	</div>

	<div class="Header">
		<div class="BaseLine">
			<h1>Suivi livraison</h1>
		</div>
		<div class="SummaryContainer">
			<div class="SummaryLine">
				<span class="LabelLine">Tournée(s)</span>
				<span class="ValueLine"><?php getTourneeProgress()?></span>
			</div>
			<div class="SummaryLine">
				<span class="LabelLine">PDL</span>
				<span class="ValueLine"><?php getPdlProgress()?></span>
			</div>
			<div class="SummaryLine">
				<span class="LabelLine">Article(s)</span>
				<span class="ValueLine"><?php getArticleProgress()?></span>
			</div>
		</div>
	</div>

	<div class="LegendContainer"> 
		<div class="TitleLegendContainer">
			<p>Légende :</p>
		</div>
		<div class="ItemLegendContainer">
			<span class="Spot SpotStandBy"></span>
			<span class="Label">En Attente</span>
			<span class="Spot SpotInCourse"></span>
			<span class="Label">En Cours</span>
			<span class="Spot SpotFinish"></span>
			<span class="Label">Terminé</span>
			<span class="Spot"><img src="link55.png" class="SpotDammage"></span>
			<span class="Label">Article Endommagé</span>
		</div>
	</div>

	<div class="TableHeader">
		<table>
			<td class="TourneeTh">Tournée</td>
			<td class="PdlTh">Point de livraison</td>
			<td class="ArticleTh">Articles</td>
		</table>
	</div>

	<div class="MainContent">
		<?php 
	  	try {
			//$conn = new PDO ("sqlsrv:Server= tcp:orh0gtnyo4.database.windows.net,1433 ; Database = DATABASE_GLASS", "googleglass", "PVILpvil122");    
			$conn = new PDO ("sqlsrv:Server= tcp:uovcbcoe7z.database.windows.net,1433 ; Database = DATABASE_GLASS", "azureuser", "Azurerpi15");    
			$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$conn->setAttribute( PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1 );

			// Tournées
			$queryT = 'select * from glassCGIt.Tournees';
			$stmtT = $conn->query( $queryT );
			while ( $rowT = $stmtT->fetch( PDO::FETCH_ASSOC ) ){
				echo '<table width=100%>';
				$T = 0;

			   	// PDL with Tournée
				$queryP = 'select * from glassCGIt.PDL where id in (select id_pdl from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\')';
				$stmtP = $conn->query( $queryP );
				while ( $rowP = $stmtP->fetch( PDO::FETCH_ASSOC ) ){
					$P = 0;

					$A = 0;
					$queryTmp = 'select * from glassCGIt.ARTICLES where id in (select id_article from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\' and id_pdl = \''.$rowP['id'].'\')';
					$stmtTmp = $conn->query( $queryTmp );
					while ( $rowTmp = $stmtTmp->fetch( PDO::FETCH_ASSOC ) ){
						$A = $A + 1;
					}

					// Article with PDL & Tournée
					$queryA = 'select * from glassCGIt.ARTICLES where id in (select id_article from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\' and id_pdl = \''.$rowP['id'].'\')';
					$stmtA = $conn->query( $queryA );
					while ( $rowA = $stmtA->fetch( PDO::FETCH_ASSOC ) ){
						
						// Satus with Article & PDL & Tournée
						$queryS = 'select * from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\' and id_pdl = \''.$rowP['id'].'\' and id_article = \''.$rowA['id'].'\'';
						$stmtS = $conn->query( $queryS );
						while ( $rowS = $stmtS->fetch( PDO::FETCH_ASSOC ) ){
							$A = $A - 1;
							// Couleur pour la Tournée
							$colorT = "<span class=\"Spot SpotStandBy\"></span>";
							if (strcmp((string)$rowS['statut_tournee'], "Terminé") == 0) {
								$colorT = "<span class=\"Spot SpotFinish\"></span>";
							}elseif (strcmp((string)$rowS['statut_tournee'], "En Cours de Tournée") == 0) {
								$colorT = "<span class=\"Spot SpotInCourse\"></span>";
							}
							// Couleur pour le Pdl
							$colorP = "<span class=\"Spot SpotStandBy\"></span>";
							if (strcmp((string)$rowS['statut_pdl'], "Terminé") == 0) {
								$colorP = "<span class=\"Spot SpotFinish\"></span>";
							}elseif (strcmp((string)$rowS['statut_pdl'], "En Cours de Livraison") == 0) {
								$colorP = "<span class=\"Spot SpotInCourse\"></span>";
							}
							// Couleur pour l'Article'
							$colorA = "<span class=\"Spot SpotStandBy\"></span>";
							if (strcmp((string)$rowS['statut_livraison'], "Livré") == 0) {
								$colorA = "<span class=\"Spot SpotFinish\"></span>";
							}if (strcmp((string)$rowS['endommage'], "OUI") == 0) {
								$colorA = "<span class=\"Spot\"><img src=\"link55.png\" class=\"SpotDammage\"></span>";
							}

							// Echo
							echo '<tr>';
							if ($T == 0) { // Tournée
								// Articles dans Tournées
								$queryTmp = 'select * from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\'';;
								$stmtTmp = $conn->query( $queryTmp );
								$count = 0;
								while ( $rowTmp = $stmtTmp->fetch( PDO::FETCH_ASSOC ) ){
									$count++;
								}
								echo '<td rowspan="'.$count.'" class="TourneeCell">'.$colorT.(string)$rowT['libelle'].'</td>';
								$T = 1;
							}
							if ($P == 0) { // Pdl
								// Articles dans Pdl
								$count = 0;
								$queryTmp = 'select * from glassCGIt.ARTICLES where id in (select id_article from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\' and id_pdl = \''.$rowP['id'].'\')';
								$stmtTmp = $conn->query( $queryTmp );
								while ( $rowTmp = $stmtTmp->fetch( PDO::FETCH_ASSOC ) ){
									$count++;
								}
								echo '<td rowspan="'.$count.'" class="PdlCell">'.$colorP.(string)$rowP['nom_pdl'].'</td>';
								$P = 1;
							}
							// Article
							if ($A == 0) {
								echo '<td class="LastArticleCell">'.$colorA.(string)$rowA['nom_article'].'</td>';
							}else {
								echo '<td class="ArticleCell">'.$colorA.(string)$rowA['nom_article'].'</td>';
							}
						}
					}
				}
				echo '</table>';
			}

		} catch ( PDOException $e ) {
			print( "Error connecting to SQL Server." );
			die(print_r($e));
		}
		?>

		<button href="raz.php">Remise à zéro !</button>
	</div>
</body>
</html>

<?php
// Retourne la date
function getMyDate(){
	date_default_timezone_set('Europe/Paris');
	switch (date("D")) {
		case 'Mon':
			echo "Lundi ";
			break;
		case 'Tue':
			echo "Mardi ";
			break;
		case 'Wed':
			echo "Mercredi ";
			break;
		case 'Thu':
			echo "Jeudi ";
			break;
		case 'Fri':
			echo "Vendredi ";
			break;
		case 'Sat':
			echo "Samedi ";
			break;
		case 'Sat':
			echo "Dimanche ";
			break;
		default:
			echo "Le ";
			break;
	}
	echo date("d");
	switch (date("n")) {
		case 1:
			echo " janvier ";
			break;
		case 2:
			echo " février ";
			break;
		case 3:
			echo " mars ";
			break;
		case 4:
			echo " avril ";
			break;
		case 5:
			echo " mai ";
			break;
		case 6:
			echo " juin ";
			break;
		case 7:
			echo " juillet ";
			break;
		case 8:
			echo " août ";
			break;
		case 9:
			echo " septembre ";
			break;
		case 10:
			echo " octobre ";
			break;
		case 11:
			echo " novembre ";
			break;
		case 12:
			echo " décembre ";
			break;
		default:
			echo date("/n");
			break;
	}
	echo date("Y");
}

// Retourne l'heure
function getMyHeure(){
	date_default_timezone_set('Europe/Paris');
	echo date("H:i");
}

// Retourne l'avancé des tournées
function getTourneeProgress(){
	//$conn = new PDO ("sqlsrv:Server= tcp:orh0gtnyo4.database.windows.net,1433 ; Database = DATABASE_GLASS", "googleglass", "PVILpvil122");    
	$conn = new PDO ("sqlsrv:Server= tcp:uovcbcoe7z.database.windows.net,1433 ; Database = DATABASE_GLASS", "azureuser", "Azurerpi15");    
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$conn->setAttribute( PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1 );

	// Tournées Totales
	$queryT = 'select * from glassCGIt.Tournees';
	$stmtT = $conn->query( $queryT );
	$Tt = 0;
	while ( $rowT = $stmtT->fetch( PDO::FETCH_ASSOC ) ){
		$Tt = $Tt + 1;
	}
	// Tournées Finies
	$queryT = 'select * from glassCGIt.Tournees where id in (select id_tournee from glassCGIt.Tournee_PDL_Article where statut_tournee = \'Terminé\')';
	$stmtT = $conn->query( $queryT );
	$Tf = 0;
	while ( $rowT = $stmtT->fetch( PDO::FETCH_ASSOC ) ){
		$Tf = $Tf + 1;
	}

	echo $Tf.'/'.$Tt;
}

// Retourne l'avancé des Pdls
function getPdlProgress(){
	//$conn = new PDO ("sqlsrv:Server= tcp:orh0gtnyo4.database.windows.net,1433 ; Database = DATABASE_GLASS", "googleglass", "PVILpvil122");    
	$conn = new PDO ("sqlsrv:Server= tcp:uovcbcoe7z.database.windows.net,1433 ; Database = DATABASE_GLASS", "azureuser", "Azurerpi15");    
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$conn->setAttribute( PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1 );

	// Tournées Totales
	$queryT = 'select * from glassCGIt.Tournees';
	$stmtT = $conn->query( $queryT );
	$Pt = 0;
	while ( $rowT = $stmtT->fetch( PDO::FETCH_ASSOC ) ){
		// PDL with Tournée
		$queryP = 'select * from glassCGIt.PDL where id in (select id_pdl from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\')';
		$stmtP = $conn->query( $queryP );
		while ( $rowP = $stmtP->fetch( PDO::FETCH_ASSOC ) ){
			$Pt = $Pt + 1;
		}
		
	}

	// Tournées Totales
	$queryT = 'select * from glassCGIt.Tournees';
	$stmtT = $conn->query( $queryT );
	$Pf = 0;
	while ( $rowT = $stmtT->fetch( PDO::FETCH_ASSOC ) ){
		// PDL Finis with Tournée
		$queryP = 'select * from glassCGIt.PDL where id in (select id_pdl from glassCGIt.Tournee_PDL_Article where id_tournee = \''.$rowT['id'].'\' and statut_pdl = \'Terminé\')';
		$stmtP = $conn->query( $queryP );
		while ( $rowP = $stmtP->fetch( PDO::FETCH_ASSOC ) ){
			$Pf = $Pf + 1;
		}
		
	}

	echo $Pf.'/'.$Pt;
}

// Retourne l'avancé des Articles
function getArticleProgress(){
	//$conn = new PDO ("sqlsrv:Server= tcp:orh0gtnyo4.database.windows.net,1433 ; Database = DATABASE_GLASS", "googleglass", "PVILpvil122");    
	$conn = new PDO ("sqlsrv:Server= tcp:uovcbcoe7z.database.windows.net,1433 ; Database = DATABASE_GLASS", "azureuser", "Azurerpi15");    
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$conn->setAttribute( PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1 );

	// Articles Totales
	$queryA = 'select * from glassCGIt.Tournee_PDL_Article';
	$stmtA = $conn->query( $queryA );
	$At = 0;
	while ( $rowA = $stmtA->fetch( PDO::FETCH_ASSOC ) ){
		$At = $At + 1;
	}

	// Articles Totales
	$queryA = 'select * from glassCGIt.Tournee_PDL_Article where statut_livraison = \'Livré\'';
	$stmtA = $conn->query( $queryA );
	$Af = 0;
	while ( $rowA = $stmtA->fetch( PDO::FETCH_ASSOC ) ){
		$Af = $Af + 1;
	}

	echo $Af.'/'.$At;
}
?>



