$(document).ready(function(){
	$('a[href^="#"]').on('click',function (event) {
	    event.preventDefault();
	    var target = this.hash,
	    $target = $(target);
	    $('html, body').stop().animate({
	        'scrollTop': $target.offset().top
	    }, 800, function () {
	        window.location.hash = target;
	    });
	});

	var target 	= window.location.hash,
		$target = $(target);

	$('html, body').stop().animate({
		'scrollTop': $target.offset().top
	}, 0, 'jswing');

	$('#contactForm').on('submit', function(event){
		event.preventDefault();

		var $form = $(this);
		var $button = $form.find('button');
		$button.text('Sending message...');

		$.ajax({
			type: 'post',
			url: $(this).attr('action'),
			data: {
				name: $('#name').val(),
				email: $('#email').val(),
				message: $('#message').val(),
				submit: $('#submit').val()
			},
			dataType: 'json', 
			success: function(response) {
				if (response.code == 203) {
					$list = $('<ul/>');
					$.each(response.errors, function(index, value){
						var $item = $('<li/>', {
							html: value
						}).appendTo($list);
					});
					$form.find('div.messages').html($list).addClass('error');
				}
				else if (response.code == 201) {
					$form.find('div.messages').html('<p>Thanks, your message has been sent. Please allow 48 hours of the message being received for me to get back to you.</p>').addClass('success');
					$form[0].reset();
				}
				$button.text('Send Message');
			}
		});
	});

	$('#gallery').cycle({
		fx		: 'scrollHorz',
		timeout : 0,
		prev 	: '#previous',
		next	: '#next'
	});
});