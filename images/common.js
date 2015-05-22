function showToolTip(content){
var fullcontent = '<div class="tooltip" style="position: absolute; width: 200px; height: 50px; background: url(\'http://content.markettraders.com/markettraders/images/tooltip.png\') no-repeat center center; border: 1px solid red; top: 0px; background: #fff; left: 0px;">'+content+'</div>';
  jq('#tooltip').hover(function(){

jq('#tooltip').parent().append(fullcontent);
    jq('.tooltip').animate({
     top: '+=15',
     opacity: 0.75,
     }, 
     100
     );
    jq('.tooltip').hover(function(){
jq(this).show();
},
function(){
jq(this).hide();
jq('.tooltip').remove();

})
  
},
function(){
jq('.tooltip').hide();
jq('.tooltip').remove();

});

}


jq(function(){
jq('.hidden1, .hidden2').hide();
var eAddress = '';
var emailRegex = /^[A-Za-z][A-Za-z0-9\._-]*@[A-Za-z0-9\.-]+\.[A-Za-z]{2,4}$/;

jq('#cont-edu-email').blur(function(){
		if (jq('#cont-edu-email').val().length < 5 || !jq('#cont-edu-email').val().match(emailRegex))
		{
			emailValid = false;
			
		}
		else
		{
			emailValid = true;
			
		}
	});

jq('#tip5').click(function(){
jq('#cont-edu-email').blur();
if(!emailValid){
alert('Please enter a valid email address!');
}
else{
var autoPop = jq('#cont-edu-email').val();
jq('#email').val(autoPop);
eAddress = autoPop;
sendAddy = 'email='+autoPop;
jq('.hidden1, .hidden2').fadeIn(400);
jq('table#sfForm tr:nth-child(4) .sfTitle').append('<div class="echeck"></div>');
jq('.echeck').html('Checking email address: <span id="checking"><img src="http://content.markettraders.com/general/images/ajax-loader.gif"></span>');
jq.ajax({
url: '/wp-content/themes/twentyeleven/inc/checksfacct.php',
type: 'POST',
data: sendAddy,
success: function(msg){jq('#checking').html(msg);
},
error: function(){ alert('failed');}

});
}

});

jq('.hidden2, #closeme').click(function(){
jq('.echeck').remove();
jq('.hidden2, .hidden1').fadeOut(400);
});

jq('#email').blur(function(){
jq('#checking').html('<img src="http://content.markettraders.com/general/images/ajax-loader.gif">');
jq.ajax({
url: '/wp-content/themes/twentyeleven/inc/checksfacct.php',
type: 'POST',
data: 'email='+jq(this).val(),
success: function(msg){jq('#checking').html(msg);
},
error: function(){ alert('An error occurred. Please try again later!');}

});
});
});






