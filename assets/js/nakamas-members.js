function openTab(evt, tabName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";

}

function dsOpenTab(evt, dsTabName) {
  // Declare all variables
  var i, dstabcontent, dstablinks;

  // Get all elements with class="ds-tabcontent" and hide them
  dstabcontent = document.getElementsByClassName("ds-tabcontent");
  for (i = 0; i < dstabcontent.length; i++) {
    dstabcontent[i].style.display = "none";
  }

  // Get all elements with class="ds-tablinks" and remove the class "active"
  dstablinks = document.getElementsByClassName("ds-tablinks");
  for (i = 0; i < dstablinks.length; i++) {
    dstablinks[i].className = dstablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(dsTabName).style.display = "block";
  evt.currentTarget.className += " active";

}

// Default tab
document.getElementById("defaultOpen").click();
document.getElementById("dsDefaultOpen").click();

// jQuery
jQuery(document).ready(function($) {
  var data = {
		'action': 'my_action',
		'vara': nkms_ajax.vara,      // We pass php values differently!
    'varb': nkms_ajax.varb
	};

  console.log(data.vara)
  	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post(nkms_ajax.ajax_url, data, function(response) {
		alert('Got this from the server: ' + response);
	});
});
