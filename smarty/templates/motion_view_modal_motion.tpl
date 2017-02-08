<link href="{$settings->app_url}libs/summernote.css" rel="stylesheet">
<script src="{$settings->app_url}libs/summernote.min.js"></script>
<script src="{$settings->app_url}libs/lang/summernote-cs-CZ.min.js"></script>
<link href="{$settings->app_url}libs/bootstrap-tagsinput.css" rel="stylesheet">
<script src="{$settings->app_url}libs/bootstrap-tagsinput.min.js"></script>


<fieldset>
    <input type="hidden" name="motion_id" value="{$motion->id}">

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="name">{$t->get('motion_name')}:</label>
            <input type="text" required class="form-control" name="name" id="name" value="{$motion->name|htmlspecialchars|unescape}"/>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6">
            <label for="date">{$t->get('motion_date')}:</label>
            <input type="date" required class="form-control" name="date" id="date" {if !$date_and_time['date']}placeholder="2012-07-21"{else}value="{$date_and_time['date']}"{/if} pattern="{literal}[0-9]4}-[0-9]{2}-[0-9]{2}{/literal}"/>
        </div>
        <div class="form-group col-sm-6">
            <label for="time">{$t->get('motion_time')}:</label>
            <input type="time" class="form-control" name="time" id="time" {if !$date_and_time['time']}placeholder="12:00"{else}value="{$date_and_time['time']}"{/if}/>
        </div>
    </div>

    <div class="form-group">
        <label for="description">{$t->get('motion_description')}:</label>
        <textarea id="description" name="description">
            {$motion->description|htmlspecialchars}
        </textarea>
    </div>

    <div class="form-group">
        <label for="links">{$t->get('links')}:</label>
        <fieldset id="links">
            <div>
                {if count($motion->attributes->links) == 0}
                    <div class="col-sm-4">
                        <input type="text" id="links_descriptions" name="links_descriptions[]"     placeholder="{$t->get('motion_link_description')}" class="form-control"/>
                    </div>
                    <div class="col-sm-8">
                        <input type="url" id="links_links" name="links_links[]" placeholder="{$t->get('motion_link_link')}" class="form-control" />
                    </div>
                {else}
                    {foreach $motion->attributes->links as $link}
                        <div>
                            <div class="col-sm-4">
                                <input type="text" id="links_descriptions" name="links_descriptions[]" value="{$link->text|htmlspecialchars|unescape}" class="form-control"/>
                            </div>
                            <div class="col-sm-7">
                                <input type="url" id="links_links" name="links_links[]" value="{$link->url|htmlspecialchars|unescape}" class="form-control" />
                            </div>
                            <button class="remove_field col-sm-1 btn btn-danger">X</button>
                        </div>
                    {/foreach}
                {/if}
            </div>
        </fieldset>
        <button class="add_field_button btn btn-info btn-xs">+ {$t->get('add_link')}</button>
    </div>

    <div class="form-group">
        <label for="tags">{$t->get('tags')}:</label>
        <input type="text" id="tags" name="tags" data-role="tagsinput" value="{foreach $tags as $tag}{$tag->tag|htmlspecialchars|unescape},{/foreach}"/>
    </div>


</fieldset>


<script>
$(document).ready(function() {
    //initialize summernote for vysiwyg edit
    $('#description').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['link', ['link']],
            ['misc', ['fullscreen','codeview','help']]
        ],
        placeholder: "{$t->get('motion_description')}...",
        lang: 'cs-CZ',
        minHeight: 30,
    });
    //send the description back to textarea
    $('form[name=motion_form]').submit(function(){
        $('#description').html($('#description').summernote('code'));
    });


    var max_fields      = 10; //maximum input boxes allowed
        var wrapper         = $("#links"); //Fields wrapper
        var add_button      = $(".add_field_button"); //Add button ID
        var motion_link_description = "{$t->get('motion_link_description')}";
        var motion_link_link = "{$t->get('motion_link_link')}";

        var x = 1; //initlal text box count
        $(add_button).click(function(e){ //on add input button click
            e.preventDefault();
            if(x < max_fields){ //max input box allowed
                x++; //text box increment
                $(wrapper).append('<div><div class="col-sm-4"><input type="text" id="links_descriptions" name="links_descriptions[]" placeholder="'+motion_link_description+'" class="form-control"/></div><div class="col-sm-7"><input type="text" id="links_links" name="links_links[]" placeholder="'+motion_link_link+'" class="form-control" /></div><button class="remove_field col-sm-1 btn btn-danger">X</button></div>'); //add input box
            }
        });

        $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })


    //if # is present, directly open modal
    if(window.location.hash) {
        $('#modal-edit').modal('show');
    }

});
</script>
