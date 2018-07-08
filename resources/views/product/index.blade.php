<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">


    <!-- Place your css Styles here -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</head>

<body>

    <div class="container">
        <div class="row">
            <h3>Add product</h3>
            <hr>
            <div class="col-md-6">
                {!! Form::open(array( 'id' => 'submit_product')) !!}
                <div class="form-group">
                    {!! Form::label('Product', 'Product name') !!}
                    {!! Form::text('name', '', array('class' => 'form-control', 'id' => 'name')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Quantity', 'Quantity') !!}
                    {!! Form::text('qty', '', array('class' => 'form-control', 'id' => 'qty')) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Price', 'Price') !!}
                    {!! Form::text('price', '', array('class' => 'form-control', 'id'=>'price')) !!}
                </div>
                {!! Form::submit('Add product', array('class' => 'btn btn-primary')) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>


    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit_product').submit(function(ev) {
                ev.preventDefault();
                var formDatat = $('#submit_product').serialize();

                $.ajax({
                    url: "{{ route('product.store') }}",
                    type: 'post',
                    data: formDatat,
                    beforeSend: function () {

                    },
                    success: function (response) {
                        alert(response);
                        $('#name').val('');
                        $('#qty').val('');
                        $('#price').val('');
                    }
                });
            });
        });
    </script>
</body>

</html>