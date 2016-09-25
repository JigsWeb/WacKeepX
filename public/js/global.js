(function ($) {
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);

$(".notes-container").ready(function(){
    $('.notes-container').isotope({
        itemSelector: '.note',
        resize: false,
        masonry: {
            columnWidth: 20,
            gutter: 4
        }
    });
});

$(document).ready(function(){
    $("textarea[name='content']").keydown(textareaAutoHeight);

    $("textarea[name='content']").each(textareaAutoHeight);

    $(".new-note").submit(function(e){
        e.preventDefault();

        var data = $(this).serializeFormJSON();

        if(!data.title.length){
            alert('Vous ne pouvez pas créer une note sans titre');
            return;
        }

        $("button[type='submit']", $(this)).prop('disabled', true);

        $.post($(this).attr('action'), data)
            .then(function(result){
                $("button[type='submit']", $(".new-note")).prop('disabled', false);
                $(".new-note")[0].reset();
                $(".new-note .checkboxes").empty();
                addNote(result);
            })
            .catch(function(result){
                console.log(result);
            });
    });

    noteEventBind();
    checkboxEventBind();

    $(".new-note .colors .choice li").click(function(){
        $(".new-note .colors .choice li").removeClass("checked");

        $("input[name='color']", $(this).parents(".new-note")).val($(this).data('color'));
        $(this).addClass('checked');

        $(".new-note").removeClass().addClass('new-note').addClass($(this).data('color'));
    });

    function throwError(data){
        console.log(data);
    }

    function addNote(data){
        var $colorList = $('<ul class="choice"></ul>')
            .append(colorWrapper("white"))
            .append(colorWrapper("red"))
            .append(colorWrapper("blue"))
            .append(colorWrapper("turquoise"))
            .append(colorWrapper("grey"))
            .append(colorWrapper("yellow"))
            .append(colorWrapper("orange"))
            .append(colorWrapper("green"));

        var $colorsContainer = $('<div class="colors"></div>').append($colorList);

        var $addCheckbox = $('<div class="fi flaticon-list add-checkbox"></div>').append($('<span class="popover">Ajouter une checkbox</span>'));

        var $checkboxes = $("<ul class='checkboxes'></ul>");

        data.checkboxes.forEach(function(element){
            $checkboxes.append(checkboxWrapper(element));
        });

        var $note = $("<form class='note "+ data.color +"' data-id='"+ data.id +"'></form>")
            .append($("<a href='#' class='fi flaticon-multiply close'></a>"))
            .append($("<input type='text' name='title' value='" + data.title.replace('\'','&apos;') + "' />"))
            .append($("<textarea name='content' class='content'>" + data.content + "</textarea>"))
            .append($checkboxes)
            .append($addCheckbox)
            .append($colorsContainer)
            .append($("<button type='submit'>OK</button>"));

        $note.prependTo($('.notes-container'));

        textareaAutoHeight.call($("textarea[name='content']", $note)[0]);

        $(".colors li."+ data.color, $note).addClass('checked');

        alert("Note ajouté avec succès");

        checkboxEventBind();
        noteEventBind();
        isotopReload();
    }

    function alert(message){

        var $alert = $('<div class="alert-box"></div>')
            .append($('<span class="message">'+ message +'</span>'))
            .append($('<i class="fi flaticon-multiply"></i>'));

        $alert.appendTo($(".alert-container"));

        $alert.css('bottom','30px');

        var alertTimeout = setTimeout(function(){
            $alert.css('bottom','-70px');
            setTimeout(function(){
                $alert.remove();
            }, 2500);
        }, 3000);

        $(".alert-box i").click(function(){
            clearTimeout(alertTimeout);
            $alert.remove();
        });
    }

    function checkboxEventBind(){
        checkboxAddEvent();
        checkboxCheckEvent();
        checkboxRemoveEvent();
    }

    function checkboxAddEvent(){
        $(".add-checkbox").unbind('click').click(function(){
            var $form = $(this).parents('form');

            addCheckbox($form);

            if($form.hasClass('note')) isotopReload();
        });
    }

    function checkboxCheckEvent(){
        $(".false-checkbox").unbind('click').click(function(){
            var $checkbox = $(this).prev();

            if(parseInt($checkbox.val())){
                $(this).removeClass('checked');
                $checkbox.val("0");
            }
            else{
                $(this).addClass('checked');
                $checkbox.val("1");
            }
        });
    }

    function checkboxRemoveEvent(){
        $(".remove-checkbox").unbind('click').click(function(){
            var $note = $(this).parents('.note'),
                $li   = $(this).parent();

            var $id = $('input[name="checkboxes_id[]"]', $li);

            if($note.length && $id.length){
                $note.append($('<input type="hidden" name="checkboxes_destroy[]" value="'+ $id.val() +'"/>'));
            }

            $li.remove();
        });
    }

    function noteEventBind(){
        noteCloseEvent();
        noteColorEvent();
        noteUpdateEvent();
    }

    function noteUpdateEvent(){
        $(".note").unbind('submit').submit(function(e){
            e.preventDefault();

            var $note = $(this);
            var data  = $(this).serializeFormJSON();

            if(!data.title.length){
                alert('Une note sans titre, c\'est un homme sans queue');
                return;
            }

            $("button[type='submit']", $note).prop('disabled', true);

            $.post($(this).attr('action'), data)
                .then(function(result){
                    $("button[type='submit']", $note).prop('disabled', false);
                })
                .catch(function(result){
                    console.log(result);
                });
        });
    }

    function noteCloseEvent(){
        $(".note .close").unbind('click').click(function(e){
            e.preventDefault();

            var $link = $(this),
                $note = $(this).parent();

            $link.prop('disabled', true);
            $note.remove();
            isotopReload();

            $.post('/note/'+ $note.data('id').toString(), {'_method': 'DELETE','_token': $('.new-note input[name="_token"]').val() })
                .catch(throwError);
        });
    }

    function noteColorEvent(){
        $(".note .colors .choice li").unbind('click').click(function(){
            var $note = $(this).parents('.note'),
                $li   = $(this),
                color = $(this).data('color');

            $(".note .colors .choice li").removeClass("checked");
            $li.addClass('checked');
            $("input[name='color']", $note).val(color);

            $li.parents(".note").removeClass().addClass('note').addClass(color);

            $.post('/note/' + $note.data('id'), {"_method": 'PUT','color': color,'_token': $('.new-note input[name="_token"]').val()})
                .catch(throwError);
        });
    }

    function isotopReload(){
        $('.notes-container').isotope("reloadItems").isotope({
            itemSelector: '.note',
            resize: false,
            masonry: {
                columnWidth: 20,
                gutter: 4
            }
        });
    }

    function colorWrapper(color){
        return $('<li data-color="'+ color +'"></li>').addClass(color)
    }

    function checkboxWrapper(data){
        var falseCheckboxClass = data.checked ? 'checked' : '';

        return $("<li></li>")
            .append($('<input type="hidden" name="checkboxes_id[]" value="' + data.id +'">'))
            .append($('<input type="hidden" name="checkboxes_checked[]" value="' + data.checked + '">'))
            .append($('<span class="false-checkbox ' + falseCheckboxClass +'"></span>'))
            .append($('<input type="text" name="checkboxes_value[]" placeholder="Element de liste" value="' + data.content + '">'))
            .append($('<i class="fi flaticon-substract remove-checkbox"></i>'));
    }

    function textareaAutoHeight(){
        var el = this;
        setTimeout(function(){
            el.style.cssText = 'height:auto; padding:0';
            // for box-sizing other than "content-box" use:
            // el.style.cssText = '-moz-box-sizing:content-box';
            el.style.cssText = 'height:' + el.scrollHeight + 'px';
            isotopReload();
        },0);
    }

    function addCheckbox($form){
        var $lastCheckbox = $(".checkboxes li:last input[type='text']", $form);

        if(!$lastCheckbox.length || $lastCheckbox.val().length){
            var $li = $("<li></li>")
                .append($('<input type="hidden" name="checkboxes_checked[]" value="0">'))
                .append($('<span class="false-checkbox"></span>'))
                .append($('<input type="text" name="checkboxes_value[]" placeholder="Element de liste">'))
                .append($('<i class="fi flaticon-substract remove-checkbox"></i>'));

            $(".checkboxes", $form).append($li);
            checkboxEventBind();
        }

        $(".checkboxes li:last input[type='text']", $form).focus();
    }
});