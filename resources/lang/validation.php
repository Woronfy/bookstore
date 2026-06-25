<?php

return [
    'required' => 'Поле :attribute обязательно.',
    'email'    => 'Поле :attribute должно быть корректным email-адресом.',
    'unique'   => 'Такое значение поля :attribute уже существует.',
    'min'      => [
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'confirmed' => 'Подтверждение :attribute не совпадает.',
    'max'      => [
        'string' => 'Поле :attribute не должно превышать :max символов.',
    ],
    'attributes' => [
        'first_name' => 'Имя',
        'last_name'  => 'Фамилия',
        'nickname'   => 'Никнейм',
        'email'      => 'Email',
        'password'   => 'Пароль',
    ],
];