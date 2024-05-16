<?php

return [
    'required' => 'El campo :attribute es obligatorio',
    'email' => 'El campo :attribute debe ser un email válido',
    'unique' => 'El registo insertado ya existe en la base de datos',
    'regex' => 'El campo :attribute contiene caracteres no permitidos',
    'max' => [
        'string' => 'El campo :attribute no debe tener mas de :max caracteres',
        'numeric' => 'El campo :attribute no debe ser mayo que :max caracteres'
    ],
    'min' => [
        'string' => 'El campo :attribute debe tener al menos :min caracteres',
        'numeric' => 'El campo :attribute debe ser de al menos :max caracteres'
    ],
];
