@extends('master')


@section('content')
    <div class="col-md-6 offset-md-3" style="margin-top: 100px;">
        <h1 class="text-center"> todos</h1>
        <div class="form-group" style="margin-top: 35px;">
            {{ csrf_field() }}
            <input type="text" class="form-control" id="todo" placeholder="What you want to do?">
        </div>

            <ul id="ul">

            </ul>


        <div id="here">

        </div>

    </div>
@endsection


{{--@section('styles')--}}
{{--    --}}
{{--@endsection--}}



@section('scripts')
    <script>



        $(document).ready(function () {
            var _token = $('input[name="_token"]').val()
            var action; var state = 1; var id = 0;



            $('ul li').on('focus', function () {
                alert(55)



            })



            $(document).on('mouseenter', '.list', function () {
                id = $(this).data('id')
                console.log( id)
                if ( action !='editing'){
                    $('span[id^="del_"]').hide()
                    $('span[id^="done_"]').hide()
                    $('#del_'+id).show()
                    $('#done_'+id).show()
                }
            });


            // $(document).on()


            $(document).on('click dblclick', '.list', function () {
                action = 'editing';
                $('#done_'+id).hide()
                $('#text_'+id).hide()
                $('#del_'+id).hide()
                $('#edit_'+id).show()
            })
            



            // if ( id > 0)
                $(document).on('keyup', '.edit', function () {
                    // alert( id)
                    var name = $(this).val()
                    console.log(id+'->'+name)
                })


            
            $(document).on('keyup', '#edit_'+id, function (e) {
                alert(id)
                if (e.key == 'Enter') {
                    var name = $(this).val()
                    if ( name.trim())
                        update(name)
                    else alert('Please Enter a Name')
                }
            })


            function update(name){

                $.ajax({
                    type: 'PATCH',
                    url: 'todos/'+id,
                    data: {_token: _token, name: name},
                    success: function (data) {
                        console.log(data)
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
                                    '<li class="list" data-id="'+data["id"]+'">'
                                    +'<span id="done_'+data["id"]+'">&#10003;</span>'
                                    +'<span id="text_'+data["id"]+'">'+data['name']+'</span>'
                                    +'<span id="del_'+data["id"]+'">&#10005;</span>'
                                    +'<input type="text" class="form-control edit" id="edit_'+data["id"]+'" value="'+data["name"]+'">'
                                    +'</li>'
                                )

                            }

                        })

                    }



                }

            })

        })
    </script>
@endsection