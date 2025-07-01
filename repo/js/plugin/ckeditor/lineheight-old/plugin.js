if (!CKEDITOR.plugins.get('lineheight')) {
    CKEDITOR.plugins.add('lineheight', {
        init: function (editor) {
            editor.ui.addRichCombo('lineheight', {
                label: 'Line Height',
                title: 'Line Height',
                voiceLabel: 'Line Height',
                toolbar: 'styles',
                panel: {
                    css: [CKEDITOR.skin.getPath('editor')].concat(CKEDITOR.skin.getPath('editor', 'panel')),
                    multiSelect: false
                },
                init: function () {
                    // Add line height options
                    this.add('remove', 'Remove');
                    this.add('1', '1');
                    this.add('1.5', '1.5');
                    this.add('2', '2');
                    this.add('2.5', '2.5');
                    this.add('3', '3');
                    this.add('3.5', '3.5');
                    this.add('4', '4');
                    this.add('4.5', '4.5');
                    this.add('5', '5');
                },
                onClick: function (value) {
                    console.log('Line height selected:', value); // Check if this logs
                   // element: '*', // Apply to any element
                    var elements = ['*']; // List of elements to apply line height
                    elements.forEach(function(element) {
                        var style = new CKEDITOR.style({
                            element: element,
                            attributes: { 'style': 'line-height:' + value + ';' }
                        });
                        editor.applyStyle(style);
                    });

                    /*editor.focus();
                    var style = new CKEDITOR.style({
                        element: 'p',
                        attributes: { 'style': 'line-height:' + value + ';' }
                    });
                    editor.applyStyle(style);*/
                    
                    /*var style;
                    if (value === 'remove') {
                        style = new CKEDITOR.style({
                            element: '*', // Apply to any element
                            attributes: { 'style': 'line-height: normal;' }
                        });
                    } else {
                        style = new CKEDITOR.style({
                            element: '*', // Apply to any element
                            attributes: { 'style': 'line-height:' + value + ';' }
                        });
                    }
                        */
                    editor.fire('saveSnapshot'); // Save state for undo
                },
                onShow: function () {
                    console.log('on show Line height selected:', value); 
                    var lineHeight = getCurrentLineHeight(editor);
                    this.setValue(lineHeight); // Set the dropdown value to the current line height
                }
            });

            // Function to get the current line height from the selected text
            function getCurrentLineHeight(editor) {
                var selection = editor.getSelection();
                if (!selection) {
                    return '1'; // Default line height if no selection
                }

                var range = selection.getRanges()[0];
                var commonAncestor = range.getCommonAncestor();
                var style = window.getComputedStyle(commonAncestor);
                var lineHeight = style.lineHeight || '1'; // Default to '1' if not set

                return lineHeight;
            }
        }
    });

}