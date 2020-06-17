
<?php 
ini_set('display_errors', 0 );
error_reporting(0);
// Conexão com xml
$xml_string = file_get_contents("extracted/RM2579.xml");
$xml_object = simplexml_load_string($xml_string);
$conexao = mysqli_connect("localhost", "root", "", "arizonwe_bdconsempi");
// Busca de tags e atributos internos no xml
foreach ($xml_object->processo as $processo) {	
	$numero=$processo->attributes()->numero;
	$marca=$processo->marca->nome;
	$dt_deposito=$processo->attributes()->{'data-deposito'};	
	$dt_concessao=$processo->attributes()->{'data-concessao'};
	$vigencia=$processo->attributes()->{'data-vigencia'};
	//$estado=$processo->{'lista-classe-nice'}->{'classe-nice'}->status;
	$status = ($processo->{'lista-classe-nice'}->{'classe-nice'}->status ? $processo->{'lista-classe-nice'}->{'classe-nice'}->status: 'em análise');
	
// Formatação de datas no php para sql
$dt_depositoBr = $dt_deposito;
$dt_concessaoBr = $dt_concessao;
$dt_vigenciaBr = $vigencia;
$dt_depositoFinal = DateTime::createFromFormat('d/m/Y',$dt_depositoBr)->format('Y-m-d');
$dt_concessaoFinal = DateTime::createFromFormat('d/m/Y',$dt_concessaoBr)->format('Y-m-d');
$dt_vigenciaFinal = DateTime::createFromFormat('d/m/Y',$dt_vigenciaBr)->format('Y-m-d');

//Query de inserção PHP
try {
	$username = 'arizonwe_consempiinsert';
	$password  = 'topweb@digital';
	$pdo = new PDO('mysql:host=localhost;dbname=arizonwe_bdconsempi', $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	$stmt = $pdo->prepare('INSERT INTO processo (processo, marca, dt_deposito, dt_concessao, vigencia, estado) VALUES(:numero, :marca, :dt_deposito, :dt_concessao, :vigencia, :estado)');
	$stmt->execute(array(
	
	':numero' => $numero,
	':marca' => $marca,
	':dt_deposito' => $dt_depositoFinal,
	':dt_concessao' => $dt_concessaoFinal,
	':vigencia' => $dt_vigenciaFinal,
	':estado' => $status
	
	)); 
 
} catch(PDOException $e) {
  echo "";
}} 

header("location: processos2.php");?>



