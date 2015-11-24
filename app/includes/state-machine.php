<?php
	global $state_machine;
	$state_machine = [
		'material-request' =>
			[
				'states' => [
					'submitted-for-review',
					'approved-by-site-manager',
					'approved-by-operation-manager',
					'approved-by-general-manager',
					'approved-by-assistant-procurement-manager',
					'approved-by-procurement-manager',
					'release-to-system',
				],
				'roles' => [
					'site-manager',
					'operation-manager',
					'general-manager',
					'assistant-procurement-manager',
					'procurement-manager',
					'ceo',
					'sysadmin',
				],
				'state_meta' => [
					'initial' => [
						'state' => 'submitted-for-review',
						'external_state_code' => 'U',
					],
					'final' => [
						'state' => 'release-to-system',
						'external_state_code' => 'R',
					],
				],
				'transitions' => [
					'site-manager-approval-for-budgeted-material-purchase' => [
						'to' => 'approved-by-site-manager',
						'from' => ['submitted-for-review'],
						'roles' => ['site-manager'],
						'conditions' => [
						],
					],
					'operation-manager-approval-for-budgeted-material-purchase' => [
						'to' => 'approved-by-operation-manager',
						'from' => ['approved-by-site-manager'],
						'roles' => ['operation-manager'],
						'conditions' => [
							function ($entity) { return $entity->amount <= 10; },
						],
					],
					'general-manager-approval-for-budgeted-material-purchase' => [
						'to' => 'approved-by-general-manager',
						'from' => ['approved-by-site-manager'],
						'roles' => ['general-manager'],
						'conditions' => [
		//GM should be able to approve lower amounts too
		//					'$amount > 100000'
						],
					],
					'procurement-manager-approval-for-budgeted-material-purchase' => [
						'to' => 'approved-by-procurement-manager',
						'from' => ['approved-by-operation-manager'],
						'roles' => ['procurement-manager'],
						'conditions' => [
						],
					],
				],
				'callbacks' => [
					'before' => [
						'approved-by-site-manager' => [
							//TODO: Apply filter for from state
							//'from' => [''],
							'do' => function() {},
						],
					],
					'after' => [
						'approved-by-site-manager' => [
							//TODO: Apply filter for from state
							//'from' => [''],
							'do' => function() {},
						],
					],
				],
			], //end material request flow
		'purchase-order' => 
			[
			]
	];
