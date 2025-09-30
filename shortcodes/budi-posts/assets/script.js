jQuery(document).ready(function(){
    jQuery(".post-filter input").change(function(i){
        apply_post_filters()
    })
})

function apply_post_filters(){

    // Check, ob mindestens eine Checkbox aktiviert ist, dies aktiviert das filtering, sonst werden alle Posts angezeigt
    var apply = 0
    var filter_taxonomy
    var filter_terms = new Array
    jQuery(".post-filter input").each(function(){
        filter_taxonomy = jQuery(this).data('taxonomy')
        if( this.checked ){
            apply = 1
            filter_terms.push( this.value )
        }
    })

    if( !apply ){
        console.log("No Filter, show all")
        show_all_posts()
        return
    }

    console.log("Filter")
    console.log(filter_taxonomy)
    console.log(filter_terms)

    jQuery(".post-list > div > div").each(function(){
        var post_term_ids = jQuery(this).data(filter_taxonomy)

        post_term_ids = String(post_term_ids).split(",")

        console.log( post_term_ids )

        show = 0

        for(i = 0 ; i < filter_terms.length ; i++ ){
            for(j = 0 ; j < post_term_ids.length ; j++ ){

                if( filter_terms[i] == post_term_ids[j] ){
                    show = 1
                }

            }
        }

        if( !show ){
            jQuery(this).slideUp()
        }else{
            jQuery(this).slideDown()
        }
    })


}

function show_all_posts(){
    jQuery(".post-list > div > div").slideDown()
}
