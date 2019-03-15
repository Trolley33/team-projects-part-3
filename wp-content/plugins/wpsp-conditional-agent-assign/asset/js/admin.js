
function wpsp_aut_item_select(obj){

    wpspjq('.wpsp-autocomplete-result-item').removeClass('wpsp-autocomplete-result-item-selected');
    wpspjq(obj).addClass('wpsp-autocomplete-result-item-selected');
}

function wpsp_aut_choose_item(obj){

    var agent_id    = parseInt(wpspjq(obj).attr('id'));
    var agent_name  = wpspjq(obj).text();
    wpspjq('#wpsp-agent-id').val(agent_id);
    wpspjq('#wpsp-agent-label').text(agent_name);
    wpspjq('.wpsp-autocomplete-drop-down-panel').hide();
}

function wpsp_search_keypress(evt,t){

    if( evt.keyCode == 40 ){
        if(wpspjq('.wpsp-autocomplete-result-item-selected').next().is('.wpsp-autocomplete-result-item')){
            wpsp_aut_item_select(wpspjq('.wpsp-autocomplete-result-item-selected').next());
        }
        return;
    }

    if( evt.keyCode == 38 ){
        if(wpspjq('.wpsp-autocomplete-result-item-selected').prev().is('.wpsp-autocomplete-result-item')){
            wpsp_aut_item_select(wpspjq('.wpsp-autocomplete-result-item-selected').prev());
        }
        return;
    }

    if( evt.keyCode == 13 ){
        wpsp_aut_choose_item( wpspjq('.wpsp-autocomplete-result-item-selected') );
        return;
    }

    wpsp_search_users_for_add_agent(t.value);

}

function wpsp_search_users_for_add_agent( s = '' ){

    var data = {
        'action': 'search_agents_for_caa',
        's': s,
        'nonce' : wpspjq('input[name="nonce"]').val()
    };

    wpspjq.post(wpsp_caa_data.ajax_url, data, function(response) {
        wpspjq('#wpsp-autocomplete-search-results').html(response);
    });
}


function wpsp_add_user_tbl(e){
    select_user_id=wpspjq('[name="wpsp_agent[agent_id]"]').val();

    if(select_user_id!=""){
        //get selected agent id list
        var select_user_array=new Array();
        wpspjq('#wpsp_selected_agents tbody tr').find('input[name="wpsp_caa_rule[agents][]"]').each(function (){
            select_user_array.push(wpspjq(this).val());
        });
        //check selected agent id is already exists
        if( !(wpspjq.inArray(select_user_id,select_user_array) > -1)){
            select_user_name=wpspjq('#wpsp-agent-label').html();
            var markup = "<tr><td>" + select_user_name + "<input type='hidden' name='wpsp_caa_rule[agents][]' value='"+select_user_id+"'/> </td>"+
                    "<td><span class='delete-row'>Remove</span></td> </tr>";
            wpspjq("#wpsp_selected_agents tbody").append(markup);
        }
    }else{
        alert(wpsp_caa_data.agent_duplicate);
    }
    e.preventDefault();
}

function wpsp_caa_set_add_condition(){

  var field_key       = wpspjq('#wpsp_en_conditional_field').val();
  var field_key_label = wpspjq('#wpsp_en_conditional_field').find('option:selected').text();
  var field_val       = '';
  var field_val_label = '';
  var html_str        = '';

  if ( wpspjq('#wpsp_en_conditional_field_option').val().trim() != '' ) {
    field_val       = wpspjq('#wpsp_en_conditional_field_option').val().trim();
    field_val_label = wpspjq('#wpsp_en_conditional_field_option').find('option:selected').text();
  } else {
    field_val       = wpspjq('#wpsp_en_conditional_field_has_word').val().trim();
    field_val_label = wpspjq('#wpsp_en_conditional_field_has_word').val().trim();
  }

  if ( field_key !='' && field_val != '' ) {

    var duplicate_flag = false;
    var exist_items    = wpspjq('#wpsp_en_condition_container').find("input[name='wpsp_caa_rule[condition]["+field_key+"][]']");
    wpspjq(exist_items).each(function(index, el) {
      if( wpspjq(el).val().trim() == field_val ){
        duplicate_flag = true;
      }
    });
    if (duplicate_flag) {
      wpspjq('#wpsp_en_condition_add_container').hide();
      return;
    }

    html_str  += '<div class="wpsp_autocomplete_choice_item">';
    html_str  += '  '+ field_key_label +' = "'+ field_val_label +'" <span onclick="wpsp_autocomplete_choice_item_delete(this)" class="dashicons dashicons-no-alt wpsp_autocomplete_choice_item_delete"></span>';
    html_str  += '  <input name="wpsp_caa_rule[condition]['+ field_key +'][]" value="'+ field_val +'" type="hidden">';
    html_str  += '</div>';
    wpspjq('#wpsp_en_condition_container').append(html_str);
    wpspjq('#wpsp_en_condition_add_container').hide();
  }
}

function wpsp_caa_delete(obj){

  if(confirm("Are you sure to delete this condition?")){
  var delete_url = wpspjq(obj).data('href');
    window.location.href = delete_url;
  }

}
