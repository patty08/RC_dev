rcmail.addEventListener('init', function(evt) {
	if(rcmail.task == 'addressbook')
	{
		rcmail.addEventListener('plugin.callback_autocomplete', callback_autocomplete);
		$('#quicksearchbox').on('keyup', search_query);
		$('#quicksearchbox').autocomplete({
			minLength: 0,
			source: [],
			select: function(event,ui) {
				$('#quicksearchbox').val(ui.item.value);
				rcmail.command('search'); 
				return false;
			}
		});
	}
	else if (rcmail.task == 'mail') {
		rcmail.addEventListener('plugin.callback_autocomplete_mail', callback_autocomplete_mail);
		rcmail.http_post('plugin.autocomplete-get-x', '_q='+$('#quicksearchbox').val());
	}
	else 
	{
		rcmail.removeEventListener('plugin.callback_autocomplete', callback_autocomplete);
	}
});

function callback_autocomplete (response) {
	$('#quicksearchbox').autocomplete('option', 'source', response);
	$( "#quicksearchbox" ).autocomplete( "search", $('#quicksearchbox').val());
}

function search_query() {
	lock = rcmail.set_busy(true, 'loading');
	rcmail.http_post('plugin.autocomplete-get-x', '_term='+$('#quicksearchbox').val(), lock);
}

function callback_autocomplete_mail (response) {
	console.log(response);
}
