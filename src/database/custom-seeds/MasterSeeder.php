<?php

use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // General
        \App\Language::create([
            'code' => 'es',
            'name' => 'Español',
            'image' => 'es.png'
        ]);
        \App\Site::create([
            'name' => 'Plataforma ',
            'domain' => 'http://mimg.dev/',
            'root' => '/',
            'es' => [
                'title' => 'PROGRAMA MUNICIPAL DE RECONOCIMIENTO Y FOMENTO A INICIATIVAS SOSTENIBLES', 
                'description' => 'Nuestro objetivo es programar la plataforma del Programa Municipal de Reconocimiento y Fomento a Iniciativas Sostenibles en formato es php y sql para entorno web, en base a un contenido y diseño previamente definido.',
                'keywords' => 'programa, municipal, iniciativas sostenibles, medio ambiente, guayaquil, ecuador',
            ],        
            'google_verification' => '',
            'analytics' => ''
        ]);
        
        // Nodos
        $node_registry_a = \App\Node::create(['name'=>'registry-a', 'table_name'=>'registry_a', 'type'=>'form']);
        \App\NodeExtra::create(['parent_id'=>$node_registry_a->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'field', 'value_array'=>json_encode(['status','company_type','guayaquil_zone'])]);
        \App\NodeExtra::create(['parent_id'=>$node_registry_a->id, 'display'=>'admin', 'type'=>'graph', 'parameter'=>'pie', 'value_array'=>json_encode(['status','company_type','guayaquil_zone'])]);
        $node_registry_b = \App\Node::create(['name'=>'registry-b', 'table_name'=>'registry_b', 'type'=>'form']);
        \App\NodeExtra::create(['parent_id'=>$node_registry_b->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'field', 'value_array'=>json_encode(['status','clasification','guayaquil_belongs'])]);
        \App\NodeExtra::create(['parent_id'=>$node_registry_b->id, 'display'=>'admin', 'type'=>'graph', 'parameter'=>'pie', 'value_array'=>json_encode(['status','clasification','guayaquil_belongs'])]);
        $node_postulation_a = \App\Node::create(['name'=>'postulation-a', 'table_name'=>'postulation_a', 'type'=>'form']);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_a->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'field', 'value_array'=>json_encode(['status'])]);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_a->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'parent_field', 'value_array'=>json_encode([['name'=>'guayaquil_zone', 'parent'=>'registry-a', 'data'=>'registry_a_id'], ['name'=>'company_type', 'parent'=>'registry-a', 'data'=>'registry_a_id']])]);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_a->id, 'display'=>'admin', 'type'=>'graph', 'parameter'=>'pie', 'value_array'=>json_encode(['status'])]);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_a->id, 'display'=>'admin', 'type'=>'parent_graph', 'parameter'=>'pie', 'value_array'=>json_encode([['name'=>'guayaquil_zone', 'parent'=>'registry_a', 'data'=>'registry_a_id'], ['name'=>'company_type', 'parent'=>'registry_a', 'data'=>'registry_a_id']])]);
        $node_postulation_b = \App\Node::create(['name'=>'postulation-b', 'table_name'=>'postulation_b', 'type'=>'form']);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_b->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'field', 'value_array'=>json_encode(['status'])]);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_b->id, 'display'=>'admin', 'type'=>'filter', 'parameter'=>'parent_field', 'value_array'=>json_encode([['name'=>'guayaquil_belongs', 'parent'=>'registry-b', 'data'=>'registry_b_id'], ['name'=>'clasification', 'parent'=>'registry-b', 'data'=>'registry_b_id']])]);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_b->id, 'display'=>'admin', 'type'=>'graph', 'parameter'=>'pie', 'value_array'=>json_encode(['status'])]);
        \App\NodeExtra::create(['parent_id'=>$node_postulation_b->id, 'display'=>'admin', 'type'=>'parent_graph', 'parameter'=>'pie', 'value_array'=>json_encode([['name'=>'guayaquil_belongs', 'parent'=>'registry_b', 'data'=>'registry_b_id'], ['name'=>'clasification', 'parent'=>'registry_b', 'data'=>'registry_b_id']])]);
        $node_deadlines = \App\Node::create(['name'=>'deadline']);
        $node_social_network = \App\Node::create(['name'=>'social-network', 'table_name'=>'social_networks', 'location'=>'app', 'type'=>'global', 'model'=>'\App\SocialNetwork']);
        $node_title = \App\Node::create(['name'=>'title']);
        $node_content = \App\Node::create(['name'=>'content']);
        $node_banner = \App\Node::create(['name'=>'banner']);
        $node_agenda = \App\Node::create(['name'=>'agenda']);
        $node_sponsor = \App\Node::create(['name'=>'sponsor']);
        $node_contact = \App\Node::create(['name'=>'contact', 'table_name'=>'contact']);
        $node_form_contact = \App\Node::create(['name'=>'form-contact', 'type'=>'form', 'table_name'=>'form_contact']);
        
        // Menu: Home
        $page_home = \App\Page::create(['type'=>'customized', 'customized_name'=>'home', 'es'=>['name'=>'Inicio']]);
        \App\Menu::create(['page_id'=>$page_home->id]);
        \App\Section::create(['id'=>1, 'page_id'=>$page_home->id, 'node_id'=>$node_title->id]);
        \App\Section::create(['id'=>2, 'page_id'=>$page_home->id, 'node_id'=>$node_content->id]);
        \App\Section::create(['id'=>3, 'page_id'=>$page_home->id, 'node_id'=>$node_title->id]);
        \App\Section::create(['id'=>4, 'page_id'=>$page_home->id, 'node_id'=>$node_agenda->id]);
        \App\Section::create(['id'=>5, 'page_id'=>$page_home->id, 'node_id'=>$node_title->id]);
        \App\Section::create(['id'=>6, 'page_id'=>$page_home->id, 'node_id'=>$node_sponsor->id]);

        // Menu: Sobre el Programa
        $page_programa = \App\Page::create(['es'=>['name'=>'Sobre el Programa']]);
        \App\Menu::create(['page_id'=>$page_programa->id]);
        \App\Section::create(['id'=>7, 'page_id'=>$page_programa->id, 'node_id'=>$node_content->id]);

        // Menu: Categorias
        $page_categorias = \App\Page::create(['es'=>['name'=>'Categorias']]);
        \App\Menu::create(['page_id'=>$page_categorias->id]);
        \App\Section::create(['id'=>8, 'page_id'=>$page_categorias->id, 'node_id'=>$node_content->id]);

        // Menu: Premios
        $page_premios = \App\Page::create(['es'=>['name'=>'Premios']]);
        \App\Menu::create(['page_id'=>$page_premios->id]);
        \App\Section::create(['id'=>9, 'page_id'=>$page_premios->id, 'node_id'=>$node_content->id]);

        // Menu: Cronograma
        $page_cronograma = \App\Page::create(['es'=>['name'=>'Cronograma']]);
        \App\Menu::create(['page_id'=>$page_cronograma->id]);
        \App\Section::create(['id'=>10, 'page_id'=>$page_cronograma->id, 'node_id'=>$node_content->id]);
        
        // Menu: Registro
        $menu_registro = \App\Menu::create(['type'=>'blank', 'es'=>['name'=>'Registro']]);
        $page_registro_a = \App\Page::create(['es'=>['name'=>'Registro A']]);
        \App\Menu::create(['level'=>2, 'parent_id'=>$menu_registro->id, 'es'=>['name'=>'A. Distinción a empresas ambientalmente sostenibles', 'link'=>'registro-a']]);
        \App\Section::create(['id'=>11, 'page_id'=>$page_registro_a->id, 'node_id'=>$node_content->id]);
        \App\Section::create(['id'=>12, 'page_id'=>$page_registro_a->id, 'node_id'=>$node_registry_a->id]);
        $page_registro_b = \App\Page::create(['es'=>['name'=>'Registro B']]);
        \App\Menu::create(['level'=>2, 'parent_id'=>$menu_registro->id, 'es'=>['name'=>'B. Capital semilla a iniciativas sostenibles', 'link'=>'registro-b']]);
        \App\Section::create(['id'=>13, 'page_id'=>$page_registro_b->id, 'node_id'=>$node_content->id]);
        \App\Section::create(['id'=>14, 'page_id'=>$page_registro_b->id, 'node_id'=>$node_registry_b->id]);

        // Page: Postulación A
        $page_postulacion_a = \App\Page::create(['es'=>['name'=>'Postulacion A']]);
        \App\Section::create(['id'=>15, 'page_id'=>$page_postulacion_a->id, 'node_id'=>$node_content->id]);
        \App\Section::create(['id'=>16, 'page_id'=>$page_postulacion_a->id, 'node_id'=>$node_postulation_a->id]);

        // Page: Postulación B
        $page_postulacion_b = \App\Page::create(['es'=>['name'=>'Postulacion B']]);
        \App\Section::create(['id'=>17, 'page_id'=>$page_postulacion_b->id, 'node_id'=>$node_content->id]);
        \App\Section::create(['id'=>18, 'page_id'=>$page_postulacion_b->id, 'node_id'=>$node_postulation_b->id]);

        // Menu: Contacto
        $page_contacto = \App\Page::create(['es'=>['name'=>'Contacto']]);
        \App\Menu::create(['page_id'=>$page_contacto->id]);
        \App\Section::create(['id'=>19, 'page_id'=>$page_contacto->id, 'node_id'=>$node_contact->id]);
        \App\Section::create(['id'=>20, 'page_id'=>$page_contacto->id, 'node_id'=>$node_form_contact->id]);
        
        // Panel: Postulaciones
        $page_postulaciones = \App\Page::create(['type'=>'customized', 'customized_name'=>'postulaciones', 'es'=>['name'=>'Postulaciones']]);
        \App\Section::create(['id'=>21, 'page_id'=>$page_postulaciones->id, 'node_id'=>$node_content->id]);

        // Home Segunda Parte
        \App\Section::create(['id'=>22, 'page_id'=>$page_home->id, 'node_id'=>$node_banner->id]);

        // Crear menu en Admin
        /*$m_list = \App\Menu::create(['menu_type'=>'admin', 'icon'=>'th-list', 'es'=>['name'=>'Listas de Correos', 'link'=>'admin/model-list/target-list']]);
        $m_email = \App\Menu::create(['menu_type'=>'admin', 'icon'=>'th-list', 'es'=>['name'=>'Enviar Emails', 'link'=>'admin/model-list/email']]);
        $m_history = \App\Menu::create(['menu_type'=>'admin', 'icon'=>'th-list', 'es'=>['name'=>'Emails Enviados', 'link'=>'admin/model-list/sent-email']]);*/
        
        // Variables
        \App\Variable::create([
            'name' => 'admin_email',
            'type' => 'string',
            'es' => ['value'=>'edumejia30@gmail.com'],
        ]);
        \Solunes\Master\App\Variable::create([
            'name' => 'footer_name',
            'type' => 'string',
            'es' => ['value'=>'GAD MUNICIPAL DE GUAYAQUIL - GUAYAQUIL, ECUADOR'],
        ]);
        \Solunes\Master\App\Variable::create([
            'name' => 'footer_rights',
            'type' => 'string',
            'es' => ['value'=>'TODOS LOS DERECHOS RESERVADOS'],
        ]);
        
        // Social Networks
        \App\SocialNetwork::create([
            'code' => 'facebook',
            'url' => 'https://www.facebook.com/alcaldiaguayaquil/',
        ]);
        \App\SocialNetwork::create([
            'code' => 'twitter',
            'url' => 'https://twitter.com/alcaldiagye/',
        ]);
        \App\SocialNetwork::create([
            'code' => 'youtube',
            'url' => 'https://www.youtube.com/user/municipioguayaquil/',
        ]);
        \App\SocialNetwork::create([
            'code' => 'instagram',
            'url' => 'https://www.instagram.com/municipiogye/',
        ]);
        
        /*factory(App\Customer::class, 30)->create();
        factory(App\CustomerPoint::class, 150)->create();
        factory(App\Operator::class, 100)->create(['city_id'=>$lpz->id]);
        factory(App\Operator::class, 100)->create(['city_id'=>$scz->id]);
        factory(App\OperatorAttendance::class, 100)->create(['operator_id'=>1, 'status'=>'1/2']);
        factory(App\OperatorAttendance::class, 100)->create(['operator_id'=>2, 'status'=>'O']);
        factory(App\Product::class, 20)->create(['type'=>'product']);
        factory(App\Product::class, 30)->create(['type'=>'implement']);*/
        /*factory(App\FilledForm::class, 50)->create(['form_id'=>1]);
        factory(App\FilledForm::class, 50)->create(['form_id'=>2]);
        factory(App\FilledForm::class, 50)->create(['form_id'=>3]);
        factory(App\FilledForm::class, 50)->create(['form_id'=>4]);
        factory(App\FilledForm::class, 50)->create(['form_id'=>5]);
        factory(App\FilledField::class, 50)->create(['filled_form_id'=>rand(1,50), 'field_id'=>rand(1,9)]);
        factory(App\FilledField::class, 50)->create(['filled_form_id'=>rand(51,100), 'field_id'=>rand(10,15)]);
        factory(App\FilledField::class, 50)->create(['filled_form_id'=>rand(101,150), 'field_id'=>rand(16,26)]);
        factory(App\FilledField::class, 50)->create(['filled_form_id'=>rand(151,200), 'field_id'=>rand(27,65)]);
        factory(App\FilledField::class, 50)->create(['filled_form_id'=>rand(201,250), 'field_id'=>rand(66,77)]);*/
        //factory(App\Questionnaire::class, 100)->create(['user_id'=>1]);
        
    }
}