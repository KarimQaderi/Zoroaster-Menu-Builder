<?php

    namespace KarimQaderi\ZoroasterMenuBuilder\Http\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Support\Facades\Route;
    use KarimQaderi\ZoroasterMenuBuilder\Http\Models\Menu;

    class MenuItems extends Model
    {
        protected $guarded = [];

        /**
         * @var array
         */
        protected $fillable = ['menu_id' , 'name' , 'url' , 'route' , 'parameters' , 'target' , 'parent_id' , 'order' , 'enabled' , 'classes'];

        /**
         * @var mixed
         */
        protected $with = ['children'];

        /**
         * @var array
         */
        protected $casts = [
            'enabled' => 'boolean' ,
        ];

        /**
         * @var array
         */
        protected $appends = ['link' , 'type' , 'enabledClass'];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function menu(): BelongsTo
        {
            return $this->belongsTo(Menu::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function children(): HasMany
        {
            return $this->hasMany(self::class , 'parent_id')->with('children');
        }

        /**
         * @param $parentId
         *
         * @return Collection
         */
        public function itemsChildren($parentId)
        {
            return $this->whereParentId($parentId);
        }

        /**
         * Get Type attribute
         *
         * @return  string
         */
        public function getTypeAttribute()
        {
            if($this->route != null){
                return 'route';
            }

            return 'link';
        }

        /**
         * Get Enabled class attribute
         *
         * @return  string
         */
        public function getEnabledClassAttribute()
        {
            return ($this->enabled) ? 'enabled' : 'disabled';
        }


        /**
         * Get the link attribute
         *
         * @param   boolean $absolute
         *
         * @return  string
         */
        public function getLinkAttribute($absolute = false)
        {


            if($this->getTypeAttribute() == 'route'){


                $this->parameters = json_decode($this->parameters , true);


                if(!is_null($this->route)){
                    if(!Route::has($this->route)){
                        return '#';
                    }


                    try{
                        return url(route($this->route , $this->parameters , $absolute));

                    } catch(\Exception $exception){
                        return 'پارامتر ها نادرست هستن';
                    }

                }
            } else{

                if(starts_with($this->url , 'http')){
                    $absolute = true;
                }

                if($absolute){
                    return url($this->url);
                }
            }

            return $this->url;
        }

        /**
         * Return the html link
         *
         * @return  string
         */
        public function html()
        {
            return '<a class="nav-link ' . $this->classes . '" href="' . $this->link . '" target="' . $this->target . '">' . $this->name . '</a>';
        }
    }
