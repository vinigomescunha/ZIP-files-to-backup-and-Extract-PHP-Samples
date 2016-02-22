$(document).on('submit', "#upload", function(e) {
	e.preventDefault();
	$.ajax({
		url: "sample.web.php",
		type: "POST",
		data: new FormData(this), 
		contentType: false, 
		cache: false, 
		processData: false, 
		success: function( data ) { 
			$("#messages span").html(data);
		}
	});
});

$(document).on('click', '#more', function() {
	var size = parseInt($('#inputs .upload-files').size());
	size++;
	$('#inputs').append('<div class="ui action input big upload-files">\
				  <label class="labelupload" for="file' + size + '">\
					<i class="cloud big icon"></i>\
					<span></span>\
					<input type="file" name="files[]" id="file' + size + '" style="display: none" multiple />\
				</label>\
			    </div>');
});

$(document).on('click', '#minus', function() {
	var size = parseInt($('#inputs .upload-files').size());
	size--;
	if(size) 
		$('#inputs .upload-files').get(size).remove();
});

$(document).on('change', '.labelupload', function() {
	var lfor = $(this).attr("for"),
	names = $($('#' + lfor).get(0).files);
	if(names.length > 1) {
		name = "";
		for(var i=0; i < names.length; i++) {
			name += names[i].name + " | ";
		}
	} else name = names[0].name;
	$($(this)).find('span').html(name);
});
