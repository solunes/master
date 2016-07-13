<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fields Language Lines
    |--------------------------------------------------------------------------
    |
    */

    // GLOBAL FIELDS
    'address' => 'Address',
    'city' => 'City',
    'code' => 'Code',
    'content' => 'Content',
    'created_at' => 'Creation Date',
    'email' => 'Email',
    'id' => 'ID',
    'image' => 'Image',
    'last_activity' => 'Last Activity',
    'last_login' => 'Last Login',
    'last_session' => 'Last Session',
    'latitude' => 'Latitude',
    'longitude' => 'Longitude',
    'link' => 'Link',
    'message' => 'Message',
    'name' => 'Name',
    'node' => 'Node',
    'order' => 'Order',
    'parent' => 'From',
    'password' => 'Password',
    'phone' => 'Phone',
    'remember_token' => 'Remember Token',
    'role_user' => 'Role User',
    'section' => 'Section',
    'status' => 'Status',
    'updated_at' => 'Last Update',
    'url' => 'URL',
    'user' => 'User',

    // FILTER FIELDS
    'f_date_from' => 'Fecha Desde',
    'f_date_to' => 'Fecha Hasta',
    'f_customer' => 'Cliente',
    'f_point' => 'Punto',

    // CUSTOM FIELDS
    'alternative_email' => 'Correo electrónico (alternativo)',
    'clasification' => 'Clasificación',
    'company_name' => 'Nombre de la empresa',
    'company_type' => 'Tipo de empresa',
    'contact_email' => 'Email de Contacto',
    'contact_name' => 'Nombre de Contacto',
    'contact_phone' => 'Teléfono de Contacto',
    'contact_position' => 'Posición del Contacto',
    'deadline' => 'Fecha Límite',
    'event' => 'Evento',
    'expired_message' => 'Mensaje de Expiración',
    'fax' => 'Fax',
    'guayaquil_belongs' => 'Parroquia a la que pertenece',
    'guayaquil_zone' => 'Parroquia en la que realiza sus operaciones',
    'has_ruc' => '¿La empresa cuenta con RUC?',
    'interest_in_participation' => 'Interés de Participar (máx. 500 palabras)',
    'participant_name' => 'Nombre del participante o institución',
    'proposal_title' => 'Título de la propuesta',
    'proposal_summary' => 'Resumen de la propuesta (máx. 300 palabras)',
    'proposal_objective' => 'Objetivo de la propuesta (máx.100 palabras)',
    'ruc' => 'Número de RUC de la Institución',
    'ruc_optional' => 'RUC de la Institución',
    'send_date' => 'Fecha de Envío',
    'website' => 'Sitio Web',

    // REGISTRY FIELDS
    'pa1_title' => 'Indicador 1: Cumplimiento de la normativa ambiental vigente',
    'ra1_type' => '1.1 Tipo de permiso ambiental vigente',
    'ra1_file' => 'Documento Verificable',
    'ra1_other' => 'En caso de Otro, especificar cual',
    'ra2_type' => '1.2 Especificar cuál es la forma de verificación del cumplimiento de su gestión ambiental',
    'ra2_file' => 'Documento Verificable',

    // POSTULATION A FIELDS
    'registry_a' => 'Registro',
    'total_ponderation' => 'Puntaje Total',
    'pa2_1_bool' => '2.1 Uso eficiente de materias primas, insumos y materiales',
    'pa2_1_file' => 'Adjuntar documento verificable',
    'pa2_1_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_2_bool' => '2.2 Producción limpia',
    'pa2_2_file' => 'Adjuntar documento verificable',
    'pa2_2_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_3_bool' => '2.3 Eficiencia energética',
    'pa2_3_file' => 'Adjuntar documento verificable',
    'pa2_3_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_4_bool' => '2.4 Manejo adecuado de residuos sólidos',
    'pa2_4_file' => 'Adjuntar documento verificable',
    'pa2_4_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_5_bool' => '2.5 Manejo adecuado de los recursos hídricos',
    'pa2_5_file' => 'Adjuntar documento verificable',
    'pa2_5_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_6_bool' => '2.6 Construcciones sostenibles (infraestructura verde)',
    'pa2_6_file' => 'Adjuntar documento verificable',
    'pa2_6_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_7_bool' => '2.7 Transporte sostenible',
    'pa2_7_file' => 'Adjuntar documento verificable',
    'pa2_7_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_8_bool' => '2.8 Buenas prácticas ambientales',
    'pa2_8_file' => 'Adjuntar documento verificable',
    'pa2_8_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_9_bool' => '2.9 Cuidado al patrimonio natural',
    'pa2_9_file' => 'Adjuntar documento verificable',
    'pa2_9_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_10_bool' => '2.10 Innovaciones verdes',
    'pa2_10_file' => 'Adjuntar documento verificable',
    'pa2_10_desc' => 'Breve explicación del caso más sobresaliente',
    'pa2_11_title' => '2.11 Gestión de la Huella de Carbono',
    'pa2_11_1_bool' => '· Medición de la Huella de Carbono',
    'pa2_11_1_file' => 'Adjuntar documento verificable',
    'pa2_11_1_desc' => 'Breve detalle de medidas aplicadas',
    'pa2_11_1_methology' => 'Descripción metodología utilizada, alcance y año base',
    'pa2_11_2_bool' => '· Aplicación de medidas para reducir la Huella de Carbono',
    'pa2_11_2_file' => 'Adjuntar documento verificable',
    'pa2_11_2_desc' => 'Breve detalle de medidas aplicadas',
    'pa2_11_3_bool' => '· Aplicación de medidas para compensar la Huella de Carbono',
    'pa2_11_3_file' => 'Adjuntar documento verificable',
    'pa2_11_3_desc' => 'Breve detalle de medidas aplicadas',
    'pa2_12_title' => '2.12 Gestión de la Huella Hídrica',
    'pa2_12_1_bool' => '· Medición de la Huella Hídrica',
    'pa2_12_1_file' => 'Adjuntar documento verificable',
    'pa2_12_1_desc' => 'Breve detalle de medidas aplicadas',
    'pa2_12_1_methology' => 'Descripción metodología utilizada, alcance y año base',
    'pa2_12_2_bool' => '· Aplicación de medidas para reducir la Huella Hídrica',
    'pa2_12_2_file' => 'Adjuntar documento verificable',
    'pa2_12_2_desc' => 'Breve detalle de medidas aplicadas',
    'pa2_12_3_bool' => '· Aplicación de medidas para compensar la Huella Hídrica',
    'pa2_12_3_file' => 'Adjuntar documento verificable',
    'pa2_12_3_desc' => 'Breve detalle de medidas aplicadas',
    'pa3_title' => 'Indicador 3: Gestión ambiental',
    'pa3_1_bool' => '3.1 ¿Cuenta con programas de formación y capacitación en materia ambiental para sus funcionarios (ejecutados o en ejecución)?',
    'pa3_1_file' => 'Adjuntar documento verificable',
    'pa3_1_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_2_bool' => '3.2 ¿Cuenta con programas de conservación ambiental con la comunidad (ejecutados o en ejecución)?',
    'pa3_2_file' => 'Adjuntar documento verificable',
    'pa3_2_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_3_bool' => '3.3 ¿Cuenta con sistemas de gestión certificados en temas ambientales?',
    'pa3_3_file' => 'Adjuntar documento verificable',
    'pa3_3_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_4_bool' => '3.4 ¿Cuenta con algún seguro relacionado a temas ambientales?',
    'pa3_4_file' => 'Adjuntar documento verificable',
    'pa3_4_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_5_bool' => '3.5 ¿Cuenta con algún estudio de riesgos ambientales?',
    'pa3_5_file' => 'Adjuntar documento verificable',
    'pa3_5_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_6_bool' => '3.6 ¿Cuenta con algún programa de prevención de accidentes vigente?',
    'pa3_6_file' => 'Adjuntar documento verificable',
    'pa3_6_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_7_bool' => '3.7 ¿Cuenta con algún programa de contingencias vigente?',
    'pa3_7_file' => 'Adjuntar documento verificable',
    'pa3_7_desc' => 'Breve explicación (máx. 300 palabras)',
    'pa3_8_bool' => '3.8 ¿Desarrolla otras iniciativas ambientales más allá de lo que exige la ley? ',
    'pa3_8_file' => 'Adjuntar documento verificable',
    'pa3_8_desc' => 'Breve descripción de la iniciativa',
    
    // POSTULATION B FIELDS
    'registry_b' => 'Registro',
    'pb1' => '1. Sector',
    'pb2' => '2. Título de la propuesta',
    'pb3' => '3. Antecedentes (máx 400 palabras)',
    'pb4' => '4. Objetivo (máx 300 palabras)',
    'pb5' => '5. Justificación (máx. 400 palabras)',
    'pb6' => '6. Metodología propuesta (máx. 500 palabras)',
    'pb7' => '7. Forma de implementación (máx. 500 palabras)',
    'pb8_title' => '8. Beneficios esperados',
    'pb8_1' => '8.1 Sociales (si aplica)',
    'pb8_2' => '8.2 Económicos (si aplica)',
    'pb8_3' => '8.3 Ambientales',
    'pb9' => '9. Sostenibilidad del proyecto (máx. 500 palabras)',
    'pb10' => '10. Tiempo de implementación del proyecto en meses',
    'pb11_title' => '11. Costos asociados (USD)',
    'pb11_total' => 'Costo total',
    'pb11_investment' => 'Costo de Inversión',
    'pb11_operation' => 'Costo de Operación',
    'pb11_other' => 'Otros costos',
    'pb11_counterpart' => 'Contraparte (si aplica)',
    'pb12' => '12. Resumen de la experiencia del proponente (máx. 300 palabras)',
    'pb_cv_file' => 'Adjuntar Documento Verificable (CV)',

];
