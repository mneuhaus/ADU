$("[data-canvas-toggle]").live('click', function(){
	$(".off-canvas").toggleClass("off-canvas-active");
	return false;
});

$("[role='main']").live('click', function(){
	$(".off-canvas").removeClass("off-canvas-active");
});