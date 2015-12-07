<?php

/**
 *  Used to manage state transitions for a workflow
 */
class WorkflowInstance {
	/**
	 *  The current workflow state of the instance
	 *  @var string
	 */
	var $current_state;
	
	/**
	 *  The ID the instance. Can use int but it's only going to be treated as a string
	 *  @var string
	 */
	var $id;
	
	/**
	 *  The entity instance object; this will have work done upon it by the callbacks and conditionals
	 *  @var stdClass
	 */
	var $data;
	
	/**
	 *  The name of the workflow
	 *  @var string
	 */
	var $workflow;
	
	/**
	 *  Constructor for WorkflowInstance to initialize workflow name, instance ID, current state, and the entity instance object
	 *  @param string $workflow Name of the workflow
	 *  @param string $id ID of the instance
	 *  @param string $current_state The current state of the instance
	 *  @param mixed $data The entity instance object
	 */
	public function __construct($workflow, $id, $current_state, $data) {
		$this->workflow = $workflow;
		$this->id = $id;
		$this->current_state = $current_state;
		$this->data = $data;
	}
	
	/**
	 *  Fetches an array of states that this workflow instance can transition to
	 *  @param string $username
	 *  @return string[]
	 */
	public function getTransitionStates($username) { //TODO: Add support for roles, $rolename=null
		$states_to_transition = [];
		global $state_machine;
		
		foreach($state_machine[$this->workflow]['transitions'] as $transkey=>$itertrans) {
            $possible_trans = false;
            foreach($itertrans['from'] as $iter_from_state) {
                if ($iter_from_state==$this->current_state) {
                    $possible_trans = true;
                    break;
                }
            } // end foreach from-states

            if (!$possible_trans) {
                continue;
            }
            else 
            {
                foreach ($itertrans['conditions'] as $trialbyfire) {
                    if (!$trialbyfire($this->data)) {
                        $possible_trans = false;
                        continue;
                    }
                } //end foreach conditions

                if (!in_array($username, $itertrans['users'])) {
					$possible_trans = false;
				}

                if ($possible_trans) {
                    $states_to_transition[] = $itertrans['to'];
                }
            } //end else
        } //end foreach transitions
		
		return $states_to_transition;
	} // end function get_state_transitions
	
	/**
	 *  Transitions to the new state
	 *  @param string $new_state
	 *  @return void
	 */
	public function setState($new_state) {
		global $state_machine;
		
		$this->state = $new_state;

		if (isset($state_machine[$this->workflow]['callbacks'])) {
			$callbacks = $state_machine[$this->workflow]['callbacks'];
			if (isset($callbacks['after'])) {
				$after_callbacks = $callbacks['after'];
				if (isset($after_callbacks[$new_state])) {
					$specific_after_callbacks = $after_callbacks[$new_state];
					if (isset($specific_after_callbacks['do'])) {
						$do_specific_after_callbacks = $specific_after_callbacks['do']; //get the function
						$do_specific_after_callbacks($this->data); //call the function
					} // end if the 'do' function for the after callback for the new state is set
				} // end if the after callback for the new state is set
			} // end if after callbacks is set
		} //end if callbacks is set
		
	}
}

