<div class="well">
<h2>Import:</h2>
<?
echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Import/Volume'));
echo $this->form->select('volumes',$volumes);
echo $this->form->submit('Submit',array('class'=>'btn btn-primary'));
echo $this->form->end();

?>
</div>