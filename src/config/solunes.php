<?php

return [
	// GLOBAL
	'vendor_path' => env('SOLUNES_PATH', 'vendor/solunes/master'),

	// CUSTOM FUNC
	'get_page_array' => false,
    'before_migrate' => false,
    'after_migrate' => false,
	'before_seed' => false,
	'after_seed' => false,
	'get_sitemap_array' => false,
	'get_indicator_result' => false,
	'update_indicator_values' => false,
	'add_node_array' => false,
	'check_permission' => false,
	'custom_node_request' => false,
	'custom_indicator' => false,
	'custom_field' => false,
	'check_custom_filter' => false,
	'custom_filter' => false,
	'custom_filter_field' => false,
	'custom_pdf_header' => false,
];