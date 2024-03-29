<?php
class addressbook_autocomplete extends rcube_plugin {
		
		public $task = 'addressbook|mail';
		private $rc;
		private $contacts;
		
		function init() {
			$this->require_plugin('jqueryui');
			$this->rc = rcmail::get_instance();
			$this->contacts = $this->rc->get_address_book(-1);
			
			//Register actions
			$this->register_action('plugin.autocomplete-get-x', array($this, 'autocomplete_get'));
			$this->register_action('plugin.autocomplete-get-mails-x', array($this, 'autocomplete_get_mails'));
			
			if($this->rc->task == 'addressbook' || $this->rc->task == 'mail') 
			{
				$this->init_ui();
			}
		}

		function init_ui()
		{
			if ($this->ui_initialized) {
				return;
			}

			$this->include_script('addressbook_autocomplete.js');
			$this->ui_initialized = true;
			
		}
		
		
		/* Get suggestions for a certain string in parameter
		** @param String 	value to research
		*/
		function autocomplete_get() 
		{
			if(!$this->ui_initialized)
				return null;

			$query_term =  rcube_utils::get_input_value('_term', RCUBE_INPUT_POST);
			$set = $this->contacts->search('*', $query_term, 0);
			
			$result = $this->parse_results($set, 3);
			$this->rc->output->command('plugin.callback_autocomplete', $result);
			$this->rc->output->send();
		
		}
		
		function autocomplete_get_mails() {
			if(!$this->ui_initialized)
				return null;

			$query_term = rcube_utils::get_input_value('_q', RCUBE_INPUT_POST);
			$subject['subject'] = 'HEADER SUBJECT';
			

		}


		/* Parse results from search query for jQueryUI autocompletion
		** @param rcube_result_set	result set from search query
		** @param int		number MAX of records to return
		** @return array	
		*/
		private function parse_results($set, $nb) 
		{
			if(!$this->ui_initialized)
				return null;

			$length = min($nb, $set->count);
			$records = $set->records;
			$result = array();
			for($i = 0; $i < $length; ++$i) {
				array_push($result, $records[$i]['name']);
			}
			return $result;
		}
}

