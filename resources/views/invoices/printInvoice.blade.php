@extends('layouts.master')
@section('css')
    <style>
        @media print {
            #print_Button {
                display: none;
            }
        }
    </style>
@endsection
@section('title')
    معاينه طباعة الفاتورة
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    معاينة طباعة الفاتورة</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            <div class=" main-content-body-invoice" id="print">
                <div class="card card-invoice">
                    <div class="card-body">
                        <div class="invoice-header">
                            <h1 class="invoice-title">فاتورة تحصيل</h1>
                            <div class="billed-from">
                                <h6>نظام فواتير</h6>
                                <p>كل الحقوق محفوظه لدي عبدالرحمن <br>
                                    Tel No: 01015496488<br>
                                    Email: abdra1396@gmail.com
                                </p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->
                        <div class="row mg-t-20">
                            <div class="col-md">
                                <label class="tx-gray-600">Billed To</label>
                                <div class="billed-to">
                                    <h6>Abdelrahman</h6>
                                    <p>Giza October-6th<br>
                                        Tel No: 324 445-4544<br>
                                        Email: abdra1396@gmail.com</p>
                                </div>
                            </div>
                            <div class="col-md">
                                <label class="tx-gray-600">معلومات الفاتورة</label>
                                <p class="invoice-info-row"><span>رقم الفاتورة</span>
                                    <span>{{ $invoice->order_id }}</span>
                                </p>
                                <p class="invoice-info-row"><span>تاريخ الاصدار</span>
                                    <span>{{ $invoice->invoice_Date }}</span>
                                </p>
                                {{-- <p class="invoice-info-row"><span>تاريخ الاستحقاق</span> --}}
                                {{-- <span>{{ $invoice->Due_date }}</span></p> --}}
                                {{-- <p class="invoice-info-row"><span>القسم</span> --}}
                                {{-- <span>{{ $invoice->section->section_name }}</span></p> --}}
                            </div>
                        </div>
                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice border text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">اسم المنتج</th>
                                        <th scope="col">قسم المنتج</th>
                                        <th scope="col">الكميه</th>
                                        <th scope="col">ثمن بيع القطعه الواحده</th>
                                        <th scope="col">الاجمالي</th>
                                        <th scope="col">تاريخ الاصدار</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach ($invoice->order->order_details as $o)
                                        <?php $i++; ?>
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $o->product->Product_name }}</td>
                                            <td>{{ $o->section }}</td>
                                            <td>{{ $o->mount }}</td>
                                            <td>{{ $o->total / $o->mount }}</td>
                                            <td>{{ $o->total }}</td>
                                            <td>{{ $o->created_at }}</td>
                                            {{-- <td colspan="2">

                                                                        <a class="btn btn-outline-success btn-sm"
                                                                            href="{{ url('showAttach/' . $invoice->invoice_number . '/' . $attachment->file_name) }}"
                                                                            role="button"><i
                                                                                class="fas fa-eye"></i>&nbsp;
                                                                            عرض</a>

                                                                        <a class="btn btn-outline-info btn-sm"
                                                                            href="{{ url('download/' . $invoice->invoice_number . '/' . $attachment->file_name) }}"
                                                                            role="button"><i
                                                                                class="fas fa-download"></i>&nbsp;
                                                                            تحميل</a>


                                                                        @can('حذف المرفق')
                                                                            <button class="btn btn-outline-danger btn-sm"
                                                                                data-toggle="modal"
                                                                                data-file_name="{{ $attachment->file_name }}"
                                                                                data-invoice_number="{{ $attachment->invoice_number }}"
                                                                                data-id_file="{{ $attachment->id }}"
                                                                                data-target="#delete_file">حذف</button>
                                                                        @endcan


                                                                    </td> --}}
                                        </tr>
                                    @endforeach

                                    {{-- <tr>
                                        <td class="valign-middle" colspan="2" rowspan="4">
                                            <div class="invoice-notes">
                                                <label class="main-content-label tx-13">#</label>

                                            </div><!-- invoice-notes -->
                                        </td>
                                        <td class="tx-right">الاجمالي</td>
                                        <td class="tx-right" colspan="2"> {{ number_format($total, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-right">نسبة الضريبة ({{ $invoice->Rate_VAT }})</td>
                                        <td class="tx-right" colspan="2">287.50</td>
                                    </tr>
                                    <tr>
                                        <td class="tx-right">قيمة الخصم</td>
                                        <td class="tx-right" colspan="2"> {{ number_format($invoice->Discount, 2) }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="tx-right tx-uppercase tx-bold tx-inverse">الاجمالي شامل الضريبة</td>
                                        <td class="tx-right" colspan="2">
                                            <h4 class="tx-primary tx-bold">{{ number_format($invoice->Total, 2) }}</h4>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                        <hr class="mg-b-40">



                        <button class="btn btn-danger  float-left mt-3 mr-2" id="print_Button" onclick="printDiv()"> <i
                                class="mdi mdi-printer ml-1"></i>طباعة</button>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>


    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>

@endsection
