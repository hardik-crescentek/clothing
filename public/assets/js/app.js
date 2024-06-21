(function ($) {

	'use strict';

	// ------------------------------------------------------- //
	// Preloader
	// ------------------------------------------------------ //
	$(window).on("load", function () {
		$(".loader").fadeOut();
		$("#preloader").delay(350).fadeOut("slow");
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
	});

	// ------------------------------------------------------- //
	// Sidebar Functionality
	// ------------------------------------------------------ //
	$('#toggle-btn').on('click', function (e) {
		e.preventDefault();
		$(this).toggleClass('active');

		$('.side-navbar').toggleClass('shrinked');
		$('.content-inner').toggleClass('active');

		if ($(window).outerWidth() > 1183) {
			if ($('#toggle-btn').hasClass('active')) {
				$('.navbar-header .brand-small').hide();
				$('.navbar-header .brand-big').show();
			} else {
				$('.navbar-header .brand-small').show();
				$('.navbar-header .brand-big').hide();
			}
		}

		if ($(window).outerWidth() < 1183) {
			$('.navbar-header .brand-small').show();
		}
	});
	// Close dropdown after click
	$(function () {
		$(".side-navbar li a").click(function (event) {
			$(".collapse").collapse('hide');
		});
	});

	// ------------------------------------------------------- //
	// Dynamic Height
	// ------------------------------------------------------ //	
	$(window).resize(function () {
		var height = $(this).height() - $(".header").height() + $(".main-footer").height()
		$('.d-scroll').height(height);
	})

	$(window).resize();

	// ------------------------------------------------------- //
	// Auto Height Scrollbar
	// ------------------------------------------------------ //
	$(window).resize(function () {
		$('.auto-scroll').height($(window).height() - 130);
	});

	$(window).trigger('resize');

	// ------------------------------------------------------- //
	// Back To Top
	// ------------------------------------------------------ //
	$(function () {
		// Show or hide the sticky footer button
		$(window).scroll(function () {
			if ($(this).scrollTop() > 350) {
				$('.go-top').fadeIn(100);
			} else {
				$('.go-top').fadeOut(200);
			}
		});

		// Animate the scroll to top
		$('.go-top').click(function (event) {
			event.preventDefault();

			$('html, body').animate({
				scrollTop: 0
			}, 800);
		})
	});

	// ------------------------------------------------------- //
	// Custom Checkbox (check, heart, star)
	// ------------------------------------------------------ //
	$('.checkbox').click(function () {
		$(this).toggleClass('is-checked');
	});

	// ------------------------------------------------------- //
	// Check / Uncheck all checkboxes
	// ------------------------------------------------------ //
	$("#check-all").change(function () {
		$("input:checkbox").prop('checked', $(this).prop("checked"));
	});

	// ------------------------------------------------------- //
	// Card Close
	// ------------------------------------------------------ //
	$('a.remove').on('click', function (e) {
		e.preventDefault();
		$(this).parents('.col-remove').fadeOut(500);
	});

	// ------------------------------------------------------- //
	// Sidebar Scrollbar
	// ------------------------------------------------------ //	
	$(".sidebar-scroll").niceScroll({
		cursorcolor: "transparent",
		cursorborder: "transparent",
		cursoropacitymax: 0,
		boxzoom: false,
		autohidemode: "hidden",
		cursorfixedheight: 80
	});

	// ------------------------------------------------------- //
	// Widget Scrollbar
	// ------------------------------------------------------ //	
	$(".widget-scroll").niceScroll({
		railpadding: {
			top: 0,
			right: 3,
			left: 0,
			bottom: 0
		},
		scrollspeed: 100,
		zindex: "auto",
		autohidemode: "leave",
		cursorwidth: "4px",
		cursorcolor: "rgba(52, 40, 104, 0.1)",
		cursorborder: "rgba(52, 40, 104, 0.1)"
	});

	// ------------------------------------------------------- //
	// Table Scrollbar
	// ------------------------------------------------------ //	
	$(".table-scroll").niceScroll({
		railpadding: {
			top: 0,
			right: 0,
			left: 0,
			bottom: 0
		},
		scrollspeed: 100,
		zindex: "auto",
		autohidemode: "leave",
		cursorwidth: "4px",
		cursorcolor: "rgba(52, 40, 104, 0.1)",
		cursorborder: "rgba(52, 40, 104, 0.1)"
	});

	// ------------------------------------------------------- //
	// Offcanvas Scrollbar
	// ------------------------------------------------------ //	
	$(".offcanvas-scroll").niceScroll({
		railpadding: {
			top: 0,
			right: 2,
			left: 0,
			bottom: 0
		},
		scrollspeed: 100,
		zindex: "auto",
		hidecursordelay: 800,
		cursorwidth: "3px",
		cursorcolor: "rgba(52, 40, 104, 0.1)",
		cursorborder: "rgba(52, 40, 104, 0.1)",
		preservenativescrolling: true,
		boxzoom: false
	});

	// ------------------------------------------------------- //
	// Search Box
	// ------------------------------------------------------ //
	$('#search').on('click', function (e) {
		e.preventDefault();
		$('.search-box').slideDown();
	});
	$('.dismiss').on('click', function () {
		$('.search-box').slideUp();
	});

	// ------------------------------------------------------- //
	// Adding slide effect to dropdown
	// ------------------------------------------------------ //
	$('.dropdown').on('show.bs.dropdown', function (e) {
		$(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
	});

	$('.dropdown').on('hide.bs.dropdown', function (e) {
		$(this).find('.dropdown-menu').first().stop(true, true).slideUp(300);
	});

	// ------------------------------------------------------- //
	// Options hover effect to dropdown
	// ------------------------------------------------------ //
	$('.widget-options > .dropdown, .actions > .dropdown, .quick-actions > .dropdown').hover(function () {
		$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(350);
	}, function () {
		$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(350);
	});

	// ------------------------------------------------------- //
	// Offcanvas Sidebar
	// ------------------------------------------------------ //
	$(function () {
		//open
		$('.open-sidebar').on('click', function (event) {
			event.preventDefault();
			$('.off-sidebar').addClass('is-visible');
		});
		//close
		$('.off-sidebar').on('click', function (event) {
			if ($(event.target).is('.off-sidebar') || $(event.target).is('.off-sidebar-close')) {
				$('.off-sidebar').removeClass('is-visible');
				event.preventDefault();
			}
		});
	});

	// ------------------------------------------------------- //
	// Close Modal After Time Period
	// ------------------------------------------------------ //
	$(function () {
		$('#delay-modal').on('show.bs.modal', function () {
			var myModal = $(this);
			clearTimeout(myModal.data('hideInterval'));
			myModal.data('hideInterval', setTimeout(function () {
				myModal.modal('hide');
			}, 2500));
		});
	});

	$.validate();

})(jQuery);

function meter2yard(meter) {
	return meter * 1.09361;
}

function uuid() {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
		var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
		return v.toString(16);
	});
}

function uuidFast() {
	var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
	var uuid = new Array(36), rnd = 0, r;
	for (var i = 0; i < 36; i++) {
		if (i == 8 || i == 13 || i == 18 || i == 23) {
			uuid[i] = '-';
		} else if (i == 14) {
			uuid[i] = '4';
		} else {
			if (rnd <= 0x02) rnd = 0x2000000 + (Math.random() * 0x1000000) | 0;
			r = rnd & 0xf;
			rnd = rnd >> 4;
			uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
		}
	}
	return uuid.join('');
}

function uniqueId(len, radix) {
	var chars = 'sadasd0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
	var uuid = [], i;
	radix = radix || chars.length;

	if (len) {
		// Compact form
		for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
	} else {
		// rfc4122, version 4 form
		var r;

		// rfc4122 requires these characters
		uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
		uuid[14] = '4';

		// Fill in random data.  At i==19 set the high bits of clock sequence as
		// per rfc4122, sec. 4.1.5
		for (i = 0; i < 36; i++) {
			if (!uuid[i]) {
				r = 0 | Math.random() * 16;
				uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
			}
		}
	}

	return uuid.join('');

};