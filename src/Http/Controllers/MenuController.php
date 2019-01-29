<?php

    namespace KarimQaderi\ZoroasterMenuBuilder\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use KarimQaderi\Zoroaster\Fields\Boolean;
    use KarimQaderi\Zoroaster\Fields\btnSave;
    use KarimQaderi\Zoroaster\Fields\Group\Form;
    use KarimQaderi\Zoroaster\Fields\Group\Panel;
    use KarimQaderi\Zoroaster\Fields\InputHidden;
    use KarimQaderi\Zoroaster\Fields\Select;
    use KarimQaderi\Zoroaster\Fields\Text;
    use KarimQaderi\Zoroaster\Sidebar\FieldMenu\Divider;
    use KarimQaderi\Zoroaster\Traits\Builder;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Models\Menu;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Models\MenuItems;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Requests\NewMenuItemRequest;

    class MenuController extends Controller
    {
        use Builder;


        /**
         * Save menu items when reordering
         *
         * @param   Request $request
         *
         * @return  json
         */
        public function saveItems(Request $request)
        {
            $menu = Menu::find((int)$request->get('menu_id'));
            $items = $request->get('items');
            $i = 1;
            foreach($items as $item){
                $this->saveMenuItem($i , $item);
                $i++;
            }

            return response()->json([
                'success' => true ,
            ]);
        }

        /**
         * Create new menu item
         *
         * @param   NewMenuItemRequest $request
         *
         * @return  json
         */
        public function createNew(NewMenuItemRequest $request)
        {
            $data = $request->all(['name' ,'enabled', 'url' , 'route' , 'parameters' , 'parameters' , 'classes' , 'menu_id' , 'target']);
            $data['order'] = MenuItems::max('id') + 1;
            $menuItem = MenuItems::create($data);

            return back();
        }


        /**
         * Update the given menu item
         *
         * @param   \KarimQaderi\ZoroasterMenuBuilder\Http\Models\MenuItems $item
         * @param   NewMenuItemRequest $request
         *
         * @return  json
         */
        public function update(MenuItems $item , NewMenuItemRequest $request)
        {
            $item->whereId($request->id)->update($request->all(['name' ,'enabled', 'url' , 'route' , 'parameters' , 'parameters' , 'classes' , 'menu_id' , 'target']));

            return back();
        }

        /**
         * Destroy current menu item and all his childrens
         *
         * @param   \KarimQaderi\ZoroasterMenuBuilder\Http\Models\MenuItems $item
         *
         * @return  json
         */
        public function destroy(Request $request)
        {
            $item = new MenuItems();
            $item = $item->find($request->id);
            $item->children()->delete();
            $item->delete();

            return response()->json([
                'success' => true ,
            ]);
        }

        /**
         * Save the menu item
         *
         * @param   int $order
         * @param   array $item
         * @param   int $parentId
         *
         */
        private function saveMenuItem($order , $item , $parentId = null)
        {
            $menuItem = MenuItems::find($item['id']);
            $menuItem->order = $order;
            $menuItem->parent_id = $parentId;
            $menuItem->save();

            $this->checkChildren($item);
        }

        /**
         * Recurisve save menu items childrens
         *
         * @param   array $item
         *
         */
        private function checkChildren($item)
        {
            if(isset($item['children']) && count($item['children']) > 0){
                $i = 1;
                foreach($item['children'] as $child){
                    $this->saveMenuItem($i , $child , $item['id']);
                    $i++;
                }
            }
        }


        public function find(Request $request)
        {
            $menuItem = null;

            if($request->route == 'update')
                $menuItem = MenuItems::find($request->id);

            Session()->put('_old_input.menu_id' , $request->menu_id);


            return self::RenderViewForm([

                Form::make(route('Zoroaster-menu-builder.' . $request->route) , 'POST' , false , [

                    InputHidden::make('menu_id') ,

                    InputHidden::make('id') ,

                    Text::make('نام' , 'name') ,

                    Select::make('نوع لینک' , 'type')->options([
                        'link' => 'link' ,
                        'route' => 'route' ,
                    ])->activeEelementByClass([
                        'link' => 'activeEelement_link' ,
                        'route' => 'activeEelement_route' ,
                    ]) ,

                    new Panel('' , [
                        Text::make('لینک' , 'url')->help('http://127.0.0.1:8000/Zoroaster') ,
                    ] , 'activeEelement_link') ,

                    new Panel('' , [
                        Text::make('Route' , 'route')->help('Zoroaster.resource.show') ,
                        Text::make('Parameters' , 'parameters')->help('{"resourceId":1,"resource":"user"}') ,
                    ] , 'activeEelement_route') ,

                    Select::make('باز شدن در' , 'target')->options([
                        '_self' => 'در این صفحه' ,
                        '_blank' => 'در صفحه جدید' ,
                    ]) ,

                    Select::make('وضعیت' , 'enabled')->options([
                        '1' => 'فعال' ,
                        '0' => 'غیرفعال' ,
                    ]) ,

                    Text::make('Classes' , 'classes') ,

                    Divider::make() ,

                    btnSave::make() ,
                ]) ,

            ] ,
                function($field) use ($menuItem){

                    if(empty($menuItem) && isset($field->showOnCreation) && $field->showOnCreation == false) return false;
                    if(isset($field->canSee) && $field->canSee == false) return false;
                    return true;
                } ,
                'viewForm' , $menuItem , null);
        }
    }
