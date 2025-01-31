<? 
require "control/require.php";

$p = new pages;
$parray = $p->get_all_elements(array('name' => 'home'));
if($page = $parray[0]){
	$id = $page->get_prop('id');
	header("location: display.php?p=Home");
}else{
	echo "no homepage specified.\n<br>";
}
?>
