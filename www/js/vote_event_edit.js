// vote events edit/create
var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;
    // an array that will be populated with substring matches
    matches = [];
    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');
    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });
    cb(matches);
  };
};

var parties = [];
var abbreviations = [];
var parties2 = {};
var abbreviations2 = {};
for (i=0;i<organizations.length;i++) {
    parties.push(organizations[i].name);
    if (organizations[i].attributes == undefined) {
        organizations[i].attributes = {};
    }
    if (!(organizations[i].attributes.hasOwnProperty("abbreviation"))) {
        organizations[i].attributes.abbreviation = "";
    }
    if (!(organizations[i].attributes.hasOwnProperty("color"))) {
        organizations[i].attributes.color = "#000000";
    }
    abbreviations.push(organizations[i].attributes.abbreviation);
    parties2[organizations[i].name] = {"name": organizations[i].name, "abbreviation": organizations[i].attributes.abbreviation, "color": organizations[i].attributes.color}
    abbreviations2[organizations[i].attributes.abbreviation] = {"name": organizations[i].name, "abbreviation": organizations[i].attributes.abbreviation, "color": organizations[i].attributes.color}
}

var row = {"i":0, "family_name": "", "given_name": "", "organization_name":"", "organization_abbreviation": "", "organization_color": "#000000"};


//show at least 1 row
if (rows.length == 0) {
    var copyrow = $.extend(true, {}, row);
    rows.push(copyrow);
    row.i = next;
}

var next = rows.length;

for (var i=0;i<rows.length;i++) {
    rows[i].t = t;
}


//values keep values, because typeahead keeps them somewhere (not in inputs)
var values = {}
for (i=0;i<rows.length;i++) {
    values[i] = rows[i];
}

//set up typeahead
function activate_typeahead() {
    $('*[data-typeahead]').each(function(){
        $(this).typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'typeahead',
            source: substringMatcher(window[$(this).data("typeahead")])
        });
    });
}
//set up typeahead for a single row
function activate_typeahead_for_row(i) {
    $('*[data-typeahead][data-row="'+ i +'"]').each(function(){
        $(this).typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'typeahead',
            source: substringMatcher(window[$(this).data("typeahead")])
        });
    });
}

 // on select from typeahead change also abbr. and color
function selected_name() {
    $('*[data-typeahead="parties"]').each(function(){
        $(this).on('typeahead:select',function(){
            r = $(this).data("row");
            newval = {
                "organization_name":  $(this).typeahead('val'),
                "organization_abbreviation": parties2[$(this).val()].abbreviation,
                "organization_color": parties2[$(this).val()].color
            }
            $('*[data-row='+r+'][data-col="organization_abbreviation"]').val(parties2[$(this).val()].abbreviation);
            $('*[data-row='+r+'][data-col="organization_color"]').val(parties2[$(this).val()].color);
            values[r]['organization_name'] = newval.organization_name;
            values[r]['organization_abbreviation'] = newval.organization_abbreviation;
            values[r]['organization_color'] = newval.organization_color;
        });
    });
}
// on select from typeahead change also name and color
function selected_abbreviation() {
    $('*[data-typeahead="abbreviations"]').each(function(){
        $(this).on('typeahead:select',function(){
            var r = $(this).data("row");
            newval = {
                "organization_name":   abbreviations2[$(this).val()].name,
                "organization_abbreviation": $(this).typeahead('val'),
                "organization_color": abbreviations2[$(this).val()].color
            }
            $('*[data-row='+r+'][data-col="organization_name"]').val(abbreviations2[$(this).val()].name);
            $('*[data-row='+r+'][data-col="organization_color"]').val(abbreviations2[$(this).val()].color);
            values[r]['organization_name'] = newval.organization_name;
            values[r]['organization_abbreviation'] = newval.organization_abbreviation;
            values[r]['organization_color'] = newval.organization_color;
        });
    });
}

