

<script>
 CKEDITOR.replace('{$element}', {
	toolbar : [
    { name: 'clipboard',   
      groups: [ 'clipboard', 'undo' ], 
      items: [ 'SelectAll', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'basicstyles', 
      groups: [ 'basicstyles', 'cleanup' ], 
      items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] 
    },
		{ name: 'colors', 
      items: [ 'TextColor', 'BGColor' ] 
    },
    { name: 'paragraph', 
      groups: [ 'list', 'indent', 'blocks', 'align' ], 
      items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] 
    },
    { name: 'insert', 
      items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'SpecialChar', 'PageBreak' ] 
    },
    { name: 'links', 
      items: [ 'Link', 'Unlink', 'Anchor' ] 
    },
		{ name: 'editing', 
      groups: [ 'find', 'selection' ], 
      items: [ 'Find', 'Replace' ] 
    },
		{ name: 'document',	
      items: [ 'Source', '-','Print' ] 
    }
	]
  });
</script>

