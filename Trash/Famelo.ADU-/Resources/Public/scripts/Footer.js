// @codekit-prepend "../components/bootstrap/js/bootstrap-dropdown.js"
// @codekit-prepend "../components/bootstrap/js/bootstrap-tooltip.js"
// @codekit-prepend "../components/bootstrap/js/bootstrap-popover.js"
// @codekit-prepend "../components/famelo/components/scripts/off-canvas.js"
// @codekit-prepend "../components/bootstrap-datepicker/js/bootstrap-datepicker.js"
// @codekit-prepend "../components/bootstrap-datepicker/js/locales/bootstrap-datepicker.de.js"
// @codekit-prepend "../components/flexie/flexie.js"
// @codekit-prepend "../components/prettycheckable/prettyCheckable.js"
// @codekit-prepend "../components/jquery.validation/lib/jquery.metadata.js"
// @codekit-prepend "../components/jquery.validation/jquery.validate.js"
// @codekit-prepend "../components/jquery.validation/localization/messages_de.js"

// https://raw.github.com/DmitryBaranovskiy/raphael/300aa589f5a0ba7fce667cd62c7cdda0bd5ad904/raphael-min.js
// codekit-prepend "components/morris.js/morris.min.js"
// codekit-prepend "components/raty/js/jquery.raty.js"
// codekit-prepend "components/peity/jquery.peity.min.js"

$(document).ready(function(){
	$("[data-canvas-toggle]").click(function(){
		$(".off-canvas").toggleClass("off-canvas-active");
		return false;
	});

	$("[role='main']").click(function(){
		$(".off-canvas").removeClass("off-canvas-active");
	});

	$("[data-element='prettyCheckable'], .prettyCheckable").prettyCheckable();

	$(".happiness [data-element='prettyCheckable']").each(function(){
		var e = $(this);
		if (typeof(e.attr("data-marker")) !== "undefined") {
			e.next("a").text(e.attr("data-marker"));
		}
	});

	$(".happiness [data-element='prettyCheckable']").change(function(){
		var radio = $(this);
		var value = parseInt(radio.val());
		if (radio.attr('checked') != 'checked') {
			return;
		}
		if (value > 1) {
			radio.parents("td").find('.form-horizontal').show();
		} else {
			radio.parents("td").find('.form-horizontal').hide();
		}
	}).change();

	$('[rel="popover"]').popover({ html: true, placement: 'left' });
	$("form.validate, .validate form").validate({
		errorContainer: $("#warning"),
	});

	$('.dropdown-toggle').dropdown();

	$('[data-date-format]').datepicker({
		language: 'de',
		format: 'dd.mm.yyyy'
	});
});