<?php
	$this->scripts('<script src="/js/pdfobject.js"></script>'); 
	$this->scripts('<script src="/ckeditor/ckeditor.js"></script>'); 	
	$this->scripts('<script src="/js/Import.js"></script>'); 

foreach ($pages as $p){
	$filename = $p->filename;
	$description = $p->description;
	$id = $p->_id;
}

?>

<script type="text/javascript">

	window.onload = function (){
	
		var myPDF = new PDFObject({ 
		
			url: "/eBookFiles/v<?=$numeric_vol?>-<?=$roman_vol?>/PDF/<?=$filename?>.pdf",
			pdfOpenParams: {
				navpanes: 0,
				toolbar: 1,
				statusbar: 0,
				view: "FitV"
			}
		
		}).embed("pdf");
	
	};
</script>
<style>
#pdf {
	width: 100%;
	height: 550px;
	margin: 1em auto;
	border: 2px solid #6699FF;
}

#pdf p {
   padding: 1em;
}

#pdf object {
   display: block;
   border: solid 1px #666;
}


</style>
<hr>
<h2><?=$filename?></h2>
<? 	echo $this->form->select('topage',$topage,
		array('id'=>'ToPage',
		'onblur'=>'gotoPage([this.options[this.selectedIndex].text]);',
//		'onchange'=>'showUpdated([this.options[this.selectedIndex].text])',
		'value'=>(string)$id)
		);
	echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Tags/Save'));
?>

<?php

	echo $this->form->hidden('filename', array('value'=>$filename,'type'=>'hidden'));
	echo $this->form->submit('Save',array('class'=>'btn btn-primary'));
?>

<div class="row" >
	<div class="span6">
		<div id="pdf">
		</div>
	</div>
	<div class="span6" style="maxHeight:600px">
		<?php echo $this->form->textarea('content', array('class'=>'ckeditor','value'=>$description));?>
	</div>
</div>
<?php
	echo $this->form->hidden('filename', array('value'=>$filename,'type'=>'hidden'));
	echo $this->form->submit('Save',array('class'=>'btn btn-primary'));
	echo $this->form->end();
?>
    <input type="text" value="" name=""><br>
    <select>
     <option>Some options which is very very long... </option>
    </select>