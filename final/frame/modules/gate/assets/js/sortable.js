jQuery(document).ready(function($){

    /*
    * Sort Post Types
    */
    $('.posts #the-list, .pages #the-list').sortable({
        'items': 'tr',
        'axis': 'y',
        'update': function() {

            const post_type = SORT.post_type,
                  new_list = [];


            $('#the-list tr').each(function(){new_list.push($(this).attr('id').replace('post-', ''));});


            $.post(ajaxurl, {'action': 'update-post-type-order', 'post_type': post_type, 'new_list': new_list});

        }
    }).find('tr').css({
        cursor: 'move'
    });


    /*
    * Sort terms
    
    $('.tags #the-list').sortable({
        'items': 'tr',
        'axis': 'y',
        'update': function() {

            const taxonomy = SORT.taxonomy,
                  new_list = [];


            $('#the-list tr').each(function(){new_list.push($(this).attr('id').replace('tag-', ''));});


            $.post(ajaxurl, {'action': 'update-terms-order', 'taxonomy': taxonomy, 'new_list': new_list}, function(data){console.log(data)});

        }
    }).find('tr').css({
        cursor: 'move'
    });
    */

});