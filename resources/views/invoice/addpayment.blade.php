@extends('layouts.master')
@section('title', 'Invoice')
@section('content')

@if ($message = Session::get('success'))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif
<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>Add Payment</h4>
            </div>
            <div class="widget-body">
                {!! Form::open(array('route' => ['invoice.save-payment'],'method'=>'post','id'=>'from_add_payment', 'class'=>"form-horizontal form-validate", 'novalidate')) !!}
                
                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Invoic No<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('invoice_no', $invoice->invoice_no, array('id' => 'invoice_no','class' => 'form-control', 'data-validation'=>"required","readonly"=>"readonly")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Date of invoice<span class="text-danger ml-2">*</span></label>
                        {!! Form::text('generate_date', null, array('id' => 'generate_date','class' => 'form-control', 'data-validation'=>"required")) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Payment Type<span class="text-danger ml-2">*</span></label>
                        {!! Form::select('payment_type',["case"=>"Case","chequee"=>"Chequee"], "case", ["class"=>"form-control payment_type","id"=>"payment_type"]) !!}
                        {!! Form::hidden("invoice_id",$invoice->id, ["class"=>"invoice_id","id"=>"invoice_id"]) !!}
                        {!! Form::hidden("payment_receiver_id",$invoice->payment_receiver_id, ["class"=>"payment_receiver_id","id"=>"payment_receiver_id"]) !!}
                    </div>
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Amount<span class="text-danger ml-2">*</span></label>
                        {!! Form::text("amount", null, ["class"=>"form-control amount","id"=>"amount"]) !!}
                        <div class="alert alert-danger p-1" style="display: none;" id="error_amount1"></div>
                        <div class="alert alert-danger p-1" style="display: none;" id="error_amount2"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-3">
                        <label class="form-control-label d-flex">Note</label>
                        {!! Form::textarea("note", null, ["class"=>"form-control","id"=>"note","rows"=>5]) !!}
                    </div>
                    <div class="form-group col-lg-3" style="display: none;" id="div_chequee_no">
                        <label class="form-control-label">Chequee Number<span class="text-danger ml-2">*</span></label>
                        {!! Form::text("chequee_no", null, ["class"=>"form-control chequee_no","id"=>"chequee_no"]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="form-control-label text-left w-100 text-dark">
                            Total Amount: <b>{{$invoice->grand_total}}</b>
                        </label>
                        <label class="form-control-label text-left w-100 text-dark" id="remaining_amount">
                            Remaining Amount: <b id="remaining_amount_value">0.00</b>
                        </label>
                    </div>
                </div>

                
                <div class="form-group row d-flex mt-2">
                    <div class="col-lg-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg" id="final_save_btn">Save</button>
                        <a class="btn btn-secondary btn-lg ml-1" href="{{ route('invoice.index') }}"> Cancel</a>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<!-- Begin Row -->
<div class="row flex-row">
    <div class="col-xl-12 col-12">
        <div class="widget has-shadow">
            <div class="widget-header bordered no-actions d-flex align-items-center">
                <h4>History Of Payment</h4>
            </div>
            <div class="widget-body">
                <div class="table-responsive">
                    <table class="table" id="historytbl">
                        <thead class="text-center">
                            <tr>
                                <th>Date</th>
                                <th>Payment Receiver Name</th>
                                <th>Payment Type </th>
                                <th>Amount</th>
                                <th>Chequee Number</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($history)
                                @if (count($history) > 0)
                                    @foreach ($history as $item)
                                    <tr class="text-center history-row">
                                        <td> {{$item->payment_date}} </td>
                                        <td> {{$item->paymentReceiver->fullName}} </td>
                                        <td> {{$item->payment_type}} </td>
                                        <td class="td-amount" data-value="{{$item->amount}}"> {{$item->amount}} </td>
                                        <td> {{$item->chequee_no ? $item->chequee_no : "-"}} </td>
                                        <td> {{$item->note}} </td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="6">No History</td>
                                    </tr>
                                @endif
                                
                            @endisset
                        </tbody>
                    </table>
                    {{ $history->render() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('assets/js/datepicker/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
    (function($) {
        var remaining_amount=0;
        $('#generate_date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $("#payment_type").on("change",function(){
            if($(this).val()=="case"){
                $('#div_chequee_no').css("display","none");
            }
            else if($(this).val()=="chequee"){
                $('#div_chequee_no').css("display","block");
            }
        });
        $('#amount').on('keyup',function(){
            if(isNaN($(this).val())){
                $('#error_amount1').css('display','block').html("Please Enter Only Digit");
                $('#final_save_btn').attr('disabled',true);
            }
            else{
                $('#error_amount1').css('display','none');
                $('#final_save_btn').attr('disabled',false);
            }
            if($(this).val()==""){
                $(this).val(0);
            }
            if($(this).val()>remaining_amount){
                $('#error_amount2').css('display','block').html("Entered Amount Is Larger Then Remaaining Amount");
                $("#remaining_amount").html("Remaining Amount : <b>"+(0).toFixed(2)+"</b>");
            }
            else{
                $('#error_amount2').css('display','none');
                $("#remaining_amount").html("Remaining Amount : <b>"+(remaining_amount-parseFloat($(this).val())).toFixed(2)+"</b>");
            }
            
        });
        $(window).on('load',function(){
            if(!$('#historytbl tbody tr').is(":empty")){
                var payable_amount=0;
                var grand_total="{{$invoice->grand_total}}"; 
                $.each($('.td-amount'),function(i,v){
                    payable_amount+=parseFloat($(v).data("value"));
                });
                remaining_amount=grand_total-payable_amount;
                if(remaining_amount==0){
                    $('#amount').attr('readonly',true);
                    $('#final_save_btn').attr('disabled',true);
                }
                $("#remaining_amount").html("Remaining Amount : <b>"+remaining_amount.toFixed(2)+"</b>");
            }
        })
        $('#final_save_btn').on('click',function(){
            if($('#payment_type').val()=="case"){
                if($('#amount').val()==""){
                    alert("Please Enter Amount");
                    return false;
                }
            }
            else if($('#payment_type').val()=="chequee"){
                if($('#chequee_no').val()=="" && $('#amount').val()==""){
                    alert("Please Enter Chequee Number And Amount");
                    return false;
                }    
                else if($('#chequee_no').val()==""){
                    alert("Please Enter Chequee Number");
                    return false;
                }
            }            
            else{
                return true;
            }
        });
    })(jQuery);
</script>
@endpush