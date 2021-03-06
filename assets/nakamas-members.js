
// jQuery
jQuery(document).ready(function($) {

  // datepicker
  $("#datepicker").datepicker({
    dateFormat: "dd/mm/yy",
    hideIfNoPrevNext: true,
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
  });

  // Remember tab after page reload ( only on webkit based browsers )
  isWebkit = /(safari|chrome)/.test(navigator.userAgent.toLowerCase());
  if ( isWebkit ){
    $('.tabs > input').on('change', function(e) {
      var tabLink = $( this ).attr('id');
      console.log("activeTab: " + tabLink);
      sessionStorage.setItem('activeTab', tabLink);
  	});
    $('.ds-tabs > input').on('change', function(e) {
      var ds_tabLink = $( this ).attr('id');
      console.log("active dsTab: " + ds_tabLink);

      sessionStorage.setItem('activedsTab', ds_tabLink);
    });
    // Retrieve last activeTab
  	activeTab = sessionStorage.getItem('activeTab');
    $('label[for="' + activeTab + '"]').click();
    if ( activeTab == 'dance-school' ) {
      active_dsTab = sessionStorage.getItem('activedsTab');
      $('label[for="' + active_dsTab + '"]').click();
    }
  }

  // Go back to profile after uploading pfp
  $('#wpua-upload-existing').on('click', function(e) {
    // $('#top-tabs a[href="#profile"]').tab('show');
  });

  // WOO - select all dancers
  $('#select-all-dancers').click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
  });

  // dancer leaves dance school
  $('#dancer_leave_dance_school_button').click(function(){
    $('#dancer_leave_dance_school_confirmation').show();
  });

  /*
   * DANCER LEAVES DANCE SCHOOL
  **/
  $('form#dancer_leave_dance_school').on('submit', function(e) {
    e.preventDefault();
    var dancer_id = $('input[name=dancer_leave_dance_school_dancer_id]').val();
    var dance_school_id = $( 'input[name=dancer_leave_dance_school_dance_school_id]' ).val();
    var submit = $(this).find("input[type=submit]:focus").val();

    if ( submit == 'Stay' ) {
      $('#dancer_leave_dance_school_confirmation').hide();
    }

    if ( submit == 'Leave' ) {
      $.ajax({
        _ajax_nonce: nkms_ajax.nonce,
        url: nkms_ajax.ajax_url,
        type: "POST",
        data: {
          action: 'dancer_leaves_dance_school',
          dancer_leaves_dance_school_ds_id: dance_school_id,
          dancer_leaves_dance_school_dancer_id: dancer_id,
        },
        success: function(response) {
          $('form#dancer_leave_dance_school .ajax-response').html( response.data );
          setTimeout(function(){
            $('form#dancer_leave_dance_school .ajax-response').slideUp();
            window.location.reload();
          }, 3000);
        },
        error: function(response) {
          $('form#dancer_leave_dance_school .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
          setTimeout(function(){
            $('form#dancer_leave_dance_school .ajax-response').slideUp();
            window.location.reload();
          }, 3000);
        }
      });
    }
  });

  /*
   * INVITE SYSTEM
  **/
  // Dancer requests to join a dance school
  $('form#dancer_requests_to_join_dance_school').on('submit', function(e) {
    e.preventDefault();

    var dance_school_id = $( 'select[name=dancer_request_to_join_dance_school_id]' ).val();
    var dancer_id = $('input[name=dancer_request_to_join_dancer_id]').val();

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'dancer_requests_to_join_dance_school',
        dancer_requests_to_join_dance_school_ds_id: dance_school_id,
        dancer_requests_to_join_dance_school_dancer_id: dancer_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('form#dancer_requests_to_join_dance_school .ajax-response').html( response.data );
        $('form#dancer_requests_to_join_dance_school .ajax-response').show();
        setTimeout(function(){
          $('form#dancer_requests_to_join_dance_school .ajax-response').slideUp();
        }, 3000);
      },
      error: function(response) {
        $('.loader').hide();
        $('form#dancer_requests_to_join_dance_school .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    });
  });

  // Dancer accepts / declines dance school invite
  $('#dancer_pending_invites').submit( function(e) {
    e.preventDefault();
    var dancer_id = $('input[name=dancer_invited_dancer_id]').val();
    var dance_school_id = $( 'input[name=dancer_invited_dance_school_id]' ).val();
    var submit = $(this).find("input[type=submit]:focus").val();

    // loader
    $('.loader').css('display','flex');

    // if dance school is accepted
    if ( submit == 'Accept' ) {
      $.ajax({
        _ajax_nonce: nkms_ajax.nonce,
        url: nkms_ajax.ajax_url,
        type: "POST",
        data: {
          action: 'dancer_accepts_dance_school_invite',
          dancer_accepts_dance_school_invite_dancer_id: dancer_id,
          dancer_accepts_dance_school_invite_ds_id: dance_school_id,
        },
        success: function(response) {
          $('.loader').hide();
          $('form#dancer_pending_invites .ajax-response').html( response.data );
          $('form#dancer_pending_invites .ajax-response').show();
          setTimeout(function(){
            $('form#dancer_pending_invites .ajax-response').slideUp();
          }, 3000);
          window.location.reload();
        },
        error: function(response) {
          $('.loader').hide();
          $('form#dancer_pending_invites .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
        }
      });
    }
    // if dance school is declined
    if ( submit == 'Decline' ) {
      $.ajax({
        _ajax_nonce: nkms_ajax.nonce,
        url: nkms_ajax.ajax_url,
        type: "POST",
        data: {
          action: 'dancer_declines_dance_school_invite',
          dancer_declines_dance_school_invite_dancer_id: dancer_id,
          dancer_declines_dance_school_invite_ds_id: dance_school_id,
        },
        success: function(response) {
          $('.loader').hide();
          $('form#dancer_pending_invites .ajax-response').html( response.data );
          $('form#dancer_pending_invites .ajax-response').show();
          setTimeout(function(){
            $('form#dancer_pending_invites .ajax-response').slideUp();
          }, 3000);
          window.location.reload();
        },
        error: function(response) {
          $('.loader').hide();
          $('form#dancer_pending_invites .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
        }
      });
    }
  });

  // Dance School accepts / declines dancer request to join
  $('#dance_school_pending_memberships').submit( function(e) {
    e.preventDefault();
    var dancer_id = $('input[name=dance_school_request_to_join_dancer_id]').val();
    var dance_school_id = $( 'input[name=dance_school_request_to_join_ds_id]' ).val();
    var submit = $(this).find("input[type=submit]:focus").val();

    // loader
    $('.loader').css('display','flex');

    // if dancer is accepted
    if ( submit == 'Accept' ) {
      $.ajax({
        _ajax_nonce: nkms_ajax.nonce,
        url: nkms_ajax.ajax_url,
        type: "POST",
        data: {
          action: 'dance_school_accepts_dancer_invite',
          dance_school_accepts_dancer_invite_dancer_id: dancer_id,
          dance_school_accepts_dancer_invite_ds_id: dance_school_id,
        },
        success: function(response) {
          $('.loader').hide();
          $('form#dance_school_pending_memberships .ajax-response').html( response.data );
          $('form#dance_school_pending_memberships .ajax-response').show();
          setTimeout(function(){
            $('form#dance_school_pending_memberships .ajax-response').slideUp();
          }, 3000);
          window.location.reload();
        },
        error: function(response) {
          $('.loader').hide();
          $('form#dance_school_pending_memberships .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
        }
      });
    }
    // if dancer is declined
    if ( submit == 'Decline' ) {
      $.ajax({
        _ajax_nonce: nkms_ajax.nonce,
        url: nkms_ajax.ajax_url,
        type: "POST",
        data: {
          action: 'dance_school_declines_dancer_invite',
          dance_school_declines_dancer_invite_dancer_id: dancer_id,
          dance_school_declines_dancer_invite_ds_id: dance_school_id,
        },
        success: function(response) {
          $('.loader').hide();
          $('form#dance_school_pending_memberships .ajax-response').html( response.data );
          $('form#dance_school_pending_memberships .ajax-response').show();
          setTimeout(function(){
            $('form#dance_school_pending_memberships .ajax-response').slideUp();
          }, 3000);
          window.location.reload();
        },
        error: function(response) {
          $('.loader').hide();
          $('form#dance_school_pending_memberships .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
        }
      });
    }
  });

   // Guardian requests to manage dancer
   $('form#guardian-add-dancer-to-manage').on('submit', function(e) {
     e.preventDefault();
     var dancer_id = $('input[name=guardian_manage_dancer_dancer_id]').val();
     var guardian_id = $('input[name=guardian_manage_dancer_guardian_id]').val();

     // loader
     $('.loader').css('display','flex');

     $.ajax({
       _ajax_nonce: nkms_ajax.nonce,
       url: nkms_ajax.ajax_url,
       type: "POST",
       data: {
         action: 'guardian_invite_to_manage_dancer',
         guardian_manage_dancer_dancer_id: dancer_id,
         guardian_manage_dancer_guardian_id: guardian_id,
       },
       success: function(response) {
          $('.loader').hide();
          $('form#guardian-add-dancer-to-manage .ajax-response').html( response.data );
          $('form#guardian-add-dancer-to-manage .ajax-response').show();
          setTimeout(function(){
            $('form#guardian-add-dancer-to-manage .ajax-response').slideUp();
          }, 3000);
       },
       error: function(response) {
         $('form#guardian-add-dancer-to-manage .ajax-response').html( response.data );
       }
     });
   });

   // Dancer accepts / declines guardian invite
   $('#dancer_pending_invites_guardian').submit( function(e) {
     e.preventDefault();
     var dancer_id = $('input[name=guardian_invite_dancer_id]').val();
     var guardian_id = $( 'input[name=guardian_invite_guardian_id]' ).val();
     var submit = $(this).find("input[type=submit]:focus").val();

     // loader
     $('.loader').css('display','flex');

     // if guardian is accepted
     if ( submit == 'Accept' ) {
       $.ajax({
         _ajax_nonce: nkms_ajax.nonce,
         url: nkms_ajax.ajax_url,
         type: "POST",
         data: {
           action: 'dancer_accepts_guardian_invite',
           dancer_accepts_guardian_invite_dancer_id: dancer_id,
           dancer_accepts_guardian_invite_guardian_id: guardian_id,
         },
         success: function(response) {
           $('.loader').hide();
           $('form#dancer_pending_invites_guardian .ajax-response').html( response.data );
           $('form#dancer_pending_invites_guardian .ajax-response').show();
           setTimeout(function(){
             $('form#dancer_pending_invites_guardian .ajax-response').slideUp();
           }, 3000);
           window.location.reload();
         },
         error: function(response) {
           $('.loader').hide();
           $('form#dancer_pending_invites_guardian .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
         }
       });
     }
     // if guardian is declined
     if ( submit == 'Decline' ) {
         console.log('Declined');
       $.ajax({
         _ajax_nonce: nkms_ajax.nonce,
         url: nkms_ajax.ajax_url,
         type: "POST",
         data: {
           action: 'dancer_declines_guardian_invite',
           dancer_declines_guardian_invite_dancer_id: dancer_id,
           dancer_declines_guardian_invite_guardian_id: guardian_id,
         },
         success: function(response) {
           $('.loader').hide();
           $('form#dancer_pending_invites_guardian .ajax-response').html( response.data );
           $('form#dancer_pending_invites_guardian .ajax-response').show();
           setTimeout(function(){
             $('form#dancer_pending_invites_guardian .ajax-response').slideUp();
           }, 3000);
           window.location.reload();
         },
         error: function(response) {
           $('.loader').hide();
           $('form#dancer_pending_invites_guardian .ajax-response').html( '<p class="text-danger">An error occured. Please try again.</p>' );
         }
       });
     }
   });


  /*
   * UPDATE PROFILE
  **/
  $('form#update_profile').on('submit', function(e) {
    e.preventDefault();

    // loader
    $('.loader').css('display','flex');

    // Basic fields
    var user_id = $('input[name=update_profile_user_id]').val();
    var email = $('input[name=update_profile_email]').val();
    var phone_number = $('input[name=update_profile_phone_number]').val();
    var city = $('input[name=update_profile_city]').val();
    var address = $('input[name=update_profile_address]').val();
    var postcode = $('input[name=update_profile_postcode]').val();
    // Guardian (if underage)
    var guardian_name = $('input[name=update_profile_guardian_name]').val();
    var guardian_phone_number = $('input[name=update_profile_guardian_phone_number]').val();
    var guardian_email = $('input[name=update_profile_guardian_email]').val();
    // Dancer
    var dancer_ds_name = $('input[name=update_profile_dancer_ds_name]').val();
    var dancer_ds_teacher_name = $('input[name=update_profile_dancer_ds_teacher_name]').val();
    var dancer_ds_teacher_email = $('input[name=update_profile_dancer_ds_teacher_email]').val();
    // Dance school
    var dance_school_name = $('input[name=update_profile_dance_school_name]').val();
    var dance_school_address = $('input[name=update_profile_dance_school_address]').val();
    var dance_school_phone_number = $('input[name=update_profile_dance_school_phone_number]').val();
    var dance_school_description = $('input[name=update_profile_dance_school_description]').val();

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'user_update_profile',
        update_profile_user_id: user_id,
        update_profile_email: email,
        update_profile_phone_number: phone_number,
        update_profile_city: city,
        update_profile_address: address,
        update_profile_postcode: postcode,
        update_profile_guardian_name: guardian_name,
        update_profile_guardian_phone_number: guardian_phone_number,
        update_profile_guardian_email: guardian_email,
        update_profile_dancer_ds_name: dancer_ds_name,
        update_profile_dancer_ds_teacher_name: dancer_ds_teacher_name,
        update_profile_dancer_ds_teacher_email: dancer_ds_teacher_email,
        update_profile_dance_school_name: dance_school_name,
        update_profile_dance_school_address: dance_school_address,
        update_profile_dance_school_phone_number: dance_school_phone_number,
        update_profile_dance_school_description: dance_school_description,
      },
      success: function(response) {
        $('.loader').hide();
        $('form#update_profile .ajax-response').html( response.data );
        $('form#update_profile .ajax-response').show();
        setTimeout(function(){
          $('form#update_profile .ajax-response').slideUp();
        }, 3000);
      },
      error: function(response) {
        $('.loader').hide();
        $('form#update_profile .ajax-response').html( '<p class="text-danger">Error getting dancer data. Please try again.</p>' );
      }
    });
  });

  /*
   * DANCERS
   */

  // DANCE SCHOOL - send invite to dancer
  $('form#add-dancers').on('submit', function(e) {
    e.preventDefault();
    var dancer_id = $('input[name=dance_school_add_dancers_dancer_id]').val();
    var dance_school_id = $('input[name=dance_school_add_dancers_ds_id]').val();

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_dancer',
        ds_add_dancer_id: dancer_id,
        ds_add_dancer_dance_school_id: dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('form#add-dancers .ajax-response').html( response.data );
        $('form#add-dancers .ajax-response').show();
        setTimeout(function(){
          $('form#add-dancers .ajax-response').slideUp();
        }, 3000);
        // $(".ds-add-dancers-link").click( function() { $('label[for="ds-add-dancers"]').click(); });
      },
      error: function(response) {
        $('.loader').hide();
        $('form#add-dancers .ajax-response').html( response.data );
      }
    });
  });

  // Pass data to populate single dancer tab
  $('.single-dancer').on('click', function(e) {
    var single_dancer_id = $(this).attr('data-dancer-id');
    var dance_school_id = $(this).attr('data-ds-id');

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_single_dancer',
        single_dancer_id: single_dancer_id,
        dance_school_id: dance_school_id,
      },
      success: function(response) {
        $('.ds-single-dancer').html( response.data );
        $('.loader').hide();
        window.location.reload();
      },
      error: function(response) {
        $('.loader').hide();
        $('.ds-single-dancer').html( '<p class="text-danger">Error getting dancer data. Please try again.</p>' );
      }
    });
  });

  // Change dancer status
  $('#change-dancer-status').on('click', function(e) {
    var change_dancer_status_dancer_id = $(this).attr('data-dancer-id');

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_change_status',
        change_dancer_status_dancer_id: change_dancer_status_dancer_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('.ds-single-dancer .ajax-response').html( response.data );
        $('.ds-single-dancer .ajax-response').show();
        setTimeout(function(){
          $('.ds-single-dancer .ajax-response').slideUp();
          window.location.reload();
        }, 3000);
      },
      error: function(response) {
        $('.loader').hide();
        $('.ds-single-dancer .ajax-response').html( '<p class="text-danger">An error occured. Dancer status was not changed.</p>' );
      }
    });
  });

  // Remove dancer from dance school list of dancers
  $('#remove-dancer-from-dancers-list').on('click', function(e) {
    var remove_dancer_single_dancer_id = $(this).attr('data-dancer-id');
    var remove_dancer_dance_school_id = $(this).attr('data-ds-id');

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_dancer',
        remove_dancer_single_dancer_id: remove_dancer_single_dancer_id,
        remove_dancer_dance_school_id: remove_dancer_dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('.ds-single-dancer .ajax-response').html( response.data );
        setTimeout(function(){
          $('.ds-single-dancer .ajax-response').slideUp();
        }, 3000);
        // $('label[for="ds-dancers"]').click();
        // window.location.reload();
      },
      error: function(response) {
        $('.loader').hide();
        $('.ds-single-dancer .ajax-response').html( '<p class="text-danger">An error occured. Dancer was not removed.</p>' );
        setTimeout(function(){
          $('.ds-single-dancer .ajax-response').slideUp();
        }, 3000);
      }
    });
  });

  /*
   * GROUPS
   */
  // Add group to dance school list of groups
  $('form#add-groups').on('submit', function(e) {
    e.preventDefault();
    var group_name = $('#add_group_name').val();
    var group_type = $('#add_group_type').val();
    var group_level = $('#add_group_change_level_category_of_group').val();
    var ds_id = $('input[name=dance_school_add_groups_submit_ds_id]').val();

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_group',
        add_group_group_name: group_name,
        add_group_group_type: group_type,
        add_group_group_level: group_level,
        add_group_dance_school_id: ds_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('#add-groups .ajax-response').html( response.data );
        $('#add-groups .ajax-response').show();
        setTimeout(function(){
          $('#add-groups .ajax-response').slideUp();
        }, 3000);
      },
      error: function(response) {
        $('.loader').hide();
        $('#add-groups .ajax-response').html('<p class="text-danger">An error occured, group not added.</p>');
      }
    });
  });

  // Pass data to populate single group tab
  $('.single-group').on('click', function(e) {
    var single_group_id = $(this).attr('data-group-id');
    var dance_school_id = $(this).attr('data-ds-id');

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_single_group',
        single_group_id: single_group_id,
        dance_school_id: dance_school_id,
      },
      success: function(response) {
        $('.ds-single-group').html( response.data );
        $('.loader').hide();
        window.location.reload();
      },
      error: function(response) {
        $('.ds-single-group').html( response.data );
      }
    });
  });

  // Change group status
  $('.change-group-status').on('click', function(e) {
    var ds_group_change_status_group_id = $(this).attr('data-group-id');
    var ds_group_change_status_dance_school_id = $(this).attr('data-ds-id');

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_group_change_status',
        ds_group_change_status_group_id: ds_group_change_status_group_id,
        ds_group_change_status_dance_school_id: ds_group_change_status_dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('.group-details .ajax-response').html( response.data );
        setTimeout(function(){
          $('.group-details .ajax-response').slideUp();
          window.location.reload();
        }, 3000);
      },
      error: function(response) {
        $('.group-details .ajax-response').html( '<p class="text-danger">An error occured, please try again later.</p>' );
      }
    });
  });

  // Remove group
  $('.remove-group').on('click', function(e) {
    var ds_group_remove_group_id = $(this).attr('data-group-id');
    var ds_group_remove_dance_school_id = $(this).attr('data-ds-id');

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_group',
        ds_remove_group_group_id: ds_group_remove_group_id,
        ds_remove_group_dance_school_id: ds_group_remove_dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('.group-details .ajax-response').html( response.data );
        $('.group-details .ajax-response').show();
        setTimeout(function(){
          $('.group-details .ajax-response').slideUp();
          $('label[for="ds-dance-groups"]').click();
          window.location.reload();
        }, 3000);
      },
      error: function(response) {
        $('.group-details .ajax-response').html( '<p class="text-danger">An error occured, please try again later.</p>' );
      }
    });
  });

  // Change level category of group
  $('form#change-group-level-category').on('submit', function(e) {
    e.preventDefault();
    var level_category = $('#change_level_category_of_group').val();
    var dance_school_id = $('input[name=dance_school_group_change_level_category_dance_school_id]').val();

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_group_change_level_category',
        group_change_level_category: level_category,
        group_change_level_category_dance_school_id: dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('#change-group-level-category .ajax-response').html( response.data );
        $('#change-group-level-category .ajax-response').show();
        setTimeout(function(){
          $('#change-group-level-category .ajax-response').slideUp();
        }, 3000);
      },
      error: function(response) {
        $('#change-group-level-category .ajax-response').html( response.data );
      }
    });
  });

  // Add dancer to dance group
  $('form#add-group-dancer').on('submit', function(e) {
    e.preventDefault();
    var dancer_id = $('#add_dancer_to_group').val();
    var dance_school_id = $('input[name=dance_school_group_add_dancers_dance_school_id]').val();

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_add_group_dancer',
        dancer_id: dancer_id,
        dance_school_id: dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('#add-group-dancer .ajax-response').html( response.data );
        $('#add-group-dancer .ajax-response').show();
        setTimeout(function(){
          $('#add-group-dancer .ajax-response').slideUp();
        }, 3000);
      },
      error: function(response) {
        $('#add-group-dancer .ajax-response').html( response.data );
      }
    });
  });

  // Remove dancer from dance group
  $('form#remove-group-dancer').on('submit', function(e) {
    e.preventDefault();
    var dancer_id = $('#remove_dancer_from_group').val();
    var dance_school_id = $('input[name=dance_school_group_remove_dancers_dance_school_id]').val();

    // loader
    $('.loader').css('display','flex');

    $.ajax({
      _ajax_nonce: nkms_ajax.nonce,
      url: nkms_ajax.ajax_url,
      type: "POST",
      data: {
        action: 'ds_remove_group_dancer',
        dancer_id: dancer_id,
        dance_school_id: dance_school_id,
      },
      success: function(response) {
        $('.loader').hide();
        $('#remove-group-dancer .ajax-response').html( response.data );
        setTimeout(function(){
          $('#remove-group-dancer .ajax-response').slideUp();
          window.location.reload();
        }, 3000);
      },
      error: function(response) {
        $('#remove-group-dancer .ajax-response').html( response.data );
      }
    });
  });

  /*
   * TEACHERS
   */
   // Add a teacher in order to manage dance school account
   $('form#add-teachers').on('submit', function(e) {
     e.preventDefault();
     var teacher_id = $('input[name=dance_school_add_teachers]').val();
     var dance_school_id = $('input[name=dance_school_add_teachers_ds_id]').val();

     $.ajax({
       _ajax_nonce: nkms_ajax.nonce,
       url: nkms_ajax.ajax_url,
       type: "POST",
       data: {
         action: 'ds_add_teacher',
         teacher_id: teacher_id,
         dance_school_id: dance_school_id,
       },
       success: function(response) {
          $('form#add-teachers .ajax-response').html( response.data );
       },
       error: function(response) {
         $('form#add-teachers .ajax-response').html( response.data );
       }
     });
   });

  $("#select_role").val("").change();
  // REGISTRATION
  $("#select_role").change(function() {
    // e.preventDefault();
    var selRole = $('#select_role').val();
    console.log(selRole);
    if ( selRole === 'dance-school') {
      $('#ds-reg-fields-dancer').hide();
      $('#ds-reg-fields-dance-school').show();
    }
    else if ( selRole === 'dancer') {
      $('#ds-reg-fields-dance-school').hide();
      $('#ds-reg-fields-dancer > .dancer').show();
      $('#ds-reg-fields-dancer > h6').html( 'Dancer & Teacher Details' );
      $('#ds-reg-fields-dancer').show();
    }
    else if ( selRole === 'teacher') {
      $('#ds-reg-fields-dance-school').hide();
      $('#ds-reg-fields-dancer > .dancer').hide();
      $('#ds-reg-fields-dancer > h6').html( 'Teacher Details' );
      $('#ds-reg-fields-dancer').show();
    }
    else {
      $('#ds-reg-fields-dance-school').hide();
      $('#ds-reg-fields-dancer').hide();
    }
  });

  $("#datepicker").change(function() {
    var dob = $('#datepicker').val();
    var dobDate = $.datepicker.parseDate("dd/mm/yy",dob);
    var tmpToday = $.datepicker.formatDate("dd/mm/yy", new Date());
    var today = $.datepicker.parseDate("dd/mm/yy",tmpToday);
    var age = Math.floor((today-dobDate) / (365.25 * 24 * 60 * 60 * 1000));
    if ( age < 18 ) {
      $('#ds-reg-fields-dancer-guardian').show();
    }
    else {
      $('#ds-reg-fields-dancer-guardian').hide();
    }
    console.log(dobDate);
    console.log(today);
    console.log(age);
  });

  // TABS

  // profile
  $(".pfp-link").click( function() {
    $('label[for="pfp"]').click();
  });

  // ds-details
  // $(".ds-details-link").click( function() {
  //   $('label[for="ds-details"]').click();
  // });

  $(".ds-add-dancers-link").click( function() {
    $('label[for="ds-add-dancers"]').click();
  });

  // single dancer
  $(".single-dancer").click( function() {
    $('label[for="ds-dancer-single"]').click();
  });

  // add group
  $(".ds-add-groups-link").click( function() {
    $('label[for="ds-add-groups"]').click();
  });

  // single group
  $(".single-group").click( function() {
    $('label[for="ds-group-single"]').click();
  });

  // change level category of group
  $(".ds-group-change-level-category-link").click( function() {
    $('label[for="ds-group-change-level-category"]').click();
  });

  // add remove dancers from group
  $(".ds-group-add-dancers-link").click( function() {
    $('label[for="ds-group-add-dancers"]').click();
  });
  $(".ds-group-remove-dancers-link").click( function() {
    $('label[for="ds-group-remove-dancers"]').click();
  });

  $(".ds-add-teachers-link").click( function() {
    $('label[for="ds-add-teachers"]').click();
  });

});
