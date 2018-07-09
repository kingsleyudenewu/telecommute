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
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>

<body>

    <div class="container">
        <div class="row clearfix">
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
        <br><br><br><br>
        <div class="row">
            <table class="table table-bordered table-striped" id="product_table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Update Product Here -->
    <div id="product-modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Company
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </h4>
                </div>
                <div class="modal-body">
                    <form method="POST" autocomplete="off" id="product_edit_form">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="company name" class="control-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" placeholder="Product Name" name="name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="qty" class="control-label">Quantity</label>
                                    <input type="text" class="form-control" id="qty" placeholder="Quantity" name="qty">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="price" class="control-label">Price</label>
                                    <input type="text" class="form-control" id="price" placeholder="price" name="price">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-info" value="Update Product" />
                            </div>
                        </div>
                    </form>
                    <div class="deleteContent">
                        Are you Sure you want to delete this company
                    </div>
                </div>
                <div class="modal-footer">
                    {{--<button type="button" class="btn btn-danger actionBtn waves-effect" data-dismiss="modal">Delete</button>--}}
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>

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

                        window.location.href = "{{ route('product.index') }}";
                    }
                });
            });

            var dataTable = $('#product_table').DataTable({
                "processing": true,
                "serverSide": true,
                "language": {
                    "processing": "Processing Request"
                },
                "ajax":{
                    url :"{{ route('getAllProduct') }}", // json datasource
                    type: "get"
                },
                searchDelay: 350,
                "lengthMenu": [[10, 25, 50, 100, 200, 500], [10, 25, 50, 100, 200, 500]],
                aoColumns: [
                    { data: 'name', name:'name' },
                    { data: 'qty', name: 'qty' },
                    { data: 'price', name: 'price' },
                    { data: 'amount', name: 'amount '},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

//            Delete Company details
            $(document).on('click', '.del_prod', function(ev) {
                ev.preventDefault();
                $('.actionBtn').show();
                $('.deleteContent').show();
                var val = $(this).data('delete-prod');

                var r = confirm("Do you want to delete this product");
                if (r == true) {
                    $.ajax({
                        type: 'post',
                        url: "product/"+val,
                        data: {
                            '_method': 'DELETE',
                            'id': val
                        },
                        success: function(data) {
                            alert(data);
                            window.location.href = "{{ url('/') }}";
                        }
                    });
                }
            });

//            Edit company details
            $(document).on('click', '.edit_prod', function(ev) {
                ev.preventDefault();
                $('.actionBtn').hide();
                $('.deleteContent').hide();
                var val = $(this).data('edit-prod');

                $.ajax({
                    url: 'product/'+val,
                    type: 'GET',
                    beforeSend: function ()
                    {

                    },
                    success: function(response) {
                        console.log(response);
                        if(response.name !== ""){
                            $('#product_edit_form')
                                .find('[name="name"]').val(response.name).end()
                                .find('[name="qty"]').val(response.qty).end()
                                .find('[name="price"]').val(response.price).end();

                            $("#product_edit_form").attr("action", "product/"+response.id);

                            $("#product-modal-edit").modal({backdrop: 'static', keyboard: true});
                        }
                    },
                    error: function(response) {
                        alert('Operation failed');
                    }
                });
            });
        });
    </script>
</body>

</html>