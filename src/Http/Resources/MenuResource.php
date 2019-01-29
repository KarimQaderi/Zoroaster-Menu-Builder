<?php

    namespace KarimQaderi\ZoroasterMenuBuilder\Http\Resources;

    use KarimQaderi\Zoroaster\Fields\btnSave;
    use KarimQaderi\Zoroaster\Fields\Group\Panel;
    use KarimQaderi\Zoroaster\Fields\Group\RowOneCol;
    use KarimQaderi\Zoroaster\Fields\Group\RowOneColBg;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Models\Menu;
    use KarimQaderi\Zoroaster\Abstracts\ZoroasterResource;
    use KarimQaderi\Zoroaster\Fields\Group\Html;
    use KarimQaderi\Zoroaster\Fields\ID;
    use KarimQaderi\Zoroaster\Fields\Text;
    use KarimQaderi\ZoroasterMenuBuilder\Field\MenuBuilder;


    class MenuResource extends ZoroasterResource
    {
        /**
         * The model the resource corresponds to.
         *
         * @var string
         */
        public static $model = Menu::class;


        public $uriKey = 'menu-builder';

        public $label = 'منو ها';

        public $singularLabel = 'منو ';


        /**
         * Get the fields displayed by the resource.
         *
         * @param  \Illuminate\Http\Request $request
         * @return array
         */
        public function fields()
        {
            $id=isset($this->id)? ','.$this->id : '';
            return [
                new Panel('' , [

                    new RowOneCol([
                        ID::make()->exceptOnForms()->sortable() ,
                    ]) ,

                    new RowOneCol([
                        Text::make('نام' , 'name')
                            ->sortable()
                            ->rules('required' , 'max:255' , 'unique:menus,name'.$id) ,
                    ]) ,

                    new RowOneCol([
                        Text::make('Slug' , 'slug')->hideWhenCreating()->hideWhenUpdating() ,
                    ]) ,

                    new RowOneCol([
                        Text::make('تابع کمکی')->displayUsing(function(){
                            return "<code><span class='uk-text-primary'>{!!</span> <span class='uk-text-primary'>menu_builder(</span><span class='uk-text-success'>'" . $this->slug . "'</span><span class='uk-text-primary'>)</span> <span class='uk-text-primary'>!!}</span></code>";
                        })->exceptOnForms() ,
                    ]) ,
                ]) ,

                new RowOneColBg([
                    btnSave::make() ,
                ]) ,

                MenuBuilder::make() ,

            ];
        }


        function filters()
        {
            // TODO: Implement filters() method.
        }
    }
