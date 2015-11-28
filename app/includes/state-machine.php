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
			'transitions' => [
				'site-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-site-manager',
					'from' => ['submitted-for-review'],
					'users' => ['sman@mab.ae', 'nospam@mab.ae'],
					'conditions' => [
					],
				],
				'operation-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-operation-manager',
					'from' => ['approved-by-site-manager'],
					'users' => ['oman@mab.ae'],
					'conditions' => [
						function ($entity) { return $entity->amount <= 10; },
					],
				],
				'general-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-general-manager',
					'from' => ['approved-by-site-manager'],
					'users' => ['gman@mab.ae'],
					'conditions' => [
						//GM should be able to approve lower amounts too - not only $amount > 100000
					],
				],
				'procurement-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-procurement-manager',
					'from' => ['approved-by-operation-manager', 'approved-by-general-manager'],
					'users' => ['pman@mab.ae'],
					'conditions' => [
					],
				],
			],
			'callbacks' => [
				'after' => [
					'approved-by-procurement-manager' => [
						'do' => function($entity) { $entity->state='release-to-system'; $entity->save(); },
					],
				],
			],
		], //end material request flow
		
	'purchase-order' => 
		[
		], //end purchase order flow
];
