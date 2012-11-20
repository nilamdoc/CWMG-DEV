<?php
	$this->scripts('<script src="/js/pdfobject.js"></script>'); 
	$this->scripts('<script src="/ckeditor/ckeditor.js"></script>'); 	
foreach ($pages as $p){
	$filename = $p->filename;
	$description = $p->description;
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
//print_r($pages);
?>