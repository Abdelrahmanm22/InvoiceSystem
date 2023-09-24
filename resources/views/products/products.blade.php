@extends('layouts.master')
@section('title')
    المنتجات
@stop
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    المنتجات</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">

        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- if found error -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </ul>

        </div>
    @endif
    <!-- if added successfully -->
    @if (session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- if updated successfully -->
    @if (session()->has('edit'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- if deleted successfully -->
    @if (session()->has('delete'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- row -->
    <div class="row">
        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">جدول المنتجات</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    @can('اضافة منتج')
                        <div class="col-sm-6 col-md-4 col-xl-3 mg-t-20">
                            <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-flip-horizontal"
                                data-toggle="modal" href="#modaldemo8">اضافة منتج </a>
                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table key-buttons text-md-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0"> اسم المنتج</th>
                                    <th class="border-bottom-0"> الوصف</th>
                                    <th class="border-bottom-0"> القسم</th>
                                    <th class="border-bottom-0"> سعر المنتج</th>
                                    <th class="border-bottom-0"> اقل سعر بيع للمنتج</th>
                                    <th class="border-bottom-0"> سعر الجمله</th>
                                    <th class="border-bottom-0"> الكميه المتاحه </th>
                                    <th class="border-bottom-0"> اسم المسؤول</th>
                                    <th class="border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->Product_name }}</td>
                                        <td>{{ $p->description }}</td>
                                        <td>{{ $p->section->section_name }}</td>
                                        <td>{{ $p->price }}</td>
                                        <td>{{ $p->mini_price}}</td>
                                        <td>{{ $p->Wholesale_Price}}</td>
                                        <td>{{ $p->quantity}}</td>
                                        <td>{{ $p->user }}</td>
                                        <td>

                                            @can('تعديل منتج')
                                                <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                                    data-id="{{ $p->id }}" data-name="{{ $p->Product_name }}"
                                                    data-price="{{ $p->price }}"
                                                    data-mini_price="{{ $p->mini_price }}"
                                                    data-wholesale_price="{{ $p->Wholesale_Price}}"
                                                    data-quantity="{{ $p->quantity}}"
                                                    data-description="{{ $p->description }}"data-section_name="{{ $p->section->section_name }}"
                                                    data-toggle="modal" href="#exampleModal2" title="تعديل"><i
                                                        class="las la-pen"></i>
                                                </a>
                                            @endcan
                                            @can('حذف منتج')
                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $p->id }}" data-name="{{ $p->Product_name }}"
                                                    data-toggle="modal" href="#modaldemo9" title="حذف"><i
                                                        class="las la-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    <!-- Modal effects -->
    <div class="modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Modal Header</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="Product_name">اسم المنتج</label>
                            <input autocomplete="off" type="text" class="form-control" id="Product_name"
                                name="Product_name">
                        </div>
                        <div class="form-group">
                            <label for="price">سعر المنتج</label>
                            <input autocomplete="off" type="text" class="form-control" id="price"
                                name="price">
                        </div>
                        <div class="form-group">
                            <label for="mini_price">اقل سعر للمنتج مسموح البيع به</label>
                            <input autocomplete="off" type="text" class="form-control" id="mini_price"
                                name="mini_price">
                        </div>
                        <div class="form-group">
                            <label for="Wholesale_Price">سعر الجمله</label>
                            <input autocomplete="off" type="text" class="form-control" id="Wholesale_Price"
                                name="Wholesale_Price">
                        </div>
                        <div class="form-group">
                            <label for="quantity">الكميه</label>
                            <input autocomplete="off" type="text" class="form-control" id="quantity"
                                name="quantity">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                        <select name="section_id" id="section_id" class="form-control" required>
                            <option value="" selected disabled> --حدد القسم--</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">تاكيد</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- End Modal effects-->

    <!-- edit -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل المنتج</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('products.update') }}" method="post" autocomplete="off">
                        {{ method_field('post') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="">
                            <label for="Product_name" class="col-form-label">اسم المنتج:</label>
                            <input autocomplete="off" class="form-control" name="Product_name" id="Product_name"
                                type="text">
                        </div>
                        <div class="form-group">
                            <label for="price">سعر المنتج</label>
                            <input autocomplete="off" type="text"  class="form-control" id="price"
                                name="price">
                        </div>
                        <div class="form-group">
                            <label for="mini_price">اقل سعر للمنتج مسموح البيع به</label>
                            <input autocomplete="off" type="text" class="form-control" id="mini_price"
                                name="mini_price">
                        </div>
                        <div class="form-group">
                            <label for="Wholesale_Price">سعر الجمله</label>
                            <input autocomplete="off" type="text" class="form-control" id="Wholesale_Price"
                                name="Wholesale_Price">
                        </div>
                        <div class="form-group">
                            <label for="quantity">الكميه</label>
                            <input autocomplete="off" type="text" class="form-control" id="quantity"
                                name="quantity">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">ملاحظات:</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <label class="my-1 mr-2" for="inlineFormCustomSelectPref">القسم</label>
                        <select name="section_id" id="section_id" class="custom-select my-1 mr-sm-2" required>
                            <option  value="" selected disabled></option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                            @endforeach
                        </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">تاكيد</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- delete -->
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('products.destroy') }}" method="post">
                    {{ method_field('post') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>هل انت متاكد من عملية الحذف ؟</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="Product_name" id="Product_name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/modal.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <script>
        $('#exampleModal2').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var Product_name = button.data('name')
            var section_name = button.data('section_name')
            var description = button.data('description')
            var price = button.data('price')
            var mini_price = button.data('mini_price')
            var Wholesale = button.data('wholesale_price')
            var quantity = button.data('quantity')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #Product_name').val(Product_name);
            modal.find('.modal-body #description').val(description);
            modal.find('.modal-body #section_id').val(section_name);
            modal.find('.modal-body #price').val(price);
            modal.find('.modal-body #mini_price').val(mini_price);
            modal.find('.modal-body #Wholesale_Price').val(Wholesale);
            modal.find('.modal-body #quantity').val(quantity);
        })
    </script>
    <script>
        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var id = button.data('id')
            var Product_name = button.data('name')
            var modal = $(this)
            modal.find('.modal-body #id').val(id);
            modal.find('.modal-body #Product_name').val(Product_name);
        })
    </script>
    <script>
        // Get references to the input fields
        const miniPriceInput = document.getElementById('mini_price');
        const priceInput = document.getElementById('price');
        const wholesalePriceInput = document.getElementById('Wholesale_Price');
    
        // Add an event listener to the mini_price input field
        miniPriceInput.addEventListener('input', checkPrices);
        
        // Add an event listener to the price input field
        priceInput.addEventListener('input', checkPrices);
    
        // Add an event listener to the Wholesale_Price input field
        wholesalePriceInput.addEventListener('input', checkPrices);
    
        function checkPrices() {
            const miniPrice = parseFloat(miniPriceInput.value);
            const price = parseFloat(priceInput.value);
            const wholesalePrice = parseFloat(wholesalePriceInput.value);
    
            if (!isNaN(miniPrice) && !isNaN(price) && !isNaN(wholesalePrice)) {
                if (miniPrice > price || wholesalePrice > price || wholesalePrice>miniPrice) {
                    // Display an error message or change the style to indicate the error
                    if (miniPrice > price) {
                        miniPriceInput.setCustomValidity('السعر الأدنى يجب أن يكون أقل من أو يساوي السعر');
                    } else {
                        miniPriceInput.setCustomValidity('');
                    }
    
                    if (wholesalePrice > price) {
                        wholesalePriceInput.setCustomValidity('سعر الجملة يجب أن يكون أقل من أو يساوي السعر');
                    } else {
                        wholesalePriceInput.setCustomValidity('');
                    }

                    if (wholesalePrice > miniPrice) {
                        wholesalePriceInput.setCustomValidity('سعر الجملة يجب أن يكون أقل من أو يساوي السعر الأدني للبيع');
                    } else {
                        wholesalePriceInput.setCustomValidity('');
                    }
                } else {
                    // Clear any previous validation messages
                    miniPriceInput.setCustomValidity('');
                    wholesalePriceInput.setCustomValidity('');
                }
            }
        }
    </script>
    
@endsection
