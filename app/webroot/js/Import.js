function AssignType(elementID,Volume){

	FromPage = 'FromPage'+elementID;
	ToPage = 'ToPage'+elementID;
	VolumeType = "VolumeType"+elementID;
	From = document.getElementById(FromPage).value;
	To = document.getElementById(ToPage).value;	
	VT = document.getElementById(VolumeType).value;	

	$.getJSON('/Import/assign_volume_type/'+From+'/'+To+'/'+VT,
					function(){
						}
				  );
	alert("Done! You have to refresh the page now, CTRL + F5");

//	window.location = "/Import/Volume/"+Volume;
}
function ChangeImage(id,path,pageID){
	var img = path+pageID+'.gif';
	var ImageID = "THUMB"+id;
	document.getElementById(ImageID).src=img;
	}
	
function gotoPage(Volume){
	window.location = "/Tags/Volume/"+Volume;
	}