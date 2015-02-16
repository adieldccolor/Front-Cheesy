(function ( $ ) {

    $.fn.transition = function( options ) {

        if( options == undefined ){
            options = { transition: "auto" };
        }

        var settings = $.extend({
            // These are the defaults.
            transition: "auto"
        }, options );

        this.css({'transition': settings.transition, '-webkit-transition': settings.transition, 
                        '-moz-transition': settings.transition, '-ms-transition': settings.transition});
        return this;
    };

 
    $.fn.animatecss = function( options ) {

        if( options == undefined ){
            options = { animation: "fadeIn" };
        }

        // This is the easiest way to have default options.
        var settings = $.extend({
            // These are the defaults.
            animation: "fadeIn",
            duration: 1,
            infinite: false
        }, options );

        var searchFor = function(getArr, words){

            var foundIndex = -1, foundArr = [], found = false, arr = getArr, ret = foundIndex;
            for(i = 0; i < arr.length; i++){
                if( arr[i] == words ){
                    foundArr.push(i);
                    foundIndex = i;
                    found = true;
                }
            }

            if( found ){
                if( foundArr.length > 0 ){
                    ret = foundArr;
                }
                ret = foundIndex;
            }

            return ret;
        }

         var validTransitions = 'bounce flash pulse rubberBandshake swing '
                + 'tada wobble bounceIn bounceInDown bounceInLeft bounceInRight '
                + 'bounceInUp bounceOut bounceOutDown bounceOutLeft bounceOutRight '
                + 'bounceOutUp fadeIn fadeInDown fadeInDownBig fadeInLeft '
                + 'fadeInLeftBig fadeInRight fadeInRightBig fadeInUp fadeInUpBig '
                + 'fadeOut fadeOutDown fadeOutDownBig fadeOutLeft fadeOutLeftBig '
                + 'fadeOutRight fadeOutRightBig fadeOutUp fadeOutUpBig flipInX '
                + 'flip flipInY flipOutX flipOutY lightSpeedIn lightSpeedOut rotateIn '
                + 'rotateInDownLeft rotateInDownRight rotateInUpLeft rotateInUpRight '
                + 'rotateOut rotateOutDownLeft rotateOutDownRight rotateOutUpLeft '
                + 'rotateOutUpRight hinge rollIn rollOut zoomIn zoomInDown '
                + 'zoomInLeft zoomInRight zoomInUp zoomOut zoomOutDown zoomOutLeft '
                + 'zoomOutRight zoomOutUp slideInDown slideInLeft slideInRight '
                + 'slideInUp slideOutDown slideOutLeft slideOutRight slideOutUp';


        if( typeof options == "object" ){

            searchFor!=undefined&&(function(){
                var transitions = validTransitions.split(" "), 
                    find = searchFor(transitions, options.animation);
                if( typeof find !== "object" || find < 0 ){
                    options.animation = "fadeIn";
                    settings.animation = "fadeIn";
                }
            })();
            
        }else if( typeof options == "string" && options != "reset" ){


            searchFor!=undefined&&(function(){
                var transitions = validTransitions.split(" "), 
                    find = searchFor(transitions, options);
                if( typeof find == "object" || find > -1 ){
                    settings.animation = options;
                }else{
                    settings.animation = "fadeIn";
                }
            })();
           
        }

        if(typeof options == "string" && options == "reset"){

            $(this).removeClass('animated ' + validTransitions)
                .transition();
        }else{
            $(this).removeClass('animated ' + validTransitions)
                .transition()
                .addClass("animated " + settings.animation + ( settings.infinite ? " infinite" : "" ) );
            if( settings.duration > 1 && settings.duration != "auto" ){
                $(this).transition("all " + settings.duration + "s");
            }else{
                $(this).transition();
            }
        }
 
        return this;
 
    };
 
}( jQuery ));