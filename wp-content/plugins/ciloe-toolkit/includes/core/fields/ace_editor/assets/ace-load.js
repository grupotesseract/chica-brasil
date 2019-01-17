(function( $ ) {
    "use strict";

    $( document ).ready(function() {

        // Iterate cs-field-aceeditors
        $( '.cs-field-ace_editor' ).each(function(index) {

            var $editorContainer = $( this ).find( '.ace-editor-container' );

            // Get textarea to get/save data
            var $editorTextarea = $editorContainer.prev( 'textarea' );

            // Add ID to ace-editor-container
            $editorContainer.attr( 'id', 'ace_editor' + index );

            // Get theme and language
            var editorTheme = $editorContainer.data( 'theme' );
            var editorMode = $editorContainer.data( 'mode' );

            // Inicialize ACE editor
            var editor = ace.edit( 'ace_editor' + index );

            // Set editor settings
            editor.setTheme( 'ace/theme/' + editorTheme );
            editor.getSession().setMode( 'ace/mode/' + editorMode );

            editor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: true
            });

            // Save data in textarea on ACE editor change
            editor.getSession().on( 'change', function () {
                $editorTextarea.val( editor.getSession().getValue() );
            });

            // Get data on load
            editor.getSession().setValue( $editorTextarea.val() );

        });

    });

}(jQuery));