// on new name update variables for typeahead
function added_name() {
    $('*[data-typeahead="parties"]').on('change',function(){
        var r = $(this).data("row");
        values[r]['organization_name'] = $(this).val();
        var val = {
            "name": values[r]['organization_name'],
            "abbreviation": values[r]['organization_abbreviation'],
            "color": values[r]['organization_color']
        };
        abbreviations2[values[r]['organization_abbreviation']] = val;
        parties2[$(this).val()] = val;
        if (parties.indexOf($(this).val()) === -1) {
            parties.push($(this).val())
        }
    });
}
// on new abbreviation update variables for typeahead
function added_abbreviation() {
    $('*[data-typeahead="abbreviations"]').on('change',function(){
        var r = $(this).data("row");
        values[r]['organization_abbreviation'] = $(this).val();
        var val = {
            "name": values[r]['organization_name'],
            "abbreviation": values[r]['organization_abbreviation'],
            "color": values[r]['organization_color']
        };
        abbreviations2[$(this).val()] = val;
        parties2[values[r]['organization_name']] = val;
        if (abbreviations.indexOf($(this).val()) === -1) {
            abbreviations.push($(this).val())
        }
    });
}
// on new or changed color update values and also all colors with the same party
function changed_color() {
    $('*[data-col="organization_color"]').on('change',function() {
        var r = $(this).data("row");
        values[r]['organization_color'] = $(this).val();
        //updating for typeahead
        var val = {
            "name": values[r]['organization_name'],
            "abbreviation": values[r]['organization_abbreviation'],
            "color": values[r]['organization_color']
        };
        abbreviations2[values[r]['organization_abbreviation']] = val;
        parties2[values[r]['organization_name']] = val;
        // change all colors for the same abbreviations
        $('*[data-col="organization_color"]').each(function(){
            var rr = $(this).data("row");
            if (values[r]['organization_abbreviation'] == values[rr]['organization_abbreviation']) {
                $(this).val(values[r]['organization_color']);
            }
        });
    });
}
// prefil all options
function select_all_options() {
    $('[name="option"]').on('click',function(){
        var opt = $(this).val();
        $('input[type="radio"]').each(function(){
            if ($(this).val() == opt) {
                    r = $(this).data('row');
                    if (r != undefined) {
                    $('[name="option-' + r + '"]').each(function(){
                        $(this).attr('checked',false);
                    });
                    $(this).attr('checked',true).click();
                }
            }
        });
    });
}


//zebra
function zebra() {
    var zebra = false;
    $(".ve-row").each(function(){
        if (zebra) {
            $(this).addClass("zebra-even");
            $(this).removeClass("zebra-odd");
            $(this).find('input').each(function(){
                $(this).addClass("zebra-even");
                $(this).removeClass("zebra-odd");
            });
            zebra = false;
        } else {
            $(this).addClass("zebra-odd");
            $(this).removeClass("zebra-even");
            $(this).find('input').each(function(){
                $(this).addClass("zebra-odd");
                $(this).removeClass("zebra-even");
            });
            zebra = true;
        }

    })
}
//remove row
function remove_row() {
    //remove row
    $("*[data-col='x']").on('click',function(){
        r = $(this).data('row');
        $("#row-" + r).remove();
        $("#count-rows").html($(".ve-row").length);
    })
    zebra();
}

$(document).ready(function() {

    $.get('templates/motion_new_vote-event_row.handlebars',function(data) {
        //pref-filled rows
        var row_source = data;
        var source = "{{#each rows}}" + row_source + "{{/each}}";
        var row_template = Handlebars.compile(row_source);
        var template = Handlebars.compile(source);
        var data = {"rows":rows};
        $('#form_fieldset').html(template(data));
        //register functions:
        activate_typeahead();
        selected_name();
        selected_abbreviation();
        added_abbreviation();
        added_name();
        changed_color();
        select_all_options();
        remove_row();
        zebra();
        $("#count-rows").html($(".ve-row").length);

        // add new row
        $("#add-row").on('click',function(){
            next++;
            var ro = {};
            ro.i = next;
            ro.t = t;
            values[next] = jQuery.extend(true, {}, ro);
            $('#form_fieldset').append(row_template(ro));
            //register functions again including new row:
            activate_typeahead_for_row(next);
            selected_name();
            selected_abbreviation();
            added_abbreviation();
            added_name();
            changed_color();
            select_all_options();
            remove_row();
            zebra();

            $("#count-rows").html($(".ve-row").length);
        });
    }, 'text');
});
