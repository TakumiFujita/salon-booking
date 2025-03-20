<?php

namespace App\Http\Requests\Stylist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $rules = [
      'name' => 'required|string|max:255',
      'email' => [
        'required',
        'email',
        'max:255',
        Rule::unique('stylists', 'email')->ignore(Auth::guard('stylist')->id()),
      ],
      'introduction' => 'required|string|max:500', // 新しく追加された項目
    ];

    // パスワードが入力された場合のみバリデーションを追加
    if ($this->filled('password')) {
      $rules['password'] = 'required|string|min:4|confirmed';
    }

    return $rules;
  }
}
