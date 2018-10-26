jQuery(function($){
	/*Phone mask*/
    $(".fruitPhone").mask("+7 (999) 999-99-99");
    /*Form animate*/
    $( ".input" ).focusin(function() {
        $( this ).find( "span" ).animate({"opacity":"0"}, 200);
    });
    $( ".input" ).focusout(function() {
        $( this ).find( "span" ).animate({"opacity":"1"}, 300);
    });
	/*Form send*/
	$(".fruitForm").submit(function() {
		var th = $(this);
		$.ajax({
			type: "POST",
			url: "/QupeForm/mail.php",
			data: th.serialize()
		}).done(function() {
			setTimeout(function() {
			}, 1000);
		});
        /*Form success*/
        var grecaptchaID = $(this).find(".grecaptcha-wrap").attr("id");
        var grecaptchaObject=eval("("+grecaptchaID+")");
        var response = grecaptcha.getResponse(grecaptchaObject);
        if(response.length == 0) {
            //reCaptcha not verified
            alert('Проверка Google reCaptcha не пройдена! Пожалуйста нажмите кнопку "Я не робот".');
        } else {
            //reCaptcha verified
            $(this).find(".submit i").removeAttr('class').addClass("fa fa-check").css({"color":"#fff"});
            $(".submit").css({"background":"#2ecc71", "border-color":"#2ecc71"});
            $(".fruitSuccess").show().animate({"opacity":"1", "bottom":"-90px"}, 400);
            $("input").css({"border-color":"#2ecc71"});
            th.trigger("reset");
        }
		return false;
	});
    /*Form Modal*/
    $(".fruitModal-close").click(function() {
        window.location.hash = '';
        history.pushState("", document.title, window.location.pathname);
    });
});



