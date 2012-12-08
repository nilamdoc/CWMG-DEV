<div class="well">
<h2>Import Pages:</h2>
<?
//print_r($IVolumes);
$i = 0;
foreach ($Ivolumes as $v){
print_r( $v->name);
$i++;
}
//print_r($GLOBALS['Show_SQL']);
echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Import/Volume'));
echo $this->form->select('_id',$volumes);
echo $this->form->submit('Submit',array('class'=>'btn btn-primary'));
echo $this->form->end();

?>
</div>

