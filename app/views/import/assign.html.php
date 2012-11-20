<?
	$this->scripts('<script src="/js/Import.js"></script>'); 
?>
<table class="table ">
	<thead>
		<tr>
			<th>Volume</th>
			<th>TypeID</th>
			<th>From Page</th>			
			<th>To Page</th>
			<th>Filename</th>
		</tr>
	</thead>
<tbody>
<?php
$i=1;
foreach ($pagetypes as $pagetype){
	echo "<tr>";
	echo "<td>Vol: ".$id."</td>";
	echo "<td>";
	echo $this->form->select('vol_types',$types,array('id'=>'VolumeType'.$i));
	echo "</td>";
	echo "<td>";
	?>
	<input type="hidden" id="FromPage<?=$i?>" value="<?=$pagetype->filename?>" >
	<img src="<?=CWMG_WEB_VOLUMES_PATH?>v<?=$numeric_vol?>-<?=$roman_vol?>/THUMB/<?=$pagetype->filename?>.gif" /><br>
	<?
	echo $pagetype->filename."</td>";
	echo "<td>";
	echo $this->form->select('topage',$topage,array('id'=>'ToPage'.$i,'onblur'=>'ChangeImage('.$i.',"'.
	CWMG_WEB_VOLUMES_PATH.'v'.$numeric_vol.'-'.$roman_vol.'/THUMB/",[this.options[this.selectedIndex].text]);'));
	echo "</td>";
	echo "<td>";
	?>

	<img id="THUMB<?=$i?>" src="<?=CWMG_WEB_VOLUMES_PATH?>v<?=$numeric_vol?>-<?=$roman_vol?>/THUMB/<?=$pagetype->filename?>.gif" /><br>
	<?
	echo $pagetype->filename."</td>";
	echo "<td><a href='#' onclick=\"AssignType(".$i.",'".$pagetype->filename."');\" class='btn btn-primary'>Assign</a></td>";
	echo "</tr>";	
	$i++;
}
?>
</tbody>
</table>