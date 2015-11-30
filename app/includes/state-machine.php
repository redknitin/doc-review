<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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
				'rejected',
			],
			'transitions' => [
				'rejected' => [
					'to' => 'rejected',
					'from' => ['submitted-for-review', 'approved-by-site-manager', 'approved-by-operation-manager', 'approved-by-general-manager', 'approved-by-assistant-procurement-manager', 'approved-by-procurement-manager', 'release-to-system'],
					'users' => ['nospam@localhost', 'sman@localhost', 'oman@localhost', 'gman@localhost', 'pman@localhost'],
					'conditions' => [
					],
				],
				'site-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-site-manager',
					'from' => ['submitted-for-review'],
					'users' => ['sman@localhost', 'nospam@localhost'],
					'conditions' => [
					],
				],
				'operation-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-operation-manager',
					'from' => ['approved-by-site-manager'],
					'users' => ['oman@localhost', 'nospam@localhost'],
					'conditions' => [
						function ($entity) { return $entity->amount <= 10; },
					],
				],
				'general-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-general-manager',
					'from' => ['approved-by-site-manager'],
					'users' => ['gman@localhost', 'nospam@localhost'],
					'conditions' => [
						//GM should be able to approve lower amounts too - not only $amount > 100000
					],
				],
				'procurement-manager-approval-for-budgeted-material-purchase' => [
					'to' => 'approved-by-procurement-manager',
					'from' => ['approved-by-operation-manager', 'approved-by-general-manager'],
					'users' => ['pman@localhost', 'nospam@localhost'],
					'conditions' => [
					],
				],
			],
			'callbacks' => [
				'after' => [
					'approved-by-procurement-manager' => [
						'do' => function($entity) { 
							$entity->state='release-to-system'; 
							$entity->ref_status='R'; 
							$entity->save();

							$connection = new AMQPStreamConnection(
								\Config::get('app.AmqpConnection')['ip'], 
								\Config::get('app.AmqpConnection')['port'], 
								\Config::get('app.AmqpConnection')['user'], 
								\Config::get('app.AmqpConnection')['pass']
							);
							$channel = $connection->channel();
							$channel->exchange_declare('MR', 'direct', false, false, false);
							$channel->queue_declare('UpdateMR', false, false, false, false);
							$msg = new AMQPMessage(
								json_encode(
									[
										'ref_id' => $entity->ref_id,
										'ref_status' => $entity->ref_status,
										'source' => 'procure-approve',
									]
								)
							);
							$channel->basic_publish($msg, 'MR', 'EAM');
							$channel->close();
							$connection->close();
						},
					],
					'rejected' => [
						'do' => function($entity) { 
							$entity->ref_status='C'; 
							$entity->save();

							$connection = new AMQPStreamConnection(
								\Config::get('app.AmqpConnection')['ip'], 
								\Config::get('app.AmqpConnection')['port'], 
								\Config::get('app.AmqpConnection')['user'], 
								\Config::get('app.AmqpConnection')['pass']
							);
							$channel = $connection->channel();
							$channel->exchange_declare('MR', 'direct', false, false, false);
							$channel->queue_declare('UpdateMR');
							$channel->queue_bind('UpdateMR', 'MR', 'EAM');
							$msg = new AMQPMessage(
								json_encode(
									[
										'ref_id' => $entity->ref_id,
										'ref_status' => $entity->ref_status,
										'source' => 'procure-approve',
									]
								)
							);
							$channel->basic_publish($msg, 'MR', 'EAM');
							$channel->close();
							$connection->close();
						},
					],
				],
			],
		], //end material request flow
		
	'purchase-order' => 
		[
		], //end purchase order flow
];
