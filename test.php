<html>
<head>
	<meta charset="utf-8">
	<title>SALM - Suivi Livraion</title>
	<link rel="stylesheet" type="text/css" href="custom.css">
</head>
<?php 
  	try {
		$conn = new PDO ("sqlsrv:Server= tcp:orh0gtnyo4.database.windows.net,1433 ; Database = DATABASE_GLASS", "googleglass", "PVILpvil122");    
		//$conn = new PDO ("sqlsrv:Server= tcp:uovcbcoe7z.database.windows.net,1433 ; Database = DATABASE_GLASS", "azureuser", "Azurerpi15");    
		$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$conn->setAttribute( PDO::SQLSRV_ATTR_QUERY_TIMEOUT, 1 );

		// Add
		$queryT = 'insert into mro.SigFox (lumin, temp, song) values (10, 10, 10)';
		$stmtT = $conn->query( $queryT );
	} catch ( PDOException $e ) {
		print( "Error connecting to SQL Server." );
		die(print_r($e));
	}
?>