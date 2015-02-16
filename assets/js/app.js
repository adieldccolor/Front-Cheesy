var app, timers = [], item = 0;

function hasError(obj){
	return (obj.error != undefined || ( typeof obj != "object" && obj.indexOf('<br') > -1 ));
}

app = {
	init: function(){
		this.list();

		$('body').on('click', '.start', function(){
			app.cola();
			$(this).animatecss('fadeOutUp');
		}).on('click', '.reload', function(){
			window.location.reload();
		});
	}, list: function(){
		$.ajax({
			url: 'image/list',
			success: function(data){
				hasError(data)==false&&app.appendList(data);
				if(hasError(data)){
					$('.reload').removeClass('hidden');
					$('.list ul').html('<h3>No hay imagenes para recortar o todas han sido recortadas.</h3>');
				}
			}
		});

		return "Loading list...";
	}, imageStatus: function(progress, current){
		var ul = $('.list ul');
		var $item = ul.find('li').eq(current);
		$item.find('.state').text(progress + '%');
		$item.find('.progress').css({ width: progress + '%' });


	}, start: function(){
		$('.header .status').animatecss('bounceInDown');
	}, complete: function(){
		$('.header .status').animatecss('bounceOutUp');
	}, appendList: function(data){
		var ul = $('.list ul');

		$('.start').removeClass('hidden');

		for( i = 0; i < data.length; i++ ){
			ul.append('<li><span class="file">' + data[i] +'</span><span class="state">0%'
				+ ' - ready to start</span> <span class="progress"></span></li>');
		}
	}, cola: function(){
		var ul = $('.list ul'), file = "";
		if( ul.find('li').length > 0 ){

			/* check if there are more than 1 and cola is advancing, so get next */
			if( ul.find('li.done').length > 0 ){
				if( ul.find('li.done').last().next().length > 0 ){
					file = ul.find('li.done').last().next().find('.file').text();
					item = ul.find('li.done').last().next().index();
				}else{
					app.finished();
				}
			/* else, get first  */
			}else{
				file = ul.find('li').first().find('.file').text();
			}

			if( ul.find('li.done').length < ul.find('li').length ){
				/* send request */
				ul.find('li').eq(item).addClass('loading');
				app.loadImage(item, file);
			}
		}else{
			return false;
		}
	}, finished: function(){
		console.log("all images done");
		$('.reload').removeClass('hidden').animatecss('fadeInDown');
	}, loadImage: function(current, url){
		var ul = $('.list ul');

		$.ajax({
			url: '?file=' + url,
			success: function(data){
				console.log(data);
				if( !hasError(data) ){
					ul.find('li').eq(item).removeClass('loading').addClass('done');
					app.cola();
				}else{
					ul.find('li').eq(item).removeClass('loading').addClass('done error');
					console.log('error');
				}
			}, xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        //Do something with download progress
                        percentComplete = parseInt(percentComplete * 100);
                        app.imageStatus(percentComplete, current);
                    }
                }, false);
                return xhr;
            }
		});
	}
};


/* init app on dom ready */
$(document).on('ready',function(){ app.init(); })
	.ajaxStart(function(){ app.start(); }).ajaxComplete(function(){ app.complete(); });