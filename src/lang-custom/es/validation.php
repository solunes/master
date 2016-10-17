<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"         => "Debe aceptar este campo para continuar.",
    "active_url"       => "El campo no es una URL válida.",
    "after"            => ":attribute debe ser una fecha posterior a :date.",
    "alpha"            => ":attribute solo debe contener letras.",
    "alpha_dash"       => ":attribute solo debe contener letras, números y guiones.",
    "alpha_num"        => ":attribute solo debe contener letras y números.",
    "array"            => ":attribute debe ser un conjunto.",
    "before"           => ":attribute debe ser una fecha anterior a :date.",
    "between"          => array(
        "numeric" => ":attribute tiene que estar entre :min - :max.",
        "file"    => ":attribute debe pesar entre :min - :max kilobytes.",
        "string"  => ":attribute tiene que tener entre :min - :max caracteres.",
        "array"   => ":attribute tiene que tener entre :min - :max ítems.",
    ),
    "captcha"          => "Debe introducir el mismo código captcha que ve en la imagen de arriba",
    "confirmed"        => "La confirmación de :attribute no coincide.",
    "date"             => "El campo no tiene una fecha válida.",
    "date_format"      => "El campo no corresponde al formato :format.",
    "different"        => ":attribute y :other deben ser diferentes.",
    "digits"           => ":attribute debe tener :digits dígitos.",
    "digits_between"   => ":attribute debe tener entre :min y :max dígitos.",
    "email"            => "Debe introducir un correo electrónico válido",
    "exists"           => "El campo es inválido.",
    "image"            => "El archivo debe ser una imagen válida.",
    "in"               => "El campo es inválido.",
    "integer"          => "El campo debe contener un número entero.",
    "ip"               => "El campo debe contener una dirección IP válida.",
    "max"              => array(
        "numeric" => ":attribute no debe ser mayor a :max.",
        "file"    => ":attribute no debe ser mayor que :max kilobytes.",
        "string"  => ":attribute no debe ser mayor que :max caracteres.",
        "array"   => ":attribute no debe tener más de :max elementos.",
    ),
    "mimes"            => "El archivo debe tener un formato: :values.",
    "min"              => array(
        "numeric" => "El tamaño de :attribute debe ser de al menos :min.",
        "file"    => "El tamaño de :attribute debe ser de al menos :min kilobytes.",
        "string"  => "Debe introducir al menos :min caracteres.",
        "array"   => ":attribute debe tener al menos :min elementos.",
    ),
    "not_in"           => "El campo es inválido.",
    "numeric"          => "El campo debe ser un número o decimal válido.",
    "regex"            => "El formato de :attribute es inválido.",
    "required"         => "Debe llenar este campo.",
    "required_if"      => "El campo es obligatorio.",
    "required_with"    => "El campo es obligatorio.",
    "required_with_all" => "El campo es obligatorio.",
    "required_without" => "El campo es obligatorio.",
    "required_without_all" => "Debe llenar al menos uno de estos campos.",
    "same"             => "Este campo debe coincidir con :other.",
    "size"             => array(
        "numeric" => "El tamaño de :attribute debe ser :size.",
        "file"    => "El tamaño de :attribute debe ser :size kilobytes.",
        "string"  => ":attribute debe contener :size caracteres.",
        "array"   => ":attribute debe contener :size elementos.",
    ),
    "unique"           => ":attribute ya ha sido registrado.",
    "url"              => "El formato :attribute es inválido.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Rules
    |--------------------------------------------------------------------------
    */

    "maxwords" => "Este campo debe tener menos de :maxwords palabras.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => array(
        'attribute-name' => array(
            'rule-name' => 'custom-message',
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => array(),

);