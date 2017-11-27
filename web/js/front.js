/**
 * Created with JetBrains PhpStorm.
 * User: willy
 * Date: 2/16/14
 * Time: 8:23 AM
 * To change this template use File | Settings | File Templates.
 */
var timer = new Timer();
timer.start();

(function(window, document, $) {
    $items = $(".question-container>li");
    $paletteItems = $(".palette-container>li");

    startUp();

    $("#controller-prev").click(function() {
        fillPalette(currentId);

        $question = getQuestion().prev();
        currentId = getId(getQuestion().prev().addClass('active'));
        $items.not($question).removeAttr('class');

        checkControlState();
    });

    $("#controller-next").on('click', function() {
        fillPalette(currentId);

        $question = getQuestion().next();
        currentId = getId(getQuestion().next().addClass('active'));
        $items.not($question).removeAttr('class');

        checkControlState();
    });

    $(".palette-item").on('click', function() {
        var curentPalette = findPalette(getId(getQuestion())).addClass('current');
        fillPalette(getId(curentPalette));

        currentId = getId($(this));
        $question = getQuestion();
        currentId = getId($question.addClass('active'));
        $items.not($question).removeAttr('class');

        checkControlState();
    });

    $("input[type='radio']").change(function() {
        findPalette(currentId).removeClass('skipped').addClass('answered');

        post($(this).parents('li').data('question'), getId($(this)));
    });

    function checkPaletteContainer() {
        var $curentPalette = findPalette(getId(getQuestion()));

        if(!$curentPalette.parent('ul').hasClass('active')) {
            $active = $curentPalette.parent('ul').addClass('active');
            $(".palette-container").not($active).removeClass('active');
        }
    }

    function fillPalette(id) {console.log(id);
        if($("input:radio[name=opt"+id+"]").is(':checked') == false) {
            findPalette(id).addClass('skipped');
        }
    }

    function startUp() {
        currentId = getId($items.first().addClass('active'));
        $(".palette-container").first().addClass('active');

        $.each($items, function() {
            if($("input:radio[name=opt"+getId($(this))+"]").is(':checked')) {
                console.log($(this).data('id'));
                findPalette($(this).data('id')).addClass('answered');
            }
        });

        checkControlState();
    }

    function checkControlState() {
        if(currentId === 1) {
            disabled($("#controller-prev"));
        } else {
            enabled($("#controller-prev"));
        }

        if(currentId > getTotalQuestion()-1) {
            disabled($("#controller-next"));
        } else {
            enabled($("#controller-next"));
        }

        $(".question-count").html(getId(getQuestion())+"/"+getTotalQuestion());

        checkPaletteContainer();

        checkPaletteState();
    }

    function checkPaletteState() {
        var curentPalette = findPalette(getId(getQuestion())).addClass('current');

        $paletteItems.not(curentPalette).removeClass('current');
    }

    function enabled($controller) {
        return $controller.removeClass('disabled').removeAttr('disabled');
    }

    function disabled($controller) {
        return $controller.addClass('disabled').attr('disabled', 'disabled');
    }

    function getQuestion() {
        return $items.parent().find("[data-id='"+currentId+"']");
    }

    function getId($el) {
        return $el.data('id');
    }

    function findPalette(id) {
        return $paletteItems.parent().find("[data-id='"+id+"']");
    }

    function getTotalQuestion() {
        return $items.length;
    }

    function post(questionId, answerId) {
        timer.stop();

        var data = {
            questionId: questionId,
            answerId: answerId
        }

        $.post('/exam/attempt', data, function() {
            $(".frozen").show();
        })
            .complete(function() {
            $(".frozen").fadeOut(function() {
                timer.start();
            });

            if(!(currentId > getTotalQuestion()-1)) {
                $("#controller-next").click();
            }
        });
    }
$("body").fadeIn();
})(window, document, jQuery);