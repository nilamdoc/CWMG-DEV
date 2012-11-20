<div class="well">
<h2>Tag pages from volume:</h2>
<?
echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Tags/Volume'));
echo $this->form->select('_id',$volumes);
echo $this->form->submit('Submit',array('class'=>'btn btn-primary'));
echo $this->form->end();

?>
</div>