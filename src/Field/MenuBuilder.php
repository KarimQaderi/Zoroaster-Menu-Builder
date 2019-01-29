<?php


    namespace KarimQaderi\ZoroasterMenuBuilder\Field;

;
    use Illuminate\Support\Facades\Auth;
    use KarimQaderi\Zoroaster\Fields\Extend\Field;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Models\Menu;


    class MenuBuilder extends Field
    {

        public $OnCreation = false;
        public $OnUpdate = false;


        public function __construct()
        {
            $this->onlyOnDetail();

        }


        public function viewDetail($field , $data , $resourceRequest = null)
        {

            if(Auth::user()->can('ZoroasterMenuBuilder'))
                return view('Zoroaster-menu-builder::menu-builder')->with([
                    'items' => Menu::whereSlug($data->slug)->first()->optionsMenu() ,
                    'menu' => Menu::whereSlug($data->slug)->first() ,
                ]);

        }
    }