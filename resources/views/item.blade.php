<ol class="dd-list">
    @foreach($items as $item)
        <li class="dd-item" data-id="{{ $item->id }}">
            <span class="btn dd_delete" uk-icon="delete"></span>
            <span class="btn dd_edit" uk-icon="edit"></span>
            <div class="dd-handle"  data-enabled="{{ $item->enabled }}"><span class="name">{{ $item->name }} </span><span class="link">{{ $item->link }}</span></div>
            @if (count($item->children)!=0)
                @include('Zoroaster-menu-builder::item',['items'=>$item->children])
            @endif
        </li>
    @endforeach
</ol>