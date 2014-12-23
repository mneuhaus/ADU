$("[data-canvas-toggle]").live('click', function(){
	var e = $(this);
	if ($(".off-canvas").attr("data-canvas") === e.attr("data-canvas-toggle")){
		$(".off-canvas").removeAttr("data-canvas");
	} else {
		$(".off-canvas").attr("data-canvas", e.attr("data-canvas-toggle"));
	}
	return false;
});

$("[role='main']").live('click', function(){
	var e = $(this);
	$(".off-canvas").removeAttr("data-canvas");
});