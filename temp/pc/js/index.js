
	$(document).ready(
		
		TIMER = function () {
			
				var timer 	= $('span.time').text();
				var url 	= $('input[type=hidden]').attr('link');
				var name 	= $('input[type=hidden]').attr('name');
				var value 	= $('input[type=hidden]').attr('value');

				if(timer >= 1){
					
					$('span.time').html(parseInt(timer)-1);

					setTimeout('TIMER()', 1000);
					
				}else{
					
					$.post(url, {sid:value,hash:name}, function(result){

						if(result.link){
							window.location.href=result.link;
						}
						if(result.error){
							$('div.link_block').html('<div class="error">' + result.error + '</div>');
						}
					}, 'json');
				}
				
				return true;

		}
	);
	
	$(document).ready(
		
		data = function () {
			
			var time = $('span.srv_date').attr('alt');
			$('span.srv_date').removeAttr('alt');
			
			var new_time = parseInt(time)+1;
			var new_date = moment(parseInt(time) * 1000).format("DD MM YYYY, HH:mm:ss");

			$('span.srv_date').html(new_date);
			$('span.srv_date').attr('alt',new_time);
			
			setTimeout('data()', 1000);
	});