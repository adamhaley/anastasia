<?
require "require.php";

$db = new db;

$bps = new bplist;
$bparray = $bps->keys_as_array();

while(list($key,$value) = each($bparray)){
	$db->generate_from_blueprint($value);
}
?>
