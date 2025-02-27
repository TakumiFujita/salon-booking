<?php

return [
    'accepted' => ':attribute を承認してください。',
    'accepted_if' => ':other が :value のとき、:attribute を承認してください。',
    'active_url' => ':attribute は有効なURLではありません。',
    'after' => ':attribute は :date より後の日付でなければなりません。',
    'after_or_equal' => ':attribute は :date 以降の日付でなければなりません。',
    'alpha' => ':attribute は文字のみを含むことができます。',
    'alpha_dash' => ':attribute は文字、数字、ダッシュ、アンダースコアのみを含むことができます。',
    'alpha_num' => ':attribute は文字と数字のみを含むことができます。',
    'array' => ':attribute は配列でなければなりません。',
    'ascii' => ':attribute にはシングルバイトの英数字と記号のみを含むことができます。',
    'before' => ':attribute は :date より前の日付でなければなりません。',
    'before_or_equal' => ':attribute は :date 以前の日付でなければなりません。',
    'between' => [
        'array' => ':attribute の項目数は :min ～ :max でなければなりません。',
        'file' => ':attribute のファイルサイズは :min ～ :max KBでなければなりません。',
        'numeric' => ':attribute は :min ～ :max の間でなければなりません。',
        'string' => ':attribute の文字数は :min ～ :max の間でなければなりません。',
    ],
    'boolean' => ':attribute は true または false でなければなりません。',
    'confirmed' => ':attribute の確認が一致しません。',
    'current_password' => '現在のパスワードが正しくありません。',
    'date' => ':attribute は有効な日付ではありません。',
    'date_equals' => ':attribute は :date と同じ日付でなければなりません。',
    'date_format' => ':attribute は :format 形式と一致しません。',
    'different' => ':attribute と :other は異なっていなければなりません。',
    'digits' => ':attribute は :digits 桁でなければなりません。',
    'digits_between' => ':attribute は :min ～ :max 桁でなければなりません。',
    'email' => ':attribute は有効なメールアドレスでなければなりません。',
    'ends_with' => ':attribute は次のいずれかで終了する必要があります: :values。',
    'gt' => [
        'array' => ':attribute の項目数は :value を超える必要があります。',
        'file' => ':attribute のファイルサイズは :value KBを超える必要があります。',
        'numeric' => ':attribute は :value を超える必要があります。',
        'string' => ':attribute の文字数は :value を超える必要があります。',
    ],
    'gte' => [
        'array' => ':attribute の項目数は :value 以上でなければなりません。',
        'file' => ':attribute のファイルサイズは :value KB以上でなければなりません。',
        'numeric' => ':attribute は :value 以上でなければなりません。',
        'string' => ':attribute の文字数は :value 以上でなければなりません。',
    ],
    'image' => ':attribute は画像でなければなりません。',
    'in' => '選択された :attribute は無効です。',
    'integer' => ':attribute は整数でなければなりません。',
    'ip' => ':attribute は有効なIPアドレスでなければなりません。',
    'json' => ':attribute は有効なJSON文字列でなければなりません。',
    'max' => [
        'array' => ':attribute の項目数は :max 以下でなければなりません。',
        'file' => ':attribute のファイルサイズは :max KB以下でなければなりません。',
        'numeric' => ':attribute は :max 以下でなければなりません。',
        'string' => ':attribute の文字数は :max 以下でなければなりません。',
    ],
    'min' => [
        'array' => ':attribute の項目数は少なくとも :min 個でなければなりません。',
        'file' => ':attribute のファイルサイズは少なくとも :min KBでなければなりません。',
        'numeric' => ':attribute は少なくとも :min でなければなりません。',
        'string' => ':attribute の文字数は少なくとも :min 文字でなければなりません。',
    ],
    'required' => ':attribute は必須です。',
    'unique' => ':attribute はすでに使用されています。',
    'url' => ':attribute は有効なURLでなければなりません。',

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'name' => '名前',
    ],
];
