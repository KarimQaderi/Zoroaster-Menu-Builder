<div class="Zoroaster-menu-builder">
    <div class="uk-child-width-1-2 resourceName_2 view_Details uk-grid" uk-grid>
        <div class="uk-first-column">
            <h1 class="resourceName">آیتم های منو</h1>
        </div>
        <div class="uk-text-left ResourceActions">
            <button class="add">اضافه کردن آیتم</button>
        </div>
    </div>

    <div class="dd panel">
        @include('Zoroaster-menu-builder::item',['items'=>$items])
    </div>
</div>

<!-- This is the modal -->
<div id="modal-Zoroaster-menu-builder" uk-modal>
    <div class="uk-modal-dialog html">

    </div>
</div>


<script>

    $('.dd').nestable({
        callback: function(l,e){
            $.ajax({
                type: 'get',
                url: '{{ route('Zoroaster-menu-builder.saveItems') }}',
                data: {
                    items: $('.dd').nestable('serialize'),
                    menu_id: '{{ $menu->id }}',
                }
            });
        }
    });



    $(document).on('click', '.Zoroaster-menu-builder .add', function () {
        $id = $(this).closest('.dd-item').attr('data-id');
        UIkit.modal('#modal-Zoroaster-menu-builder').show();
        $.ajax({
            type: 'get',
            url: '{{ route('Zoroaster-menu-builder.find') }}',
            data: {
                route: 'new-item',
                menu_id: '{{ $menu->id }}',
            },
            success: function (data) {
                $('#modal-Zoroaster-menu-builder .html').html(data);
            }
        });

    });


    $(document).on('click', '.Zoroaster-menu-builder .dd_edit', function () {
        $id = $(this).closest('.dd-item').attr('data-id');
        UIkit.modal('#modal-Zoroaster-menu-builder').show();
        $.ajax({
            type: 'get',
            url: '{{ route('Zoroaster-menu-builder.find') }}',
            data: {
                route: 'update',
                id: $id,
                menu_id: '{{ $menu->id }}',
            },
            success: function (data) {
                $('#modal-Zoroaster-menu-builder .html').html(data);
            }
        });

    });


    $(document).on('click', '.Zoroaster-menu-builder .dd_delete', function () {
        $id = $(this).closest('.dd-item').attr('data-id');

        UIkit.modal.confirm(
            '<h2>حذف ایتم</h2>'
            , {
                labels: {ok: 'حذف', cancel: 'خیر'},
                addClass: 'modal_delete'
            }).then(function () {
            $.ajax({
                type: 'get',
                url: '{{ route('Zoroaster-menu-builder.destroy') }}',
                data: {
                    route: 'update',
                    id: $id,
                    menu_id: '{{ $menu->id }}',
                },
                success: function (data) {
                    location.reload();
                }
            });
        });



    });

</script>
