<?php

return [
    'email_exists' => 'Пользователь с таким email не найден.',
    'email_unique' => 'Пользователь с таким email уже существует.',
    'is_email' => 'Поле email должно быть валидным email адресом.',
    'username_unique' => 'Пользователь с таким username уже существует.',
    'password_min' => 'Пароль должен быть не менее :min символов.',
    'password_confirmed' => 'Пароли не совпадают.',
    'password_incorrect' => 'Неверный пароль.',
    'old_password_incorrect' => 'Старый пароль неверный',

    'attribute' => [
        'exists' => 'Поле :attribute не существует.',
        'unique' => 'Поле :attribute должно быть уникальным.',
        'required' => 'Поле :attribute обязательно для заполнения.',
        'date' => 'Поле :attribute должно быть датой.',
        'max' => 'Поле :attribute должно быть не более :max символов.',
        'in' => 'Поле :attribute должно быть одним из следующих типов: :values.',
        'image' => 'Поле :attribute должно быть изображением.',
        'size' => 'Поле :attribute должно быть не более :size байт.',
        'integer' => 'Поле :attribute должно быть целым числом.',
        'boolean' => 'Поле :attribute должно быть булевым значением.',
        'regex' => 'Поле :attribute должно быть валидным.',
    ],
];
