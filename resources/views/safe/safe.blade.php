@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@section('title')
    الخزنه
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الخزنه</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/صفحة
                الخزنه</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@if (session()->has('errorMoney'))
    <script>
        window.onload = function() {
            notif({
                msg: "لا يوجد في الخزنه المبلغ المراد سحبه!",
                type: "warning"
            })
        }
    </script>
@endif
@if (session()->has('lessThanZero'))
    <script>
        window.onload = function() {
            notif({
                msg: "غير مسموح بسحب مبلغ اصغر من 0",
                type: "warning"
            })
        }
    </script>
@endif
@if (session()->has('success'))
    <script>
        window.onload = function() {
            notif({
                msg: "تم السحب بنجاح",
                type: "success"
            })
        }
    </script>
@endif
<!-- row -->
<div class="row row-sm">
    {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-primary-gradient">
            <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                <div class="">
                    <h6 class="mb-3 tx-12 text-white">اجمالي الفواتير</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div class="">
                            <h4 class="tx-20 font-weight-bold mb-1 text-white">

                                {{ number_format(\App\Models\Invoice::sum('Total'), 2) }}
                            </h4>
                            <p class="mb-0 tx-12 text-white op-7">{{ \App\Models\Invoice::count() }}</p>
                        </div>
                        <span class="float-right my-auto mr-auto">
                            <i class="fas fa-arrow-circle-up text-white"></i>
                            <span class="text-white op-7">100%</span>
                        </span>
                    </div>
                </div>
            </div>
            <span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
        </div>
    </div> --}}
    {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-danger-gradient">
            <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                <div class="">
                    <h6 class="mb-3 tx-12 text-white">الفواتير الغير مدفوعة</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div class="">
                            <h3 class="tx-20 font-weight-bold mb-1 text-white">

                                {{ number_format(\App\Models\Invoice::where('Value_Status', 2)->sum('Total'), 2) }}

                            </h3>
                            <p class="mb-0 tx-12 text-white op-7">
                                {{ \App\Models\Invoice::where('Value_Status', 2)->count() }}
                            </p>
                        </div>
                        <span class="float-right my-auto mr-auto">
                            <i class="fas fa-arrow-circle-down text-white"></i>
                            <span class="text-white op-7">

                                @php
                                    $count_all = \App\Models\Invoice::count();
                                    $count_invoices2 = \App\Models\Invoice::where('Value_Status', 2)->count();
                                    
                                    if ($count_invoices2 == 0) {
                                        echo $count_invoices2 = 0;
                                    } else {
                                        echo $count_invoices2 = ($count_invoices2 / $count_all) * 100;
                                    }
                                @endphp

                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
        </div>
    </div> --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-success-gradient">
            <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                <div class="">
                    <h6 class="mb-3 tx-12 text-white"> الاموال المتاحه في الخزنه</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div class="">
                            <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                {{ number_format(\App\Models\Safe::sum('money'), 2) }}
                            </h4>
                            <p class="mb-0 tx-12 text-white op-7">
                                {{-- {{ \App\Models\Invoice::where('Value_Status', 1)->count() }} --}}
                            </p>
                        </div>
                        <span class="float-right my-auto mr-auto">
                            <i class="fas fa-arrow-circle-up text-white"></i>
                            <span class="text-white op-7">
                                {{-- @php
                                    $count_all = \App\Models\Safe::count();
                                    $count_invoices1 = \App\Models\Invoice::where('Value_Status', 1)->count();
                                    
                                    if ($count_invoices1 == 0) {
                                        echo $count_invoices1 = 0;
                                    } else {
                                        echo $count_invoices1 = ($count_invoices1 / $count_all) * 100;
                                    }
                                @endphp --}}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
        </div>
    </div>
</div>
<!-- row closed -->

<!-- row -->
<div class="row row-sm">
    <p class="text-primary">سحب الاموال من الخزنه..</p>
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('transaction') }}" method="post" autocomplete="off">
                    {{ csrf_field() }}
                    {{-- 1 --}}

                    <div class="row">
                        <div class="col">
                            <label for="money" class="control-label">الاموال المراد سحبها</label>
                            <input type="number" class="form-control" id="money" name="money"
                                placeholder="يجب ان يكون ألاموال المراد سحبها اقل من المتاح في الخزنه">
                            @error('money')
                                <small class="form-txt text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <label>تاريخ السحب</label>
                            <input class="form-control fc-datepicker" name="cashWithdrawalDate" placeholder="YYYY-MM-DD"
                                type="text" value="{{ date('Y-m-d') }}" readonly>
                            @error('cashWithdrawalDate')
                                <small class="form-txt text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>

                    {{-- 2 --}}
                    <div class="row">
                        <div class="col">
                            <label for="reason" class="control-label">سبب السحب</label>
                            <select name="reason" class="form-control SlectBox" onclick="console.log($(this).val())"
                                onchange="console.log('change is firing')">
                                <!--placeholder-->
                                <option value="" selected disabled>حدد سبب السحب</option>
                                <option value="مرتبات">مرتبات</option>
                                <option value="مصاريف">مصاريف</option>
                                <option value="كهربا">كهربا</option>
                                <option value="أخري">أخري</option>

                            </select>
                            @error('reason')
                                <small class="form-txt text-danger">{{ $message }}</small>
                            @enderror
                        </div>


                    </div>


                    {{-- 3 --}}

                    {{-- <div class="row">

                        <div class="col">
                            <label for="inputName" class="control-label">مبلغ العمولة</label>
                            <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                name="Amount_Commission" title="يرجي ادخال مبلغ العمولة "
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            @error('Amount_Commission')
                                <small class="form-txt text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col">
                            <label for="inputName" class="control-label">الخصم</label>
                            <input type="text" class="form-control form-control-lg" id="Discount" name="Discount"
                                title="يرجي ادخال مبلغ الخصم "
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                value=0 required>
                            @error('Discount')
                                <small class="form-txt text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col">
                            <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                            <select name="Rate_VAT" id="Rate_VAT" class="form-control" onchange="myFunction()">
                                <!--placeholder-->
                                <option value="" selected disabled>حدد نسبة الضريبة</option>
                                <option value=" 5%">5%</option>
                                <option value="10%">10%</option>
                            </select>
                            @error('Rate_VAT')
                                <small class="form-txt text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div> --}}

                    {{-- 4 --}}

                    {{-- <div class="row">
                        <div class="col">
                            <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                            <input type="text" class="form-control" id="Value_VAT" name="Value_VAT" readonly>
                        </div>

                        <div class="col">
                            <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                            <input type="text" class="form-control" id="Total" name="Total" readonly>
                        </div>
                    </div> --}}

                    {{-- 5 --}}
                    <div class="row">
                        <div class="col">
                            <label for="exampleTextarea">ملاحظات</label>
                            <textarea class="form-control" id="exampleTextarea" name="note" rows="3"></textarea>
                        </div>
                    </div><br>

                    {{-- <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                    <h5 class="card-title">المرفقات</h5>

                    <div class="col-sm-12 col-md-12">
                        <input type="file" name="pic" class="dropify"
                            accept=".pdf,.jpg, .png, image/jpeg, image/png" data-height="70" />
                    </div><br> --}}

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
<!-- row -->
<div class="row row-sm">
    <!--div-->
    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">جدول السحب من الخزنه</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table key-buttons text-md-nowrap">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">الأموال المسحوبه</th>
                                <th class="border-bottom-0">سبب السحب</th>
                                <th class="border-bottom-0">تاريخ السحب</th>
                                <th class="border-bottom-0">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $t)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $t->money }}</td>
                                    <td>{{ $t->reason }}</td>
                                    <td>{{ $t->cashWithdrawalDate }}</td>
                                    <td>{{ $t->note }}</td>
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
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<script src="{{ URL::asset('assets/js/index.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.vmap.sampledata.js') }}"></script>
@endsection
