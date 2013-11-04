<?php

class index_controller extends base_controller {
	
	/*-------------------------------------------------------------------------------------------------

	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
		parent::__construct();
	} 
		
	/*-------------------------------------------------------------------------------------------------
	Accessed via http://localhost/index/index/
	-------------------------------------------------------------------------------------------------*/
	public function index() {

		# Any method that loads a view will commonly start with this
		# First, set the content of the template with a view file
			$this->template->content = View::instance('v_index_index');
			
		# Now set the <title> tag
			$this->template->title = "gruntr";
	
		# CSS/JS includes

			$client_files_head = Array(
                '/css/index.css'
            );
	    	$this->template->client_files_head = Utils::load_client_files($client_files_head);

	      					     		
		# Render the view
			echo $this->template;

	} # End of method

    public function additional_features() {
        $this->template->content = View::instance('v_additional_features');
        $this->template->title = "+1";
        $client_files_head = Array(
            '/css/plus_one.css'
        );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);
        echo $this->template;




    }
	
	
} # End of class
