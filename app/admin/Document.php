<?php
Admin::model(App\Document::class)
	->title('Document')
	->with()
	->filters(function() {})
	->columns(function() {
		Column::string('state', 'Status');
		Column::string('description', 'Description');
		Column::string('amount', 'Amount');
		Column::date('due', 'Due On');
		Column::string('ref_id', 'Ref. ID');
		Column::string('ref_status', 'Ref. Status');

	})
	->form(function() {
		FormItem::text('state', 'Status');
		FormItem::text('description', 'Description');
		FormItem::text('amount', 'Amount');
		FormItem::date('due', 'Due On');
		FormItem::text('ref_id', 'Ref. ID');
		FormItem::text('ref_status', 'Ref. Status');
	})
	;