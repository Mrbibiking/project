$(document).ready(function(){
	showIdType();	
	showCountries();
	showNation();
	showEducation();
	showOccupation();
	showCity();
	$('#city').bind('change',function(){
		var parentid= $('#city').val();
		var UnderCityHTML="";
		try{
			  $.ajax({
				  type:"post",
				  url:"/SBES/allUnderCity.do", 
				  data:{parentid:parentid},
				  dataType:"json",
				  success:function(data){
					for(var i=0;i<data.length;i++){
						UnderCityHTML+="<option value="+data[i].FDISTID+">"+data[i].FDISTNAME+"</option>";
					}
					$("#UnderCity").html(UnderCityHTML);
		  	       }
				});
			  
		  }catch (e) {
			alert('提交异常'+e);
		  }
	});
});
//省市级联


//省 下拉列表


function showCity(){
	  var cityHTML="";
	  try{
		  $.ajax({
			  type:"post",
			  url:"/SBES/allcity.do", 
			  data:{},
			  dataType:"json",
			  success:function(data){
				for(var i=0;i<data.length;i++){
					cityHTML+="<option value="+data[i].FDISTID+">"+data[i].FDISTNAME+"</option>";
				}
				$("#city").html(cityHTML);
				var CityId= data[0].FDISTID;
				var UnderCityHTML="";
				  $.ajax({
					  type:"post",
					  url:"/SBES/allUnderCity.do",
					  data:{parentid:CityId},
					  dataType:"json",
					  success:function(datas){	  
						  for(var i=0;i<datas.length;i++){
								UnderCityHTML+="<option value="+datas[i].FDISTID+">"+datas[i].FDISTNAME+"</option>";
							}
							$("#UnderCity").html(UnderCityHTML);
					  }
					  
				  });
				
	  	       }
			});
		  
	  }catch (e) {
		alert('提交异常'+e);
	  }
}

//所属行业下拉列表
  function showOccupation(){
	  var OccupationHTML="";
	  try{
		  $.ajax({
			  type:"post",
			  url:"/SBES/occupation.do", 
			  data:{},
			  dataType:"json",
			  success:function(data){
				for(var i=0;i<data.length;i++){
					OccupationHTML+="<option value="+data[i].FITEMID+">"+data[i].FNAME+"</option>";
				}
				$("#fOccupation").html(OccupationHTML);
	  	       }
			});
		  
	  }catch (e) {
		alert('提交异常'+e);
	  }
  }

//学历下拉列表
  function showEducation(){
	  var EducationHTML="";
	  try{
		  $.ajax({
			  type:"post",
			  url:"/SBES/education.do", 
			  data:{},
			  dataType:"json",
			  success:function(data){
				for(var i=0;i<data.length;i++){
					EducationHTML+="<option value="+data[i].FITEMID+">"+data[i].FNAME+"</option>";
				}
				$("#fEducation").html(EducationHTML);
	  	       }
			});
		  
	  }catch (e) {
		alert('提交异常'+e);
	  }
  }
//民族下拉列表
  function showNation(){
	  var NationHTML="";
	  try{
		  $.ajax({
			  type:"post",
			  url:"/SBES/nation.do", 
			  data:{},
			  dataType:"json",
			  success:function(data){
				for(var i=0;i<data.length;i++){
					NationHTML+="<option value="+data[i].FITEMID+">"+data[i].FNAME+"</option>";
				}
				$("#fNation").html(NationHTML);
	  	      }
			});
		  
	  }catch (e) {
		alert('提交异常'+e);
	  }
  }
//国家下拉框
  function showCountries(){
	  var CountriesHTML="";
	  try{
		  $.ajax({
			  type:"post",
			  url:"/SBES/countries.do", 
			  data:{},
			  dataType:"json",
			  success:function(data){
				for(var i=0;i<data.length;i++){
					CountriesHTML+="<option value="+data[i].FITEMID+">"+data[i].FNAME+"</option>";
				}
				$("#fCountry").html(CountriesHTML);
	  	      }
			});
		  
	  }catch (e) {
		alert('提交异常'+e);
	  }
	  
  }
  
//证件类型下拉框
  function showIdType(){  
	  var idtypeHtml="";
	  try{
		  $.ajax({
			  type:"post",
			  url:"/SBES/idType.do", 
			  data:{},
			  dataType:"json",
			  success:function(data){
				  
				for(var i=0;i<data.length;i++){
					idtypeHtml+="<option value="+data[i].FITEMID+">"+data[i].FNAME+"</option>";
				}
				$("#idType").html(idtypeHtml);
	  	      }
			});
		  
	  }catch (e) {
		alert('提交异常'+e);
	}
	  
  }
  
