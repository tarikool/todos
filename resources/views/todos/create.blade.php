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



            $('ul li').on('focus', function () {
                alert(55)



            })


            $(document).on('click mouseenter', '.list', function () {
                var id = $(this).data('id')
                console.log( id)
                $('span[id^="del_"]').hide()
                $('span[id^="done_"]').hide()
                $('#del_'+id).show()
                $('#done_'+id).show()
            })


            $('#todo').on('keyup', function (e) {

                if (e.key == 'Enter'){
                    var name = $('#todo').val()

                    if ( name.trim(name))
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
                                    +data['name']
                                    +'<input class="form-control" id="edit_'+data["id"]+'">'
                                    +'<span id="del_'+data["id"]+'">&#10005;</span>'
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