jQuery(document).ready(function($){

    /*
    * Sort Post Types
    */
    $('table.wp-list-table #the-list').sortable({
        'items': 'tr',
        'axis': 'y',
        'update': function() {

            const post_type = SORT.post_type,
                  new_list = [];


            $('#the-list tr').each(function(){
                new_list.push($(this).attr('id').replace('post-', ''));
            });

            const data = {'action': 'update-post-type-order', 'post_type': post_type, 'new_list': new_list};

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: data,
                cache: false,
                dataType: "html"
            });
        }
    }).find('tr').css({
        cursor: 'move'
    });

});