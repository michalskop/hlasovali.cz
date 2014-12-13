// FILTER
//normalization (á -> a)
//https://github.com/twitter/typeahead.js/issues/271#issuecomment-37123748
//http://stackoverflow.com/a/22772703/1666623
$(function(){
  $.get( "json/filter.json", function(data) {
    names = data;
  })
  .done(function() {
    var charMap = {
        'àáâããäåą': 'a',
        'çčć': 'c',
        'ď': 'd',
        'èéêëěę': 'e',
        'íîï': 'i',
        'ĺľł': 'l',
        'ňńñ': 'n',
        'óôöő': 'o',
        'řŕ': 'r',
        'šś': 's',
        'ť': 't',
        'ůúüùûű': 'u',
        'ýÿ': 'y',
        'žźż': 'z',
        'ß': 'ss',
        'œ': 'oe',
        'æ': 'ae'
        };

    var normalize = function(str) {
      $.each(charMap, function(chars, normalized) {
        var regex = new RegExp('[' + chars + ']', 'gi');
        str = str.replace(regex, normalized);
      });

      return str;
    }

    var queryTokenizer = function (q) {
        var normalized = normalize(q);
        return Bloodhound.tokenizers.whitespace(normalized);
    };

    var nombres = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: queryTokenizer,
        local: $.map(names, function (name) {
            // Normalize the name - use this for searching
            var normalized = normalize(name.name);
            return {
                value: normalized,
                // Include the original name - use this for display purposes
                displayValue: name.name,
                link: name.link
            };
        })
    });

    nombres.initialize();

    var typeahead = $('.typeahead');

    typeahead.typeahead({
        minLength: 1,
        hint: false,
        highlight: true
    }, {
        displayKey: 'displayValue',
        source: nombres.ttAdapter()
    }).on('typeahead:selected', function(event, data){   
           window.location.href = data.link;
        });
  });
});
