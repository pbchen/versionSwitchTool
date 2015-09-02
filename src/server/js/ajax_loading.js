function Load(){
	this.init();
}
Load.prototype = {
	
	init: function (){
		$Div = $('<div id="loading"><img src="img/ajax-load.gif"/></div>');
	}

	,blockUI: function () {
		  $Div.prependTo('body');
		  $('#loading').css({
				'z-index': 1000,
				'position': 'fixed',
				'top': 0,
				'left': 0,
				'width': '100%',
				'height': '100%',
				'background': 'rgba(0,0,0,0.5)'
		  });
		$('#loading img').css({
				'top': '50%',
				'left': '50%',
				'position': 'absolute'
		});
	}
	// wrapper function to  un-block element(finish loading)
	,unblockUI: function () {
		 $Div.remove();
	}
}

$(function(){
    Load = new Load();
})