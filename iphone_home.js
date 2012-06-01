
jQuery(document).ready(function() {
		var stopChange = true;
	if (window.innerWidth && window.innerWidth <= 480) {
jQuery('#header ul').addClass('hide');
jQuery('#left ul').addClass('hide');
jQuery('#searchForm').addClass('hide');
jQuery('#header').append('<div class="leftButton" onclick="toggleMenu()">Menu</div>');
jQuery('#header').append('<div class="searchButton" onclick="toggleSearch()">Search</div>');
jQuery('#logoH').append('<a class="removeable" href="/index.php">Think College</a>');
jQuery('#picstripAlt').append('<img class="removeable" src="/templates/beez_home/images/picstrip5.jpg" alt="College Options for People with Intellectual Disabilities" />');
jQuery('#altTabs').append('<a class="removeable" href="/for-students/for-students">For Students</a><a class="removeable" href="/for-families/for-families">For Families</a><a class="removeable" href="/for-professionals/for-professionals">For Professionals</a>');
}
});
function toggleMenu() {
jQuery('#header ul').toggleClass('hide');
jQuery('#header .leftButton').toggleClass('pressed');
jQuery('#left ul').toggleClass('hide');
jQuery('#left .leftButton').toggleClass('pressed');
}
function toggleSearch() {
jQuery('#searchForm').toggleClass('hide');
jQuery('#header .searchButton').toggleClass('pressed');
}

function debouncer( func , timeout ) {
   var timeoutID , timeout = timeout || 20;
   return function () {
      var scope = this , args = arguments;
      clearTimeout( timeoutID );
      timeoutID = setTimeout( function () {
          func.apply( scope , Array.prototype.slice.call( args ) );
      } , timeout );
   }
}

jQuery( window ).resize( debouncer( function ( e )  {

	 if (window.innerWidth && window.innerWidth >= 481) {
	jQuery('.leftButton').remove();
	jQuery('.searchButton').remove();
	jQuery('.removeable').remove();
	stopChange = true;
	}
	
  if (window.innerWidth && window.innerWidth <= 480 ) {
	 if ( stopChange == true ) {
	jQuery('#header ul').addClass('hide');
jQuery('#left ul').addClass('hide');
jQuery('#searchForm').addClass('hide');
jQuery('#header').append('<div class="leftButton" onclick="toggleMenu()">Menu</div>');
jQuery('#header').append('<div class="searchButton" onclick="toggleSearch()">Search</div>');
jQuery('#logoH').append('<a class="removeable" href="/index.php">Think College</a>');
jQuery('#picstripAlt').append('<img class="removeable" src="/templates/beez_home/images/picstrip5.jpg" alt="College Options for People with Intellectual Disabilities" />');
jQuery('#altTabs').append('<a class="removeable" href="/for-students/for-students">For Students</a><a class="removeable" href="/for-families/for-families">For Families</a><a class="removeable" href="/for-professionals/for-professionals">For Professionals</a>');
stopChange = false; }
	}
}));

