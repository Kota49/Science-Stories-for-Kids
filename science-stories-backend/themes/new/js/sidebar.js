$(document).ready(function() {
	changeActiveClass();
	$(document).on('click', '.header-section .toggle-btn', function() {
		changeActiveClass();
	})
	function changeActiveClass() {
		if ($('body').hasClass('sidebar-collapsed')) {
			var menu = $('.side-navigation .menu-list.active');
			if (menu.length >= 1) {
				menu.removeClass('active');
				menu.addClass('inactive');
			}
		} else {
			var menu = $('.side-navigation .menu-list.inactive');
			if (menu.length >= 1) {
				menu.removeClass('inactive');
				menu.addClass('active');
			}
		}
	}
});
$(".sub-menu-list > a").click(function(e) {
	e.preventDefault();
	$('.sub-menu-list').removeClass('active');
	$(".sub-menu-list > ul").slideUp(300)
	if ($(this).next("ul").is(":hidden")) {
		$(this).next("ul").slideDown(300)
		$(this).closest('.sub-menu-list').addClass('active');
	} else {
		$(this).next("ul").slideUp(300)
	}
});


