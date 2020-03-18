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
	'send_notification_whatsapp' => false,
	'send_notification_app' => false,
	'master_admin_id' => 1,
	'master_dashboard' => true,
	'main_lang' => 'es',
	'socialite' => false,
	'test_enabled' => false,
	'scheduler_url' => 'https://scheduler.solunes.site/api',
	'scheduler_api_key' => null, // Generar código
	'content_images_table' => false, // Crear tabla de imagenes para contenido variado
    'storage_webp_enable'=>false, // Habilitar WebP en Storage
    'storage_webp_check_exists'=>false, // Habilitar en WebP que se revise cada archivo antes de generarlo
    'storage_webp_regenerate_all'=>false, // Habilitar en WebP que se revisen todos los archivos
    'storage_webp_upload_cloud'=>false, // Habilitar subida a la nube de WebP
    'storage_webp_mass_convert'=>false, // Habilitar subida a la nube de WebP
    'storage_webp_public_folders'=>[], // Habilitar array de folder para validar webpg. Ej: assets/images
    'storage_return_asset'=>false, // Habilitar función asset () en Storage por defecto
	'redirect_login_customer' => true,

	// SUBADMIN
	'customer_dashboard' => false,
	'customer_dashboard_nodes' => ['customer'=>'edit'],
    'customer_dashboard_filters' => true,
	'customer_dashboard_custom_filters' => false,

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
    'payments' => false,
    'staff' => false,
    'customer' => false,
    'reservation' => false,
    'notification' => false,
    'services' => false,
    'accounting' => false,
    'pagostt' => false,
    'todotix-customer' => false,

    // SOCIALITE
    'socialite_google' => true,
    'socialite_facebook' => true,
    'socialite_twitter' => false,
    'socialite_github' => false,

	// PACKAGES
	'alerts' => true,
	'indicators' => true,
	'indicator_total_count' => true,

	// BACKUP
	'enable_backup' => true,
	'enable_solunes_defaults' => true,
	'enable_backup_files' => false, // And database
	'enable_backup_schedule' => false,

	// LIST
	'pagination_count' => 500,
	'subadmin_pagination_count' => 100,
	'list_horizontal_scroll' => false,
	'list_vertical_scroll' => 0, // En pixeles
	'table_pagination' => 'false',
	'table_pagination_count' => 25,
	'subadmin_table_pagination' => 'true',
	'subadmin_table_pagination_count' => 10,
	'list_inline_edit' => false,
	'list_export_pdf' => true,
	'filter_suboptions' => true,
	'filter_suboptions_exceptions' => [],
	'delete_item_custom_message' => false,

	// MAP SERVICES
	'google_maps_key' => 'AIzaSyBaLzWbrRu2mktt_Ho3ejDUxRMss-51wBc',
	'default_location' => '-16.495369;-68.134289',
	'default_map' => 'google.maps.MapTypeId.ROADMAP',
	'default_zoom' => '17',
	'default_map_height' => '500px',

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
	'admin_inbox_disabled' => true,
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
	'item_form_add_html_before_form' => false, // Añadir true si se agregara contenido antes del formulario
	'item_form_add_html_before_button' => false, // Añadir true si se agregara contenido antes del boton
	'item_form_add_html_after_form' => false, // Añadir true si se agregara contenido despues del formulario

    // PDF ATTRIBUTES
    'pdf_header' => true,
    'pdf_footer' => true,
    'pdf_custom_data' => false,
    'pdf_default_paper' => 'letter',
    'pdf_margin_top' => '70mm',
    'pdf_margin_bottom' => '20mm',
    'pdf_margin_right' => '30mm',
    'pdf_margin_left' => '30mm',

	// DASHBOARD ADMIN
    'dashadmin_container' => true,
    'dashadmin_title' => true,
    'dashadmin_layout' => 'layouts/master',
    'dashadmin_nodes' => [], // ['user'=>['default'=>['name']]]
    'dashadmin_custom_redirect' => [], // ['user'=>'asd/{id}']

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
	'custom_admin_item_fields'=>false,
	'custom_admin_item_variables'=>false,
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