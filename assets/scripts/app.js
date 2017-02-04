LFS = '' || {};

(function($){
  LFS.favourites = {
    init: function(){
      // console.log(currentUser);
      LFS.favourites.ajaxUpdate.init();
      LFS.favourites.convertSvgToInline.init();
    },
    ajaxUpdate: {
      init: function(){
        this.build();
      },
      build: function(){

        if ($('.add-favourite--btn').length) {

          $('.add-favourite--btn').each( function(){

            var _this = $(this),
                currentPost = _this.closest('.add-favourite').data('post'),
                target = '.lfs-favourites-widget-response';

            _this.on('click', function(event){

              if (_this.hasClass('active')) {
                return false;
              }

              $(this).addClass('active');

              LFS.favourites.ajaxUpdate.post(currentPost, currentUser);

              if ($(target).length) {
                LFS.favourites.ajaxUpdate.update(currentPost, target);

              }

            });

          });
        }
      },
      post: function(postId, userId){
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                'action': 'ajax_form',
                'post_id': postId,
                'user_id': userId
            },
            beforeSend: function() {
                // alert('before')
            },
            success: function( response ) {
        			// console.log(response);
        		},
            error: function(){
                // alert('error')
            },
        });
      },
      update: function(postId, target){
        $.ajax({
            type: "GET",
            url: "wp-json/wp/v2/posts/" + postId,
            data: {
                'action': 'ajax_get',
                'post_id': postId
            },
            beforeSend: function() {
                // alert('before')
            },
            success: function( xhr ) {
               $(target).prepend('<h5><a href="' + xhr.link + '">' + xhr.title.rendered + '</a></h5>');
            },
            error: function(){
                // alert('error')
            },
        });
      }
    },
    convertSvgToInline: {
      init: function(){
        $('img.svg').each(function(){
       var $img = jQuery(this);
       var imgID = $img.attr('id');
       var imgClass = $img.attr('class');
       var imgURL = $img.attr('src');

       jQuery.get(imgURL, function(data) {
           // Get the SVG tag, ignore the rest
           var $svg = jQuery(data).find('svg');

           // Add replaced image's ID to the new SVG
           if(typeof imgID !== 'undefined') {
               $svg = $svg.attr('id', imgID);
           }
           // Add replaced image's classes to the new SVG
           if(typeof imgClass !== 'undefined') {
               $svg = $svg.attr('class', imgClass+' replaced-svg');
           }

           // Remove any invalid XML tags as per http://validator.w3.org
           $svg = $svg.removeAttr('xmlns:a');

           // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
           if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
               $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
           }

           // Replace image with new SVG
           $img.replaceWith($svg);
       }, 'xml');
     });
      }
    }
  }
})(jQuery);

LFS.favourites.init();