function getForm($formId, $type) {
    var form = "<form id='" + $formId + "' action='/mailer/mailer.php' method='post'>" +
               "<input name='t' type='hidden' value='" + $type + "' />" +
               "<input name='name' type='text' placeholder='Имя' required='require'/>" +
               "<input name='telephone' type='tel' placeholder='Телефон' required='require' min='11' max='11'/></br></br>" +
               "<input class='btn btn-succes' type='submit'/>" +
               "</form>";
	return form;
}

function showForm(_this, formID, typeForm) {
	$('.liteForm').html('<a class="closeLiteForm" href="#">x</a>' + getForm(formID, typeForm)).show(100);
	$('.closeLiteForm').click(function () { $('.liteForm').hide(100); return false; });
	var top = _this.offset().top;
	var left = _this.offset().left + _this.width();
	$('.liteForm').css({
		"top": top + "px",
		"left": left + "px"
	});
	$('#' + formID).submit(function () {
		var _this = $(this);
		$.ajax({
				type: "GET",
				url: "/mailer/mailer.php",
				data: _this.serialize(),
				success: function (data) {

					$('.liteForm').hide(100);
					if (data == true) {
						alert('Спасибо. В скором времени мы с вами свяжемся!');
					} else {
						alert('Кажется что то пошло не так. Попробуйте еще раз или зайдите позже');
					}

				}
		});
		return false;
	}); //end submit

}

function sendStaticForm(_this) {
	$.ajax({
			type: "GET",
			url: "/mailer/mailer.php",
			data: _this.serialize(),
			success: function (data) {
				if (data == true) {
					_this.find('[type="submit"]').replaceWith('<p class="success-message">Спасибо. В скором времени мы с вами свяжемся!</p>');
				} else {
					_this.append('<p class="error-message">Кажется что то пошло не так. Попробуйте еще раз или зайдите позже</p>');
                    _this.find('[type="submit"]').val('Отправить еще раз').removeAttr('disabled');

				}
			}
	});
}

$('body').append('<div class="liteForm"></div>');
$('.liteForm').hide();

	
$('#getConverterService').submit(function (e) {
	var _this = $(this);
    e.preventDefault();
	_this.find('[type="submit"]').val('Отправка...').attr('disabled', 'true');
	sendStaticForm(_this);
}); //end click

$('.getService').click(function () {
	var _this = $(this);
	var formName = 'service';
	showForm(_this, 'service', 'getService');
	var tdService = $(this).parent().parent().children('td:first').text();
	var nameService = tdService;
	$('#' + formName).prepend('<input name="nameService" type="hidden" value="' + nameService + '">');
	console.log(nameService);
	return false;
});

$('#getServicePc').submit(function () {
	var _this = $(this);
	sendStaticForm(_this);
	return false;
}); //end submit

$('#getServiceNotebook').submit(function () {
	var _this = $(this);
	sendStaticForm(_this);
	return false;
});

$('#callBack').submit(function () {
	var _this = $(this);
	sendStaticForm(_this);
	return false;
}); //end click

$('#callBackSPB').submit(function () {
	var _this = $(this);
	sendStaticForm(_this);
	return false;
}); //end click