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

// function singleDancer(e) {
//   console.log(e.getAttribute('data-dancer-id'));
//
// }

// Default tab
document.getElementById("defaultOpen").click();
document.getElementById("dsDefaultOpen").click();

// jQuery
jQuery(document).ready(function($) {

  // Add dancer to dance school list of dancers
  $('form#add-remove-dancers').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //var url = $(this).attr('action');
    var dancer_to_add = $('#add_dancer_to_ds').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_dancer',
        dancer: dancer_to_add,
      },
      success: function(response) {
        $('.success_msg').css('display','block');
        console.log(response);
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
    $('.ajax')[0].reset();
  });

  // Pass data to populate single dancer tab
  $('.single-dancer').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_single_dancer',
        single_dancer_id: single_dancer_id,
      },
      success: function(response) {
        console.log("Response: " + response);
        dsOpenTab(e, 'ds-dancer-single');
      },
      error: function(response) {
        console.log(response);
      }
    });
    $('.ajax')[0].reset();
  });

  //To be replaced
  // var postData = {
  //     'action': 'my_action',
  //     'test': "TinyMarioSaysHello",
  //     'the_issue_key': nkms_ajax.the_issue_key,
  //     'ds_add_dancer': $( '#add_dancer_to_ds' ).val()
  // }

  // $.ajax({
  //   type: "POST",
  //   data: postData,
  //   url: nkms_ajax.ajax_url,
  //   //This fires when the ajax 'comes back' and it is valid json
  //   success: function (response) {
  //     console.log("Success");
  //     console.log("Response was: " + response);
  //     //alert( response);
  //   }
  //   //This fires when the ajax 'comes back' and it isn't valid json
  //   }).fail(function (data) {
  //       console.log("Failure")
  //       console.log(data);
  //     });

	// jQuery.post(nkms_ajax.ajax_url, data,
  //   function(response) {
	// 	    alert('Got this from the server: ' + response);
	//      }
  //    );
});
