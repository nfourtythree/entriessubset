/**
 * Entries Subset plugin for Craft CMS
 *
 *  Field JS
 *
 * @author    Nathaniel Hammond (nfourtythree)
 * @copyright Copyright (c) 2017 Nathaniel Hammond (nfourtythree)
 * @link      http://n43.me
 * @package   EntriesSubset
 * @since     1.0.0EntriesSubset
 */

 ;(function ( $, window, document, undefined ) {

    var pluginName = "EntriesSubset",
        defaults = {
          selector:'#types-nfourtythree-entriessubset-fields-EntriesSubsetField'
        };

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {
              _this.enableEntryTypes();

              $( _this._defaults.selector + '-sources-field input[type="checkbox"]' ).on( 'change', function() {
                _this.enableEntryTypes();
              } );

            });
        },

        enableEntryTypes: function() {
          var _this = this;

          // Loop through sources
          $( '#nfourtythree-entriessubset-fields-EntriesSubsetField .checkbox-select[class*="section"] input[type="checkbox"]' ).attr( 'disabled', 'disabled' );

          $( _this._defaults.selector + '-sources-field input[type="checkbox"]' ).each( function() {
            if ( !$( this ).prop( 'disabled' ) && $( this ).prop( 'checked' ) ) {
              _this.enableEntryType( $( this ).val() );
            }
          } );
        },

        enableEntryType: function( val ) {
          var _this = this;

          if ( val === '*' ) {
            $( '#nfourtythree-entriessubset-fields-EntriesSubsetField .checkbox-select[class*="section"] input[type="checkbox"]' ).removeAttr( 'disabled' );
          } else if ( val === 'singles' ) {
            $( '#nfourtythree-entriessubset-fields-EntriesSubsetField .checkbox-select.singles input[type="checkbox"]' ).removeAttr( 'disabled' );
          } else {
            var section = val.split( ':' );
            if ( section.length == 2 ) {
              var _sectionSelector = section[1];

              // For Craft 3.1 to selector the correct items to turn on and off
              if (Craft && Craft.publishableSections && Craft.publishableSections.length) {
                for (var i = 0; i < Craft.publishableSections.length; i++) {
                  var _tmp = Craft.publishableSections[i];
                  if (_tmp.uid && _tmp.uid == section[1]) {
                    _sectionSelector = _tmp.id;
                    break;
                  }
                }
              }

              var _selectorString = '#nfourtythree-entriessubset-fields-EntriesSubsetField .checkbox-select[class*="section-' + _sectionSelector + '"] input[type="checkbox"]';
              $( _selectorString ).removeAttr( 'disabled' );
            }
          }
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
      if (!$.data(this, "plugin_" + pluginName)) {
          $.data(this, "plugin_" + pluginName,
          new Plugin( this, options ));
      }
    };

})( jQuery, window, document );
