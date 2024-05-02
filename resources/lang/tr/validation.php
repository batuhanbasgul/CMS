<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute kabul edilmeli.',
    'active_url' => ':attribute geçerli bir URL değil.',
    'after' => ':attribute, :date tarihinden sonra olmalı.',
    'after_or_equal' => ':attribute, :date tarihinden sonra veya eşit olmalı.',
    'alpha' => ':attribute sadece harf içerebilir.',
    'alpha_dash' => ':attribute sadece harf, rakam, çizgi ve alt çizgi içerebilir.',
    'alpha_num' => ':attribute sadece harf ve rakam içerebilir.',
    'array' => ':attribute dizi olmalı.',
    'before' => ':attribute, :date tarihinden önce olmalı.',
    'before_or_equal' => ':attribute, :date tarihinden önce veya eşit olmalı.',
    'between' => [
        'numeric' => ':attribute, :min ve :max arasında olmalı.',
        'file' => ':attribute, :min ve :max kb arasında olmalı.',
        'string' => ':attribute, :min ve :max karakter arasında olmalı.',
        'array' => ':attribute, :min ve :max nesne sayısı arasında olmalı.',
    ],
    'boolean' => ':attribute doğru, yanlış, 1 veya 0 olmalı.',
    'confirmed' => ':attribute doğrulaması eşleşmedi.',
    'date' => ':attribute geçerli bir tarih değil.',
    'date_equals' => ':attribute, :date tarihine eşit olmalı.',
    'date_format' => ':attribute formatı, :format ile eşleşmeli.',
    'different' => ':attribute ve :other farklı olmalı.',
    'digits' => ':attribute, :digits rakamlarından oluşmalı.',
    'digits_between' => ':attribute, :min ve :max rakamları arasında olmalı.',
    'dimensions' => ':attribute, geçersiz boyutlardan oluşuyor.',
    'distinct' => ':attribute tekrar içeriyor.',
    'email' => ':attribute geçerli E-Posta adresi olmalı.',
    'ends_with' => ':attribute, :values değerlerinden biri ile bitmeli.',
    'exists' => ':attribute geçersiz.',
    'file' => ':attribute dosya olmalı.',
    'filled' => ':attribute boş bırakılamaz.',
    'gt' => [
        'numeric' => ':attribute, :value değerinden büyük olmalı.',
        'file' => ':attribute, :value kb değerinden büyük olmalı.',
        'string' => ':attribute, :value karakter değerinden büyük olmalı.',
        'array' => ':attribute, :value nesne sayısı değerinden büyük olmalı.',
    ],
    'gte' => [
        'numeric' => ':attribute, :value değerinden büyük veya eşit olmalı.',
        'file' => ':attribute, :value kb değerinden büyük veya eşit olmalı.',
        'string' => ':attribute, :value karakter değerinden büyük veya eşit olmalı.',
        'array' => ':attribute, :value nesne sayısı değerinden büyük veya eşit olmalı.',
    ],
    'image' => ':attribute resim olmalı.',
    'in' => ':attribute geçersiz.',
    'in_array' => ':attribute, :other içerisinde bulunmuor.',
    'integer' => ':attribute tamsayı olmalı.',
    'ip' => ':attribute geçerli bir IP adres olmalı.',
    'ipv4' => ':attribute geçerli bir IPv4 adres olmalı.',
    'ipv6' => ':attribute geçerli bir IPv6 adres olmalı.',
    'json' => ':attribute geçerli bir JSON veri olmalı.',
    'lt' => [
        'numeric' => ':attribute, :value değerinden küçük olmalı.',
        'file' => ':attribute, :value kb değerinden küçük olmalı.',
        'string' => ':attribute, :value karakter değerinden küçük olmalı.',
        'array' => ':attribute, :value nesne sayısı değerinden küçük olmalı.',
    ],
    'lte' => [
        'numeric' => ':attribute, :value değerinden küçük veya eşit olmalı.',
        'file' => ':attribute, :value kb değerinden küçük veya eşit olmalı.',
        'string' => ':attribute, :value karakter değerinden küçük veya eşit olmalı.',
        'array' => ':attribute, :value nesne sayısı değerinden küçük veya eşit olmalı.',
    ],
    'max' => [
        'numeric' => ':attribute, :max değerinden büyük olamaz.',
        'file' => ':attribute, :max kb değerinden büyük olamaz.',
        'string' => ':attribute, :max karakter değerinden büyük olamaz.',
        'array' => ':attribute, :max nesne sayısı değerinden büyük olamaz.',
    ],
    'mimes' => ':attribute, :values dosya tiplerinden birisi olmalı.',
    'mimetypes' => ':attribute, :values dosya tiplerinden birisi olmalı.',
    'min' => [
        'numeric' => ':attribute, :max değerinden küçük olamaz.',
        'file' => ':attribute, :max kb değerinden küçük olamaz.',
        'string' => ':attribute, :max karakter değerinden küçük olamaz.',
        'array' => ':attribute, :max nesne sayısı değerinden küçük olamaz.',
    ],
    'multiple_of' => ':attribute, :value değerinin katları olmalı.',
    'not_in' => 'Seçili :attribute geçersiz.',
    'not_regex' => ':attribute geçersiz formata sahip.',
    'numeric' => ':attribute sayı olmalı.',
    'password' => 'Şifre geçersiz.',
    'present' => ':attribute boş bırakılamaz.',
    'regex' => ':attribute formatı geçersiz.',
    'required' => 'Lütfen bu alanı boş bırakmayınız.',
    'required_if' => ':attribute alanı :other :value olduğunda gerekli.',
    'required_unless' => ':attribute alanı :other :values içerisinde olduğunda gerekli.',
    'required_with' => ':attribute alanı :values girildiğinde gerekli.',
    'required_with_all' => ':attribute alanı :values girildiğinde gerekli.',
    'required_without' => ':attribute alanı :values girilmediğinde gerekli.',
    'required_without_all' => ':attribute alanı hiç bir :values girilmediğinde gerekli.',
    'same' => ':attribute ve :other eşleşmeli.',
    'size' => [
        'numeric' => ':attribute, :value değerine eşit olmalı.',
        'file' => ':attribute, :value kb değerine eşit olmalı.',
        'string' => ':attribute, :value karakter değerine eşit olmalı.',
        'array' => ':attribute, :value nesne sayısına eşit olmalı.',
    ],
    'starts_with' => ':attribute, :values değerlerinden birisi ile başlamalı.',
    'string' => ':attribute string olmalı.',
    'timezone' => ':attribute geçerli bir zaman dilimi olmalı.',
    'unique' => ':attribute zaten kullanılıyor.',
    'uploaded' => ':attribute yüklemesi başarısız.',
    'url' => ':attribute formatı geçersiz.',
    'uuid' => ':attribute geçerli bir UUID olmalı.',

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

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
