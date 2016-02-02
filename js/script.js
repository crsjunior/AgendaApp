function novaCategoria_onClick()
{
	alert('porra');
	var widthDocument = $('body').width();
	var widthFormCategoria = $('#formCategoria').outerWidth();
	$('#janela-categoria').css('margin-left', ((widthDocument / 2) - (widthFormCategoria / 2)) + 'px');
	$('#janela-categoria').css('visibility', 'visible');
}
