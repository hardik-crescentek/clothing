@extends('layout.main') @section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section class="forms">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.print_barcode')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                    	<label><strong>{{trans('file.add_product')}} *</strong></label>
                                        <div class="search-box input-group">
                                        	
                                            <button type="button" class="btn btn-secondary btn-lg"><i class="fa fa-barcode"></i></button>
                                            <input type="text" name="product_code_name" id="lims_productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="table-responsive mt-3">
                                            <table id="myTable" class="table table-hover order-list">
                                                <thead>
                                                    <tr>
                                                        <th>{{trans('file.name')}}</th>
                                                        <th>{{trans('file.Code')}}</th>
                                                        <th>{{trans('file.Quantity')}}</th>
                                                        <th><i class="fa fa-trash"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                	<strong>{{trans('file.Print')}}: </strong>&nbsp;
                                	<strong><input type="checkbox" name="name" checked /> {{trans('file.Product Name')}}</strong>&nbsp;
                                	<strong><input type="checkbox" name="price" checked/> {{trans('file.Price')}}</strong>&nbsp;
                                	<strong><input type="checkbox" name="promo_price"/> {{trans('file.Promotional Price')}}</strong>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="print-barcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
	    <div role="document" class="modal-dialog">
	        <div class="modal-content">
		        <div class="modal-header">
		          <h5 id="modal_header" class="modal-title">{{trans('file.Barcode')}}</h5>&nbsp;&nbsp;
		          <button id="print-btn" type="button" class="btn btn-default btn-sm"><i class="fa fa-print"></i> {{trans('file.Print')}}</button>
		          <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
		        </div>
		        <div class="modal-body">
	        		<div id="label-content">
	        		</div>
		        </div>
	        </div>
	    </div>
    </div>
</section>

<script type="text/javascript">

    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #printBarcode-menu").addClass("active");
	<?php $productArray = []; ?>
	var lims_product_code = [ @foreach($lims_product_list as $product)
        <?php
            $productArray[] = $product->code . ' (' . $product->name . ')';
        ?>
         @endforeach
            <?php
            echo  '"'.implode('","', $productArray).'"';
            ?> ];

    var lims_productcodeSearch = $('#lims_productcodeSearch');

    lims_productcodeSearch.autocomplete({
    source: function(request, response) {
        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
        response($.grep(lims_product_code, function(item) {
            return matcher.test(item);
        }));
    },
    select: function(event, ui) {
        var data = ui.item.value;
        $.ajax({
            type: 'GET',
            url: 'lims_product_search',
            data: {
                data: data
            },
            success: function(data) {
                var flag = 1;
                $(".product-code").each(function() {
                    if ($(this).text() == data[1]) {
                        alert('duplicate input is not allowed!')
                        flag = 0;
                    }
                });
                $("input[name='product_code_name']").val('');
                if(flag){
                    var newRow = $('<tr data-imagedata="'+data[3]+'" data-price="'+data[2]+'" data-promo-price="'+data[4]+'">');
                    var cols = '';
                    cols += '<td>' + data[0] + '</td>';
                    cols += '<td class="product-code">' + data[1] + '</td>';
                    cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" /></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">Delete</button></td>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);
                }
            }
        });
    }
});

	//Delete product
	$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
	    rowindex = $(this).closest('tr').index();
	    $(this).closest("tr").remove();
	});

	$("#submit-button").on("click", function(event){
		var product_name = [];
		var code = [];
		var price = [];
		var promo_price = [];
		var qty = [];
		var barcode_image = [];
		var rownumber = $('table.order-list tbody tr:last').index();
		for(i = 0; i <= rownumber; i++){
			product_name.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('td:nth-child(1)').text());
			code.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('td:nth-child(2)').text());
			price.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('price'));
			promo_price.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('promo-price'));
			qty.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').find('.qty').val());
			barcode_image.push($('table.order-list tbody tr:nth-child(' + (i + 1) + ')').data('imagedata'));
		}
		var htmltext = '<table class="barcodelist" style="width: 100%" cellpadding="5px" cellspacing="10px">';
		$.each(qty, function(index){
			i = 0;
			while(i < qty[index]){
                if(i % 2 == 0)
                    htmltext +='<tr>';
				htmltext +='<td>';
				if($('input[name="name"]').is(":checked"))
					htmltext += product_name[index] + '<br>';
				htmltext += '<img src="data:image/png;base64,'+barcode_image[index]+'" alt="barcode" /><br>';
				if($('input[name="code"]').is(":checked"))
					htmltext += '<strong>'+code[index]+'</strong><br>';
				if($('input[name="promo_price"]').is(":checked"))
					htmltext += 'price: '+promo_price[index]+'<br>';
				else if($('input[name="price"]').is(":checked"))
					htmltext += 'price: '+price[index];
				htmltext +='</td>';
                if(i % 2 != 0)
                    htmltext +='</tr>';
				i++;
			}
		});
        htmltext += '</table">';
		$('#label-content').html(htmltext);
		$('#print-barcode').modal('show');
	});

	$("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('print-barcode');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<style type="text/css">@media print { #modal_header { display: none } #print-btn { display: none } #close-btn { display: none } } table.barcodelist { page-break-inside:auto } table.barcodelist tr { page-break-inside:avoid; page-break-after:auto }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

</script>
@endsection