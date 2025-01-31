<?
require "require.php";


session_register("p");
session_register("sortby");
session_register("what");

if($HTTP_GET_VARS[sortby]){
        $sortby = $HTTP_GET_VARS[sortby];
}
if($HTTP_GET_VARS[p]){
        $p = $HTTP_GET_VARS[p];
}
if($HTTP_GET_VARS[what]){
	if($what != $HTTP_GET_VARS[what]){
		$p = 1;
	}
}
if($HTTP_GET_VARS[what]){
	$what = $HTTP_GET_VARS[what];
}

$a = new padmin(array(
		"bps" => $bparray,
		"local" => new local,
		"html" => new html($PHP_SELF),
		"display" => new display,
		"post_vars" => $HTTP_POST_VARS,
		"get_vars" => $HTTP_GET_VARS,
		"post_files" => $HTTP_POST_FILES,
		"session_vars" => $HTTP_SESSION_VARS
		)
	);

$admin =  $a->start();
$admin .=  $a->nav();
$admin .=  $a->body();
$admin .= $a->end();

echo $admin;
?>
