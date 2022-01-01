const getUrlParameter = function getUrlParameter(sParam) {
    let sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if(sParameterName[0] === sParam)
            return sParameterName[1] === undefined ? true : sParameterName[1];
    }
};

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

            let paged = getUrlParameter('paged');


            $('#the-list tr').each(function(){
                new_list.push($(this).attr('id').replace('post-', ''));
            });

            if(typeof paged === 'undefined') paged = 1;

            const data = {'action': 'update-post-type-order', 'post_type': post_type, 'new_list': new_list ,'paged': paged};
           
            //$.post(window.location.href, {data: data}, function(data){console.log(data);});

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: data,
                cache: false,
                dataType: "html",
                success: function(data){
                    console.log(data);
                },
                error: function(html){
                    console.log(html);
                }
            });
        }
    }).find('tr').css({
        cursor: 'move'
    });

});