@extends('master')


@section('content')
    <div class="col-md-6 offset-md-3" style="margin-top: 100px;">
        <h1 class="text-center"> todos</h1>
        <div class="form-group" style="margin-top: 35px;">
            {{ csrf_field() }}
            <input type="text" class="form-control" id="todo" placeholder="What you want to do?">
        </div>

            <ul id="ul">
                @foreach( $items as $item)
                    <li class="list {{ !$item->status ? 'active' :'done' }}" data-id="{{ $item->id }}">
                        <p>
                            <span id="done_{{$item->id}}">&#10003;</span>
                            <span id="text_{{$item->id}}" class="{{ $item->status ? 'line' : '' }}">{{ $item->name }}</span>
                            <span id="del_{{$item->id}}">&#10005;</span>
                        </p>
                        <input type="text" id="edit_{{$item->id}}" class="form-control edit" value="{{ $item->name }}">
                    </li>
                @endforeach
            </ul>



        <div class="row footer">
            <div class="col-md-3">
                <span id="left"></span> Task's Pending
            </div>

            <div class="col-md-6">
                <div style="margin-left: 33px;">
                    <span class="menu" id="all">All</span>
                    <span class="menu" id="active">Active</span>
                    <span class="menu" id="done">Completed</span>
                </div>
            </div>

            <div class="col-md-3">
                <span id="clear">Clear Completed</span>
            </div>
        </div>




    </div>
@endsection


@section('scripts')

    <script>
        $(document).ready(function () {
            $('#active').click(function () {
                $('.done').hide()
                $('.active').show()
            })

            $('#done').click(function () {
                $('.active').hide()
                $('.done').show()
            })

            $('#all').click(function () {
                $('.active').show()
                $('.done').show()
            })

        })
    </script>

    <script>

        $(document).ready(function () {
            var _token = $('input[name="_token"]').val()
            var action; var state; var id = 0;
            var all = @json(count($items));
            var done = @json(count($items->where('status', 1)));

            total(done)

            function total(done){
                var pending = all - done
                done > 0 ? $('#clear').show() : $('#clear').hide()
                $('#left').text(pending)
            }

            $(document).on('mouseenter', '.list', function () {
                id = $(this).data('id')
                // console.log( id)
                action = '';
                display(action)

            });

            $(document).on('mouseleave', '.list', function () {
                $('#del_'+id).hide()
                $('#done_'+id).hide()
                $('#edit_'+id).hide()
                action = '';
                display(action)
            });




            function display(action){

                if (action == 'editing' ){
                    $('#done_'+id).hide()
                    $('#text_'+id).hide()
                    $('#del_'+id).hide()
                    $('#edit_'+id).show()
                }else{
                    $('span[id^="del_"]').hide()
                    $('span[id^="done_"]').hide()
                    $('#del_'+id).show()
                    $('#done_'+id).show()
                    $('#text_'+id).show()
                    $('#edit_'+id).val($('#text_'+id).text()).hide()
                }

                // if ( action !='editing'){
                //     $('span[id^="del_"]').hide()
                //     $('span[id^="done_"]').hide()
                //     $('#del_'+id).show()
                //     $('#done_'+id).show()
                // }



            }


            $(document).on('click dblclick', 'span[id^="text_"]', function () {
                action = 'editing';
                display(action)

            })


            $(document).on('keyup', '.edit', function (e) {
                var operation = 'update';
                if (e.key == 'Enter')
                    update(operation)
            })


            $(document).on('click', 'span[id^="done_"]', function () {
                var operation = 'status'
                update(operation)

            })

            $(document).on('click', 'span[id^="del_"]', function () {
                var operation = 'delete'
                update(operation)

            })


            $('#clear').on('click', function () {
                var operation = 'clear'
                update(operation)
            })



            function update(operation){
                var name = $('#edit_'+id).val()
                // console.log(operation, name, id, _token)

                $.ajax({
                    type: 'PATCH',
                    url: "todos/"+id,
                    data: {_token: _token, name: name, operation: operation},
                    success: function (data) {
                        // console.log(data)
                        if ( data['operation'] == 'update'){
                            $('#text_'+data['item']['id']).text(data['item']['name']).show()
                            $('#edit_'+data['item']['id']).val(data['item']['name']).hide()
                            action = '';
                            display(action)
                        }


                        if (data['operation'] == 'status') {
                            if (data['item']['status'] == 1){
                                $('.list[data-id="'+data["item"]["id"]+'"]').removeClass('active')
                                $('.list[data-id="'+data["item"]["id"]+'"]').addClass('done')
                                $('#text_'+data['item']['id']).addClass('line')
                                done += 1;

                            }
                            else{
                                $('.list[data-id="'+data["item"]["id"]+'"]').removeClass('done')
                                $('.list[data-id="'+data["item"]["id"]+'"]').addClass('active')
                                $('#text_'+data['item']['id']).removeClass('line')
                                done -=1;
                            }
                        }


                        if (data['operation'] == 'delete'){
                            $('.list[data-id="'+data["item"]["id"]+'"]').remove()
                            done -= data['item']['status'] ? 1 : 0;
                            all -= 1;

                        }

                        if (data['operation'] == 'clear'){
                            $('.done').remove()
                            all -= done;
                            done = 0;
                        }


                        total(done)
                    }
                })

            }





            $('#todo').on('keyup', function (e) {

                if (e.key == 'Enter'){
                    var name = $('#todo').val()

                    if ( name.trim())
                        append()


                    function append() {

                        $.ajax({
                            type: 'POST',
                            url: '{{ route("todos.store") }}',
                            data:{_token: _token, name: name},
                            success: function (data) {
                                // console.log(data)
                                $('#ul').append(
                                    '<li class="list active" data-id="'+data["id"]+'">'+'<p>'
                                    +'<span id="done_'+data["id"]+'">&#10003;</span>'
                                    +'<span id="text_'+data["id"]+'">'+data['name']+'</span>'
                                    +'<span id="del_'+data["id"]+'">&#10005;</span>'+'</p>'
                                    +'<input type="text" class="form-control edit" id="edit_'+data["id"]+'" value="'+data["name"]+'">'
                                    +'</li>'
                                )
                                all +=1;
                                total(done)
                            }

                        })

                    }

                }

            })

        })

    </script>
@endsection