<?php

return [
	// GLOBAL
    'solunes_path' => env('FOLDER_SOLUNES_PATH', 'vendor/solunes'),
	'vendor_path' => env('SOLUNES_PATH', 'vendor/solunes/master'),
	'blocked_activities' => [],
	'image_quality' => 85,
	'varchar_lenght' => 64,
	'error_report' => true,
	'send_notification_sms' => false,
	'send_notification_app' => false,
	'master_admin_id' => 1,
	'default_location' => '-16.495369;-68.134289',
	'master_dashboard' => true,
	'main_lang' => 'es',
    
    // PLUGINS
    'store' => false,

	// LIST
	'pagination_count' => 500,
	'table_pagination' => 'false',
	'table_pagination_count' => 25,
	
	// FORM
	'relation_fast_create_array' => [], // array de field names: 'name'
	'item_get_after_vars' => [], // array de nodos: 'node'
	'item_child_after_vars' => [],
	'item_post_after_item' => [],
	'item_post_after_subitems' => [],
	'item_post_redirect_success' => [],
	'item_post_redirect_fail' => [],
	'item_add_css' => [], // array debe contener el array de includes: 'example'=>['file']
	'item_remove_scripts' => [],
	'item_add_script' => [],
	'item_add_script_store' => [],

	// CUSTOM FUNC
	'get_page_array' => false,
    'before_migrate' => false,
    'after_migrate' => false,
	'before_seed' => false,
	'after_seed' => false,
	'after_login' => false,
	'custom_admin_get_list' => false,
	'custom_get_items' => false,
	'admin_menu_extras' => false,
	'admin_menu_extra_array' => [], // Incluir los IDs de link de los menús para ejecutar
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
	'select2' => true,

    // SERVICES
    'translation' => false,

];