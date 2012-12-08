<?
echo "You have ";
echo $count;
echo " pages in the volume ";
echo $numeric_vol;
echo "-";
echo $roman_vol;
echo " to assign page types. ";

echo $this->form->create(null,array('class'=>'form form-horizontal','url'=>'/Import/Assign'));
echo $this->form->field("volume_number",array("type"=>'hidden','value'=>$volume_number));
echo $this->form->submit('Assign',array('class'=>'btn btn-primary'));
echo $this->form->end();

