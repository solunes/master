<?php

return [
	// GLOBAL
    'solunes_path' => env('FOLDER_SOLUNES_PATH', 'vendor/solunes'),
    'todotix_path' => env('FOLDER_TODOTIX_PATH', 'vendor/todotix'),
	'vendor_path' => env('SOLUNES_PATH', 'vendor/solunes/master'),
	'blocked_activities' => [],
	'image_quality' => 80,
	'varchar_lenght' => 64,
	'error_report' => true,
	'send_notification_sms' => false,
	'send_notification_app' => false,
	'master_admin_id' => 1,
	'default_location' => '-16.495369;-68.134289',
	'google_maps_key' => 'AIzaSyBaLzWbrRu2mktt_Ho3ejDUxRMss-51wBc',
	'master_dashboard' => true,
	'main_lang' => 'es',
	'socialite' => true,

	// APP VARS
	'app_name' => strtoupper(env('APP_NAME', 'Web App')),
	'app_version' => env('APP_VERSION', 'v1.0.0'),
	'app_color' => '#'.env('APP_COLOR', '4c99bf'),

    // PLUGINS
    'store' => false,
    'business' => false,
    'sales' => false,
    'project' => false,
    'product' => false,
    'inventory' => false,
    'accounting' => false,
    'payments' => false,
    'services' => false,
    'accounting' => false,
    'reservation' => false,
    'notification' => false,
    'pagostt' => false,
    'customer' => false,
    'todotix-customer' => false,

	// PACKAGES
	'alerts' => true,
	'indicators' => true,

	// LIST
	'pagination_count' => 500,
	'table_pagination' => 'false',
	'table_pagination_count' => 25,
	'list_inline_edit' => false,
	'list_export_pdf' => true,
	'filter_subptions' => true,
	'filter_subptions_exceptions' => [],

	// GLOBAL
	'login_instructions' => false,
    'admin_initial_menu' => [
        'login'=> true,
        'password_recover'=> true,
        'dashboard'=> false,
        'my_account'=> true,
        'my_profile'=> true,
        'logout'=> true
    ],
	'admin_inbox_disabled' => false,
	'admin_inbox_disabled' => false,
	'admin_inbox_excluded' => ['member'], // Incluir roles a ser excluidos del inbox, por defecto member

	// FORM
	'nocaptcha_login' => false,
	'excel_import_select_labels' => true,
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
	'custom_admin_node_actions' => false,
	'custom_admin_field_actions' => false,
	'custom_admin_get_list' => false,
	'custom_admin_get_item' => false,
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
    'sms_notification' => false,
    'email_notification' => true,
    'push_notification' => false,

];