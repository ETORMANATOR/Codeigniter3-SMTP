tinymce.init({
  selector: 'textarea',
  height: 800,
  plugins: "code preview textcolor colorpicker link image autolink autoresize codesample fullscreen charmap imagetools searchreplace wordcount visualchars table advtable emoticons anchor lists advlist checklist linkchecker pageembed a11ychecker tinymcespellchecker media mediaembed",
  toolbar1: "|a11ycheck|fontselect | styleselect | fontsizeselect| forecolor backcolor ",
  toolbar2: "| undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent checklist",
  toolbar3: 'advtablerownumbering',
  toolbar4: "emoticons link image code preview myButton codesample fullscreen charmap anchor searchreplace wordcount pageembed ",
	//toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code image_upload",
	menubar: 'file edit insert view format table tools help',
    statusbar: false,
    fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
    contextmenu_avoid_overlap: '.mce-spelling-word',
    contextmenu: 'link image table',
    browser_spellcheck: true,
    gecko_spellcheck: false,
    file_picker_types: 'file image media',
    spellchecker_language: 'en'

});