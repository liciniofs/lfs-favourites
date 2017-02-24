NEWLI = '' || {};

(function($) {
 NEWLI.modules = {
   init: function(){
     NEWLI.modules.build();
   },
   build: function(){
     NEWLI.modules.mainSlider();
   },
   mainSlider: {
       variable: '',
       init: function() {
            console.log(this);
           this.build();

       },
       build: function() {

       },
       action_example: function() {

       }
   },
   newsBlock: (function(elem) {
       this.element = elem,

       this.init = function() {

       };
       this.build = function() {

       };

       this.loadMoreNews = function() {
           var _this = this;

       };
       this.init();
   }),
 }
})(jQuery);
