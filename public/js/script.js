$(".showmembers").click(function(){
	$('#infosperso').hide();
	$('#infosmembres').addClass('d-block');
});

$('#infosmembres').on('click','.delete-member',function(){
	var boutton = $(this);
	var id = $(this).attr('data-id');
	$.get(
		'/profil/deletemember/'+id,
		function(response){
			if(response == "ok")boutton.parent().parent().remove();
		}
	);
});

$('i').click(function(){
	var column = $(this).attr("data-champs");
	var order = $(this).attr("data-order");
	$.get(
		'/profil/list/'+column+'-'+order,
		function(response){
			$("#infosmembres table tbody").html(response);
		}
	);
});

$(".exportcontacts").click(function(){
	$.get(
		'/profil/export',
		function(response){
			alert(response);
		}
	);
});

$(".importcontacts").click(function(){
	var form = $(this).parent().find("form");
	form.toggle();
});
/*
$("#fimportcontacts").submit(function(e){
	e.preventDefault();
	$.get(
		'/profil/import',
		$(this).serialize(),
		function(response){
			alert(response);
		}
	);
});
*/
