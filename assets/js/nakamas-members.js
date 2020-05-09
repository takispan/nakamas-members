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

  // Tab reload test
  // var profile = "/profile"
  // $('#danceGroups').click(function(){
  //   $('#ds-dance-groups').html(ajax_load).load(profile);
  // });

  //$('#nkms-tabs').tabs();

  /*
   * DANCERS
   */
  // Add dancer to dance school list of dancers
  $('form#add-dancers').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
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
        window.location.reload();
        document.getElementById("danceSchool").click();
        document.getElementById("dancers").click();
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Pass data to populate single dancer tab
  $('.single-dancer').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');
    console.log(single_dancer_id);

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

  // Remove dancer from dance school list of dancers
  $('.remove-dancer').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');
    console.log(single_dancer_id);

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_dancer',
        single_dancer_id: single_dancer_id,
      },
      success: function(response) {
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
      }
    });
  });

  // Change dancer status
  $('.change-dancer-status').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');
    console.log(single_dancer_id);

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_change_status',
        single_dancer_id: single_dancer_id,
      },
      success: function(response) {
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
      }
    });
  });

  /*
   * GROUPS
   */
  // Add group to dance school list of groups
  $('form#add-groups').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //get user input from form
    var group_name = $('#add_group_name').val();
    var group_type = $('#add_group_type').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_group',
        group_name: group_name,
        group_type: group_type,
      },
      success: function(response) {
        $('.success_msg').css('display','block');
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Pass data to populate single group tab
  $('.single-group').on('click', function(e) {
    var single_group_id = $(this).attr('data-group-id');
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_single_group',
        single_group_id: single_group_id,
      },
      success: function(response) {
        console.log("Response: " + response);
        dsOpenTab(e, 'ds-group-single');
      },
      error: function(response) {
        console.log(response);
      }
    });
    $('.ajax')[0].reset();
  });

  // Add dancer to dance group
  $('form#add-group-dancer').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //var url = $(this).attr('action');
    var group_dancer_to_add = $('#add_dancer_to_group').val();
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_group_dancer',
        dancer: group_dancer_to_add,
      },
      success: function(response) {
        $('.success_msg').css('display','block');
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Remove dancer from dance group
  $('form#remove-group-dancer').on('submit', function(e) {
    e.preventDefault();
    //reset info messages
    $('.success_msg').css('display','none');
    $('.error_msg').css('display','none');
    //var url = $(this).attr('action');
    var group_dancer_to_remove = $('#remove_dancer_from_group option').filter(':selected').val();
    console.log(group_dancer_to_remove);
    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_group_dancer',
        dancer: group_dancer_to_remove,
      },
      success: function(response) {
        $('.success_msg').css('display','block');
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        $('.error_msg').css('display','block');
        console.log(response);
      }
    });
  });

  // Change group status
  $('.change-group-status').on('click', function(e) {
    var group_id = $(this).attr('data-group-id');
    console.log(group_id);

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_group_change_status',
        group_id: group_id,
      },
      success: function(response) {
        console.log(response);
        window.location.reload();
      },
      error: function(response) {
        console.log(response);
      }
    });
  });



  // REGISTRATION
  // $("#account_role").change(function() {
  //   //e.preventDefault();
  //   var select_role = $('#account_role').val();
  //   console.log(select_role);
  //   if ( select_role == 'dance-school') {
  //     $('#ds-reg-fields').css('display','block');
  //   }
  //   else {
  //     $('#ds-reg-fields').show();
  //   }
  // });
  // // Custom registration
  // $('#submit_registration').on('submit', function() {
  //   e.preventDefault();
  //   var select_role = $('#select_role option').filter(':selected').val();
  //   console.log(select_role);
  //
  //   $.ajax({
  //     _ajax_nonce: nkms_ajax.nonce,
  //     url: nkms_ajax.ajax_url,
  //     type: "POST",
  //     data: {
  //       action: 'ds_update_registration_fields',
  //       role: select_role,
  //     },
  //     success: function(response) {
  //       $('#ds-reg-fields').css('display','block');
  //       console.log(response);
  //     },
  //     error: function(response) {
  //       $('#ds-reg-fields').css('display','none');
  //       console.log(response);
  //     }
  //   });
  // });

});
