/* global $ */
/* this is an example for validation and change events */
$.fn.numericInputExample = function () {
	'use strict';
	var cuota, cancelo1, cancelo1, cancelo2, cancelo3, cancelo4, cancelo5, cancelo6, cancelo7, cancelo8, cancelo, cancelo1, cancelo1, resta = "";
	var cuota1, total1 = 0.0;
	var element = $(this),
		footer = element.find('tfoot tr'),
		dataRows = element.find('tbody tr'),
		initialTotal = function () {
			var column, total;
			for (column = 3; column < 1; column++) {
				total = 0;
				dataRows.each(function () {
					var row = $(this);
					if(column == 7){
						//continue; 
						if(row.children().eq(column).find('input[type="checkbox"]').is(':checked')){
							total += 1;
						}
					}else{
						total += parseFloat(row.children().eq(column).text());						
					}
				});
				footer.children().eq(column).text(Number((total).toFixed(1)));
			};
		};
	element.find('td').on('change', function (evt) {
        recalcular(this);

		var cell = $(this),
			column = cell.index(),
			cuota = cell.text(),
			total = 0;
		if(column == 5){
			cuota1 = $(this).closest('tr').find('.A').text();
			total = parseFloat(cuota).toFixed(2) - parseFloat(cuota1).toFixed(2);
			$(this).closest('tr').find('.B').text(parseFloat(total).toFixed(2));
		}
		if (column === 0) {
			return;
		}
		element.find('tbody tr').each(function () {
			var row = $(this);
					if(column == 7){
						//continue; 
						if(row.children().eq(column).find('input[type="checkbox"]').is(':checked')){
							total += 1;
						}
					}else{
						total += parseFloat(row.children().eq(column).text());						
					}
					if(column == 5){
						//continue; 
						cuota = parseFloat(row.children().eq(column).text());
						cancelo1 = row.children().eq(1).text();
						cancelo2 = row.children().eq(2).text();
						cancelo3 = row.children().eq(3).text();
						cancelo4 = row.children().eq(4).text();
						cancelo5 = row.children().eq(5).text();
						cancelo6 = row.children().eq(6).text();
						cancelo7 = row.children().eq(7).text();
						cancelo8 = row.children().eq(8).text();  
						//resta = parseFloat(cuota) - parseFloat(cancelo) ;
						//alert(resta);
					}
		});
		if (column === 1 && total > 5000) {
			$('.alert').show();
			return false; // changes can be rejected
		} else {
			$('.alert').hide();
			footer.children().eq(column).text(Number((total).toFixed(1)));
		}
	}).on('validate', function (evt, value) {
		var cell = $(this),
			column = cell.index();
		if (column >= 0) {
			return !!value && value.trim().length > 0;
		} else {
			return !isNaN(parseFloat(value)) && isFinite(value);
		}
	});
	element.find('th').on('change', function (evt) {
		var cell = $(this),
			column = cell.index(),
			total = 0;
		if (column === 0) {
			return;
		}
		element.find('tbody tr').each(function () {
			var row = $(this);
			if(column == 7){
						//continue; 
						if(row.children().eq(column).find('input[type="checkbox"]').is(':checked')){
							total += 1;
						}
					}else{
						total += parseFloat(row.children().eq(column).text());						
					}
		});
		if (column === 1 && total > 5000) {
			$('.alert').show();
			return false; // changes can be rejected
		} else {
			$('.alert').hide();
			footer.children().eq(column).text(Number((total).toFixed(1)));
		}
	}).on('validate', function (evt, value) {
		var cell = $(this),
			column = cell.index();
		if (column === 0) {
			return !!value && value.trim().length > 0;
		} else {
			return !isNaN(parseFloat(value)) && isFinite(value);
		}
	});
	initialTotal();
	return this;
};
