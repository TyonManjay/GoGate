/**
 * Created with JetBrains PhpStorm.
 * User: willy
 * Date: 2/16/14
 * Time: 8:23 AM
 * To change this template use File | Settings | File Templates.
 */

    var on = false;

    var Timer = function( options ) {
        opt = $.extend({}, defaults, options);
    };

    function show(tok) {
        if($("#timer").length) {
            document.getElementById('timer').innerHTML = tok;
        }
    }

    function formater(number) {console.log(number);
        if(number % 1 == 0){
            var divisor = 60;
            var min = Math.floor((number/divisor));
            var sec = doubleZero(Math.floor(number - (min * 60)));

            return doubleZero(min)+':'+sec;
        }
    }

    function doubleZero(number) {
        if(number.toString().length == 1) {
            return "0"+number;
        }

        return number;
    }

    Timer.prototype.start = function() {
        if(!on) {
            on = !on;
            tik = setInterval(function() {
                show(formater(opt.limit));
                if(opt.limit < 1){
                    Timer.prototype.stop();
                    $.post('/exam/enrollment/finished' ,function(response) {
                        window.location.replace(location.protocol+"//"+location.hostname+response.url);
                    })
                }else{
                    opt.limit = opt.limit-1;
                }
            }, 1000);
        }
    }

    Timer.prototype.stop = function() {
        if(on == true) {
            clearInterval(tik);
            on = !on;
        }
    }

    defaults = {
        limit: $("#sec").val()
    }