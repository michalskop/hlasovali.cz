<script src="{$settings->app_url}libs/typeahead.bundle.min.js"></script>
<script src="{$settings->app_url}libs/handlebars.min.js"></script>

<style>
    .zebra-even {
        background-color: #EEEEEE;
    }
    .zebra-odd {
        background-color: #FFFFFF;
    }

    .ve-row .input-sm {
        padding: 3px 3px;
    }
    .ve-row .form-group {
        padding-left: 15px;
        padding-right: 0px;
        margin-bottom: 2px;
    }

    .btn-danger.semitransparent {
        opacity: 0.5;
    }
    .btn-danger.semitransparent:hover {
        opacity: 1;
    }
/** typeahead **/
/** http://stackoverflow.com/questions/20198247/twitters-typeahead-js-suggestions-are-not-styled-have-no-border-transparent-b **/
/** http://jsfiddle.net/needathinkle/MA7Ep/ **/

.twitter-typeahead {
    width: 100%;
    position: relative;
}

    .tt-query {
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}

.tt-hint {
  color: #999
}

.tt-menu {    /* used to be tt-dropdown-menu in older versions */
  width: 422px;
  margin-top: 4px;
  padding: 4px 0;
  background-color: #fff;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
  -webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
     -moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
          box-shadow: 0 5px 10px rgba(0,0,0,.2);
}

.tt-suggestion {
  padding: 3px 20px;
  line-height: 24px;
}

.tt-suggestion.tt-cursor,.tt-suggestion:hover {
  color: #fff;
  background-color: #0097cf;

}
</style>
<hr>
<h2>{$t->get('votes_by_representatives')}</h2>
<div class="row">
    <div class="form-group col-sm-4">
        <label for="vote_event_identifier">{$t->get('vote_event_identifier')}:</label>
        <input type="text" name="vote_event_identifier" class="form-control input-sm" placeholder="{$t->get('vote_event_identifier_placeholder')}" >
    </div>
</div>
<div>
    <div class="row">
        <div class="form-group col-sm-4" >
            <div class="well-sm">
                {$t->get('current_number')}: <span id="count-rows"></span>
            </div>
        </div>
        <div class="form-group col-sm-4"></div>
        <div class="form-group col-sm-4">
            <label class="radio-inline">
                <input type="radio" name="option" value="yes"><span class="text-success">{$t->get('ve_yes')}</span>
            </label>
            <label class="radio-inline">
                <input type="radio" name="option" value="no"><span class="text-danger">{$t->get('ve_no')}</span>
            </label>
            <label class="radio-inline">
                <input type="radio" name="option" value="abstain"><span class="text-danger">{$t->get('ve_abstain')}</span>
            </label>
            <label class="radio-inline">
                <input type="radio" name="option" value="absent"><span class="text-muted">{$t->get('ve_absent')}</span>
            </label>
        </div>
    </div>
    <br>
    <fieldset id="form_fieldset">

    </fieldset>
    <div class="form-group col-sm-2">
        <button class="btn btn-sm btn-info btn-block" id="add-row"> + {$t->get('add_representative')}</button>
    </div>
</div>

<script>
    var organizations = {$form_organizations};
    var family = {$form_family};
    var rows = {$form_rows};
    var t = {$form_t};
</script>
<script src="{$settings->app_url}js/vote_event_edit.js"></script>
