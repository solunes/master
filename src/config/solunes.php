<?php

return [
	// GLOBAL
	'vendor_path' => env('SOLUNES_PATH', 'vendor/solunes/master'),
	'blocked_activities' => [],
	'image_quality' => 85,
	'error_report' => true,
	'send_notification_sms' => false,
	'send_notification_app' => false,
	'master_admin_id' => 1,

	// LIST
	'pagination_count' => 500,
	'table_pagination' => 'false',
	'table_pagination_count' => 25,

	// CUSTOM FUNC
	'get_page_array' => false,
    'before_migrate' => false,
    'after_migrate' => false,
	'before_seed' => false,
	'after_seed' => false,
	'after_login' => false,
	'custom_get_items' => false,
	'list_extra_actions' => false,
	'get_sitemap_array' => false,
	'get_indicator_result' => false,
	'update_indicator_values' => true,
	'custom_indicator_values' => false,
	'indicator_custom_count' => false,
	'dyanmic_form_create_menu' => false,
	'check_permission' => false,
	'custom_indicator' => false,
	'custom_field' => false,
	'get_options_relation' => false,
	'check_custom_filter' => false,
	'custom_filter' => false,
	'custom_filter_field' => false,
	'custom_pdf_header' => false,
];