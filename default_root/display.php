<?
require "control/require.php";

if(!$HTTP_GET_VARS[p]){
	$HTTP_GET_VARS[p] = 'home';
}
$f = new frontend($PHP_SELF,$REQUEST_URI,$HTTP_GET_VARS,$HTTP_POST_VARS,$HTTP_SESSION_VARS);

echo $f->action();

?>
