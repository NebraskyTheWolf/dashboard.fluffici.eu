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

    'accepted' => 'Pole :attribute musí byť akceptované.',
    'accepted_if' => 'Pole :attribute musí byť akceptované, ak je :other :value.',
    'active_url' => 'Pole :attribute musí byť platná adresa URL.',
    'after' => 'Pole :atribút musí byť dátum po :date.',
    'after_or_equal' => 'Pole :atribút musí byť dátum po alebo rovný :date.',
    'alpha' => 'Pole :attribute musí obsahovať len písmená.',
    'alpha_dash' => 'Pole :atribút musí obsahovať len písmená, číslice, pomlčky a podčiarkovníky.',
    'alpha_num' => 'Pole :attribute musí obsahovať len písmená a číslice.',
    'array' => 'Pole :attribute musí byť pole.',
    'ascii' => 'Pole :attribute musí obsahovať len jednobajtové alfanumerické znaky a symboly.',
    'before' => 'Pole :attribute musí byť dátumom pred :date.',
    'before_or_equal' => 'Pole :atribút musí byť dátum pred alebo rovný :date.',
    'between' => [
        'array' => 'Pole :atribút musí mať položky :min až :max.',
        'file' => 'Pole :atribút musí byť medzi :min a :max kilobajtov.',
        'numeric' => 'Pole :atribút musí byť medzi :min a :max.',
        'string' => 'Pole :atribút musí obsahovať znaky medzi :min a :max.',
    ],
    'boolean' => 'Pole :attribute musí byť true alebo false.',
    'can' => 'Pole :attribute obsahuje neoprávnenú hodnotu.',
    'confirmed' => 'Potvrdenie poľa :atribút sa nezhoduje.',
    'current_password' => 'Heslo je nesprávne.',
    'date' => 'Pole :atribút musí byť platný dátum.',
    'date_equals' => 'V poli :attribute musí byť dátum rovný :date.',
    'date_format' => 'Pole :attribute musí zodpovedať formátu :format.',
    'decimal' => 'Pole :attribute musí mať :decimal desatinné miesta.',
    'declined' => 'Pole :attribute musí byť odmietnuté.',
    'declined_if' => 'Pole :attribute sa musí odmietnuť, ak je :other :value.',
    'different' => 'Polia :attribute a :other sa musia líšiť.',
    'digits' => 'Pole :atribút musí byť :číslice číslice.',
    'digits_between' => 'Pole :atribút musí byť medzi číslicami :min a :max.',
    'dimensions' => 'Pole :atribút má neplatné rozmery obrázka.',
    'distinct' => 'Pole :attribute má duplicitnú hodnotu.',
    'doesnt_end_with' => 'Pole :atribút nesmie končiť jedným z nasledujúcich znakov: :values.',
    'doesnt_start_with' => 'Pole :atribút nesmie začínať jedným z nasledujúcich znakov: :values.',
    'email' => 'Pole :atribút musí byť platná e-mailová adresa.',
    'ends_with' => 'Pole :atribút musí končiť jedným z nasledujúcich znakov: :values.',
    'enum' => 'Vybraný atribút :je neplatný.',
    'exists' => 'Vybraný atribút :je neplatný.',
    'extensions' => 'Pole :attribute musí mať jedno z nasledujúcich rozšírení: :values.',
    'file' => 'Pole :attribute musí byť súbor.',
    'filled' => 'Pole :attribute musí mať hodnotu.',
    'gt' => [
        'array' => 'Pole :attribute musí mať viac položiek ako :value.',
        'file' => 'Pole :atribút musí byť väčšie ako :hodnota kilobajtov.',
        'numeric' => 'Pole :attribute musí byť väčšie ako :value.',
        'string' => 'Pole :atribút musí byť väčšie ako :hodnota znakov.',
    ],
    'gte' => [
        'array' => 'Pole :attribute musí mať položky :value alebo viac.',
        'file' => 'Pole :attribute musí byť väčšie alebo rovné :value kilobajtov.',
        'numeric' => 'Pole :attribute musí byť väčšie alebo rovné :value.',
        'string' => 'Pole :atribút musí byť väčšie alebo rovné :hodnota znakov.',
    ],
    'hex_color' => 'Pole :atribút musí byť platná hexadecimálna farba.',
    'image' => 'Pole :attribute musí byť obrázok.',
    'in' => 'Vybraný atribút :je neplatný.',
    'in_array' => 'Pole :attribute musí existovať v položke :other.',
    'integer' => 'Pole :attribute musí byť celé číslo.',
    'ip' => 'Pole :atribút musí byť platná IP adresa.',
    'ipv4' => 'Pole :attribute musí byť platná adresa IPv4.',
    'ipv6' => 'Pole :atribút musí byť platná adresa IPv6.',
    'json' => 'Pole :attribute musí byť platný reťazec JSON.',
    'lowercase' => 'Pole :attribute musí byť písané malými písmenami.',
    'lt' => [
        'array' => 'Pole :attribute musí mať menej položiek ako :value.',
        'file' => 'Pole :attribute musí byť menšie ako :value kilobajtov.',
        'numeric' => 'Pole :attribute musí byť menšie ako :value.',
        'string' => 'Pole :attribute musí mať menej znakov ako :value.',
    ],
    'lte' => [
        'array' => 'Pole :attribute nesmie mať viac položiek ako :value.',
        'file' => 'Pole :attribute musí byť menšie alebo rovné :value kilobajtov.',
        'numeric' => 'Pole :attribute musí byť menšie alebo rovné :value.',
        'string' => 'Pole :atribút musí byť menšie alebo rovné ako :value znaky.',
    ],
    'mac_address' => 'Pole :atribút musí byť platná adresa MAC.',
    'max' => [
        'array' => 'Pole :atribút nesmie mať viac ako :max položiek.',
        'file' => 'Pole :attribute nesmie byť väčšie ako :max kilobajtov.',
        'numeric' => 'Pole :atribút nesmie byť väčšie ako :max.',
        'string' => 'Pole :atribút nesmie byť väčšie ako :max znakov.',
    ],
    'max_digits' => 'Pole :atribút nesmie mať viac ako :max číslic.',
    'mimes' => 'Pole :attribute musí byť súbor typu: :values.',
    'mimetypes' => 'Pole :attribute musí byť súbor typu: :values.',
    'min' => [
        'array' => 'Pole :atribút musí mať aspoň položky :min.',
        'file' => 'Pole :attribute musí mať veľkosť aspoň :min kilobajtov.',
        'numeric' => 'Pole :atribút musí mať hodnotu aspoň :min.',
        'string' => 'Pole :atribút musí mať aspoň :min znakov.',
    ],
    'min_digits' => 'Pole :atribút musí mať aspoň :min číslic.',
    'missing' => 'Pole :attribute musí chýbať.',
    'missing_if' => 'Pole :attribute musí chýbať, ak je :other :value.',
    'missing_unless' => 'Pole :attribute musí chýbať, pokiaľ :other nie je :value.',
    'missing_with' => 'Pole :attribute musí chýbať, ak je prítomné pole :values.',
    'missing_with_all' => 'Pole :atribút musí chýbať, ak sú prítomné :hodnoty.',
    'multiple_of' => 'Pole :attribute musí byť násobkom :value.',
    'not_in' => 'Vybraný atribút :je neplatný.',
    'not_regex' => 'Formát poľa :atribút je neplatný.',
    'numeric' => 'Pole :atribút musí byť číslo.',
    'password' => [
        'letters' => 'Pole :attribute musí obsahovať aspoň jedno písmeno.',
        'mixed' => 'Pole :attribute musí obsahovať aspoň jedno veľké a jedno malé písmeno.',
        'numbers' => 'Pole :atribút musí obsahovať aspoň jedno číslo.',
        'symbols' => 'Pole :attribute musí obsahovať aspoň jeden symbol.',
        'uncompromised' => 'Daný atribút :sa objavil v úniku údajov. Vyberte si iný :atribút.',
    ],
    'present' => 'Pole :attribute musí byť prítomné.',
    'present_if' => 'Pole :attribute musí byť prítomné, ak je :other :value.',
    'present_unless' => 'Pole :attribute musí byť prítomné, pokiaľ :other nie je :value.',
    'present_with' => 'Pole :attribute musí byť prítomné, ak je prítomné pole :values.',
    'present_with_all' => 'Pole :atribút musí byť prítomné, ak sú prítomné :hodnoty.',
    'prohibited' => 'Pole :attribute je zakázané.',
    'prohibited_if' => 'Pole :attribute je zakázané, ak je :other :value.',
    'prohibited_unless' => 'Pole :atribút je zakázané, pokiaľ nie je v položke :values uvedené :other.',
    'prohibits' => 'Pole :attribute zakazuje prítomnosť :other.',
    'regex' => 'Formát poľa :atribút je neplatný.',
    'required' => 'Pole :attribute je povinné.',
    'required_array_keys' => 'Pole :atribút musí obsahovať položky pre: :values.',
    'required_if' => 'Pole :attribute je povinné, ak je :other :value.',
    'required_if_accepted' => 'Pole :attribute je povinné, ak je akceptované :other.',
    'required_unless' => 'Pole :atribút je povinné, pokiaľ v položke :values nie je uvedené :other.',
    'required_with' => 'Pole :attribute je povinné, ak je prítomné pole :values.',
    'required_with_all' => 'Pole :atribút je povinné, ak sú prítomné :hodnoty.',
    'required_without' => 'Pole :attribute je povinné, ak nie je prítomné pole :values.',
    'required_without_all' => 'Pole :atribút je povinné, ak nie je prítomná žiadna z :hodnôt.',
    'same' => 'Pole :attribute sa musí zhodovať s :other.',
    'size' => [
        'array' => 'Pole :attribute musí obsahovať položky :size.',
        'file' => 'Pole :attribute musí byť :size kilobajty.',
        'numeric' => 'Pole :attribute musí byť :size.',
        'string' => 'Pole :atribút musí byť :size znaky.',
    ],
    'starts_with' => 'Pole :atribút musí začínať jedným z nasledujúcich znakov: :values.',
    'string' => 'Pole :attribute musí byť reťazec.',
    'timezone' => 'Pole :atribút musí byť platná časová zóna.',
    'unique' => 'Atribút :už bol použitý.',
    'uploaded' => 'Atribút :sa nepodarilo nahrať.',
    'uppercase' => 'Pole :attribute musí byť písané veľkými písmenami.',
    'url' => 'Pole :attribute musí byť platná adresa URL.',
    'ulid' => 'Pole :attribute musí byť platný ULID.',
    'uuid' => 'Pole :attribute musí byť platný UUID.',

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
            'rule-name' => 'vlastné správy',
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
