<div class="well">
<h2>Import Confirmation:</h2>
<?
echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Import/Pages'));
echo "From URL: ".$url.'<br>';
echo "From Volume: ".$numeric_vol.'-'.$roman_vol.'<br>';
echo "<div class='alert alert-error'>No of pages in database: ".$count.'. These pages will be overwritten.</div><br>';
echo $this->form->field("volume_number",array("type"=>'hidden','value'=>$volume_number));
echo '<div class="btn-group">';
echo $this->form->submit('Confirm',array('class'=>'btn btn-primary'));
echo $this->html->link('Cancel','/Import',array('class'=>'btn'));
echo $this->html->link('Assign','/Import/Assign',array('class'=>'btn'));
echo '</div>';
echo $this->form->end();
?>
</div>
<div class="well">
<h2>Import Files:</h2>
<?
echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Import/files'));
echo "From URL: ".$url.'<br>';
echo "From Volume: ".$numeric_vol.'-'.$roman_vol.'<br>';
echo "<div class='alert alert-error'>No of pages in database: ".$count.'. These pages will be overwritten.</div><br>';
echo $this->form->field("volume_number",array("type"=>'hidden','value'=>$volume_number));
echo '<div class="btn-group">';
echo $this->form->submit('Confirm',array('class'=>'btn btn-primary'));
echo '</div>';
echo $this->form->end();
?>
</div>