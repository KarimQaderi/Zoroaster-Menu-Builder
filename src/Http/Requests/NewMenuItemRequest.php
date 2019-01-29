<?php

namespace KarimQaderi\ZoroasterMenuBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class NewMenuItemRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
//            'menu_id' => 'required|exists:menus,id',
            'name'    => 'required',
            'type'    => 'required|in:link,route',
            'target'  => 'required|in:_self,_blank',
        ];

        if (request()->get('type') == 'link') {
            $rules['url'] = 'required';
        }

        if (request()->get('type') == 'route') {
            $rules['route'] = [
                'required',
                function ($attribute, $value, $fail) {
                    if (Route::has($value)) {
                        return true;
                    }

                    return $fail(ucfirst($attribute).' not is a real route name');
                },
            ];
            // $rules['parameters'] = 'required';
        }

        return $rules;
    }
}
