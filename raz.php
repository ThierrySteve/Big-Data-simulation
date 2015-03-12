<?php 
  	try {
		//$conn = new PDO ("sqlsrv:Server= tcp:orh0gtnyo4.database.windows.net,1433 ; Database = DATABASE_GLASS", "googleglass", "PVILpvil122");    
		$conn = new PDO ("sqlsrv:Server= tcp:uovcbcoe7z.database.windows.net,1433 ; Database = DATABASE_GLASS", "azureuser", "Azurerpi15");    
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$conn->setAttribute( PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1 );

		// Tournées
		$queryT = 'update glassCGIt.Tournee_PDL_Article set statut_tournee = \'Attente\', statut_pdl = \'Attente\', statut_livraison = \'Attente\', endommage = \'NON\'';
		$stmtT = $conn->query( $queryT );
		echo "Remise à zéro réussit";
		header('Location: index.php');
	} catch ( PDOException $e ) {
		print( "Error connecting to SQL Server." );
		die(print_r($e));
	}
?>