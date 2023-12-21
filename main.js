console.log("Load main.js");
var subtable = $('#subtable').DataTable({
    "paging": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "aaSorting": []
});

var subsubtable = $('#subsubtable').DataTable({
    "paging": true,
    "searching": true,
    "ordering": true,
    "info": true,
    "aaSorting": []
});

function removeCurrencyFormat(form) {
    var currencyInput = form.find(".input-icon input");
    currencyInput.each(function(index) {
        var currencyFormat = $(this).val();
        numberFormat = currencyFormat.replace(/\./g, '').replace(/\,/g, '');
        console.log("change format to number", currencyFormat, numberFormat);
        $(this).val(numberFormat);
    });
}
var data_particant = [];
// Auto currency format on input
$(function() {

    $(document).on("keyup currency-change", ".input-icon input", function(event) {
        console.log("trigger reformat currency", this.selectionStart, window.getSelection().toString(), event.type);
        // When user select text in the document, also abort.
        var selection = window.getSelection().toString();

        if (selection !== '' && event.type != "currency-change") {
            return;
        }

        // When the arrow keys are pressed, abort.
        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1 && event.type != "currency-change") {
            return;
        }


        var $this = $(this);
        var position = this.selectionStart;
        // Get the value.
        var input = $this.val();

        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt(input, 10) : 0;

        $this.val(function() {
            return (input === 0) ? "" : input.toLocaleString("en-US");
        });
        console.log("after change value", $this.val());
        // if (position != null)
        //     this.setSelectionRange(position, position);
    });
});

try {
    document.querySelector('#order').addEventListener('input', function(e) {
        var input = e.target,   
            list = input.getAttribute('list'),
            options = document.querySelectorAll('#' + list + ' option[value="'+input.value+'"]'),
            hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');
    
        if (options.length > 0) {
          hiddenInput.value = input.value;
          input.value = options[0].innerText;
          }
    
    });
} catch (error) {
}

$('body').on('change', '[name="discount"]', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    var priceCur = $(modal+' input[name=price]').val();
    var price = priceCur.replace(/\./g, '').replace(/\,/g, '');
    var quantity = $(modal+' input[name=quantity]').val();
    var discount = $(modal+' input[name=discount]').val();
    var qty = quantity > 0 ? quantity : 1;
    var total = (price * qty) - discount;
    $(modal+' input[name=total]').val(total);
    if ($(modal+' input[name=total]').closest(".input-icon")) {
        $(modal+' input[name=total]').trigger("currency-change");
    }
    console.log("price from discount", price);
    console.log("priceCur from discount", priceCur);
    console.log("total from discount", total);
});

$('body').on('change', '[name="price"]', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    var priceCur = $(modal+' input[name=price]').val();
    var price = priceCur.replace(/\./g, '').replace(/\,/g, '');
    var quantity = $(modal+' input[name=quantity]').val();
    var discount = $(modal+' input[name=discount]').val();
    if(discount > 0){
        var qty = quantity > 0 ? quantity : 1;
        var total = (price * qty) - discount;
        $(modal+' input[name=total]').val(total);
        if ($(modal+' input[name=total]').closest(".input-icon")) {
            $(modal+' input[name=total]').trigger("currency-change");
        }
    }
});

$('body').on('change', '[name="product_type"]', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    changeMode($(this).val(), modal);
});

$('body').on('change', '.private_trip_id', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    changeCustom($(this).val(), modal);
});

$('body').on('change', '.open_trip_id', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    calculatePrice(modal);
});

$('body').on('click', '.period-delete-icon', function(event) {
    $(this).closest(".period-row").remove();
});

$('body').on('click', '.period-add-new', function(event) {
    if ($(this).closest(".modal").attr("id") == "create-order") {
        $(this).closest(".modal").find(".period-field").append(
            '<div class="col-sm-12 nopadding period-row"> \
                <div class="col-sm-3 period-margin"><input type="text" name="period[period_json][label][]" class="form-control" placeholder="" value="" /></div> \
                <div class="col-sm-3 period-margin"><input type="text" name="period[period_json][duedate][]" class="form-control datepick" placeholder="" value="" /></div> \
                <div class="col-sm-4 period-margin">\
                    <div class="input-icon input-icon-left">\
                      <i>IDR</i>\
                          <input type="text" name="period[period_json][price][]" class="form-control" placeholder="0.0" value="" />\
                    </div>\
                </div> \
                <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div> \
            </div>'
        );
    }
    else {
        $(this).closest(".modal").find(".period-field").append(
            '<div class="col-sm-12 nopadding period-row"> \
                <div class="col-sm-3 period-margin"><input type="text" name="label[]" class="form-control" placeholder="" value="" /></div> \
                <div class="col-sm-3 period-margin"><input type="text" name="duedate[]" class="form-control datepick" placeholder="" value="" /></div> \
                <div class="col-sm-4 period-margin">\
                    <div class="input-icon input-icon-left">\
                      <i>IDR</i>\
                          <input type="text" name="price[]" class="form-control" placeholder="0.0" value="" />\
                    </div>\
                </div> \
                <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div> \
            </div>'
        );
    }
    $('.datepick').datepicker();
});

$('body').on('change', '.age_change', function(event) {
    var section = $(this).closest(".section");
    var ele = $(this).closest(".section").find(".field-price");
    calculatePrice(section, ele);
});


$('body').on('change', '.open_trip_product', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    console.log("trigger get price data from open_trip_product");
    getPriceData($(modal + ' [name="order[product_id]"]').val(), "open_trip", null);
});

$('body').on('change', '.custom-price', function(event) {
    var modal = "#" + $(this).closest(".modal").attr("id");
    $(modal + " .private-price").val($(this).val());
});

function calculatePrice(section, ele) {
    price = 0;
    if (section.attr("id") == "private-trip-section" && section.find(".private_trip_id").val() == "custom") {
        section.find(".form-age").each(function() {
            console.log(section.find('input[data-id="' + $(this).attr('data-group-id') + '"]'), $(this).val());
            price += parseInt(section.find('input[data-id="' + $(this).attr('data-group-id') + '"]').val()) * $(this).val().replace(/\./g, '').replace(/\,/g, '');
        });
        console.log("go to custom section")
    }
    else {
        for (index = 0; index < priceData.length; index++) {
            console.log(section.find('input[data-id="' + priceData[index].age_group_id + '"]'), section.find('input[data-id="' + priceData[index].age_group_id + '"]').val(), priceData[index].product_price)
            price += parseInt(section.find('input[data-id="' + priceData[index].age_group_id + '"]').val()) * priceData[index].product_price;
        }
    }
    console.log(ele, "last price", price);
    $(ele).val(price);
    setTimeout(function() {
        $(ele).trigger("currency-change")
    }, 250);
}

function changeCustom(selection, modal) {
    if (selection == "custom") {
        $(modal + " .custom-package").slideDown();
        $(modal + " .custom-package").find("input,select,textarea").prop("disabled", false);
    }
    else {
        $(modal + " .custom-package").slideUp();
        $(modal + " .custom-package").find("input,select,textarea").prop("disabled", true);
        console.log("private data", $(modal + " .private_trip_id"));
        getPriceData(selection, "private_trip", $(modal + " .private-price"));
    }
}

function changeMode(selection, modal) {
    console.log("trigger7");
    if (selection == "open_trip") {
        $(modal + " .private-trip").slideUp();
        $(modal + " .private-trip").find("input,select,textarea").prop("disabled", true);
        $(modal + " .open-trip").slideDown();
        $(modal + " .open-trip").find("input,select,textarea").prop("disabled", false);
        console.log("trigger from open_trip7");
        getPriceData($(modal + ' [name="order[product_id]"]').val(), "open_trip", null);
    }
    else {
        $(modal + " .open-trip").slideUp();
        $(modal + " .open-trip").find("input,select,textarea").prop("disabled", true);
        $(modal + " .private-trip").slideDown();
        $(modal + " .private-trip").find("input,select,textarea").prop("disabled", false);
        console.log("trigger custom");
        changeCustom($(modal + ' .private_trip_id').val(), modal);
    }
}

var lastViewBtn = null;
$('#participant-list.modal').on('show.bs.modal', function(event) {
    var viewBtn = lastViewBtn = $(event.relatedTarget);
    var modal = $(this);
    getParticipantData(viewBtn, modal);
});
var priceData = [];

function getPriceData(id, type, priceEle) {
    console.log("execute get price data", id, type, priceEle);
    $.ajax({
        url: mainUrl + "dashboard/v2/Get_Product_Price",
        type: "POST",
        data: "id=" + id + "&type=" + type,
        success: function(data) {
            var res = parseJSON(data);
            if (type == "private_trip" && res != null) {
                console.log("trigger private", res, res.price);
                // priceEle.val(res.price).trigger("currency-change");
                priceData = res;
            }
            else if (type == "open_trip" && res != null) {
                console.log("data", res);
                priceData = res;
            }
        },
        error: function(err) {
            showError(err);
        }
    });
}



function getParticipantData(viewBtn, modal) {
    $.ajax({
        url: viewBtn.attr("data-action"),
        type: viewBtn.attr("data-method"),
        data: viewBtn.attr("data-form"),
        success: function(data) {
            var res = parseJSON(data);
            console.log("Participant", res);
            
            console.log("ok");
            // $("#subtable tbody").html("");
            subtable.clear().draw();
            for (index = 0; index < res.length; index++) {
                participant = res[index];
                var btn = 
                '<button order_id="'+participant.order_id+'" class="btn btn-danger participant-delete-btn btn-sm">Delete</button>'+
                '<button class="btn btn-primary participant-invoice btn-sm" data-method="POST" data-action="' + base_url + 'dashboard/v2/Participant_Invoice" data-form="order_id=' + participant.order_id + '">Payment</button>';
                if(participant.order_leader=="0") btn= '<button order_id="'+participant.order_id+'" class="btn btn-danger participant-delete-btn btn-sm">Delete</button>';
                
                subtable.row.add([
                    REMOVE_NULL(participant.order_name),
                    REMOVE_NULL(participant.order_phone),
                    REMOVE_NULL(participant.order_line_id),
                    REMOVE_NULL(participant.order_email),
                    REMOVE_NULL(participant.order_start_date) + " - " + REMOVE_NULL(participant.order_end_date),
                    REMOVE_NULL(participant.voucher_code),
                    REMOVE_NULL(participant.order_note),
                    REMOVE_NULL(CONVERT_TO_CURRENCY(participant.order_price)),
                    CONVERT_TO_CURRENCY(participant.total_payment) + "/" + CONVERT_TO_CURRENCY(participant.total_invoice),
                    btn
                        
                ]).draw();
            }
        },
        error: function(err) {
            showError(err);
        }
    });
}

var lastInvoiceBtn = null;
$(document).on('click', '.participant-delete-btn', function(e) {
    var id = $(this).attr("order_id");
    console.log(id);
    var form = new FormData();
    form.append("order_id",id);
    var row =  $(this);
    $.ajax({
        url : base_url + 'dashboard/v2/Participant_delete',
        method:"post",
        data:form,
        processData: false,
        contentType: false,
        success:function(e){
            console.log('#trip-'+idxDetail);
            row.parent().parent().hide();
            var jumlah = parseInt($('#trip-'+idxDetail).html());
            console.log("jumlah : "+$('#trip-'+idxDetail).html())
            jumlah--;
            
            $('#trip-'+idxDetail).html(jumlah);
        },
        error:function(e){
            console.log(e.responseText)
        }
    })
});
$(document).on('click', '.participant-update-btn', function(e) {
    var id = $(this).attr("order_id");
    console.log(id);
    var form = new FormData();
    form.append("order_id",id);
    var row =  $(this);
    $.ajax({
        url : base_url + 'dashboard/v2/Participant_detail',
        method:"post",
        data:form,
        processData: false,
        contentType: false,
        success:function(e){
            console.log('#trip-'+idxDetail);
            row.parent().parent().hide();
            var jumlah = parseInt($('#trip-'+idxDetail).html());
            console.log("jumlah : "+$('#trip-'+idxDetail).html())
            jumlah--;
            
            $('#trip-'+idxDetail).html(jumlah);
        },
        error:function(e){
            console.log(e.responseText)
        }
    })
});
$('body').on('click', '.participant-invoice', function(e) {
    $('#payment-list').modal('show');
    form_btn = lastInvoiceBtn = $(this);
    getParticipantInvoices(form_btn);
    e.preventDefault();
});

function getParticipantInvoices(form_btn) {

    $.ajax({
        url: $(form_btn).attr("data-action"),
        type: $(form_btn).attr("data-method"),
        data: $(form_btn).attr("data-form"),
        success: function(data) {

            var res = parseJSON(data);
            console.log("Result ", res);
            subsubtable.clear().draw();
            for (index = 0; index < res.length; index++) {
                invoice = res[index];
                paymentBtn = '';
                if (invoice.status == 0) {
                    invoice.status = "Unpaid";
                    paymentBtn = '<button class="btn btn-primary add-payment" index="'+index+'" data-method="POST" data-action="' + base_url + 'dashboard/v2/Add_Payment" invoice_id="' + invoice.id + '" data-form="{invoice_id:' + invoice.id + '}">Add Payment</button>';
                }
                else {
                    invoice.status = "Paid";
                    paymentBtn = '';
                }
                subsubtable.row.add([
                    "<a target='_blank' href='" + base_url + "dashboard/v2/Invoice_Management?invoice_id=" + REMOVE_NULL(invoice.id) + "'>" + REMOVE_NULL(invoice.id) + "</a>",
                    REMOVE_NULL(invoice.title),
                    REMOVE_NULL(invoice.description),
                    REMOVE_NULL(invoice.quantity),
                    REMOVE_NULL(CONVERT_TO_CURRENCY(invoice.price)),
                    REMOVE_NULL(CONVERT_TO_CURRENCY(invoice.total)),
                    REMOVE_NULL(invoice.status),
                    REMOVE_NULL(invoice.due_date),
                    paymentBtn
                ]).draw();
            }
            // $('##create-bed-type').modal('hide');

        },
        error: function(err) {
            showError(err);
        }
    });
}
var idxDetail = -1;
$('.detail-btn').click(function(){
    console.log("masuk index");
    idxDetail = $(this).attr("index");
})


// $('body').on('click','.download-invoice', function(e) {
//     form_btn = $(this);
//                 // When click Submit Button
//     $.ajax({
//         url: $(form_btn).attr("data-action"),
//         type: $(form_btn).attr("data-method"),
//         data: $(form_btn).attr("data-form"),
//         success: function (data) {
//
//             var res = parseJSON(data);
//             if ( form_btn.attr("data-refresh") == "true" ) {
//                 process_result(res, "location.reload()");
//             }
//             else {
//                 process_result(res, "getParticipantData(lastViewBtn,$('#participant-list.modal')); getParticipantInvoices(lastInvoiceBtn);");
//             }
// 			// $('##create-bed-type').modal('hide');
//         },
//         error: function (err) {
//             showError(err);
//         }
//     });
//
//
//     e.preventDefault();
// });

$('body').on('click', '.add-payment', function(e) {
    form_btn = $(this);
    console.log($(form_btn).attr("invoice_id"));
    show_message_confirm('Add Payment', 'Are you sure? You are about to add payment',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: $(form_btn).attr("data-action"),
                    type: $(form_btn).attr("data-method"),
                    data: {"invoice_id":$(form_btn).attr("invoice_id")},
                    success: function(data) {
                        var res = parseJSON(data);
                        if (form_btn.attr("data-refresh") == "true") {
                            process_result(res, "location.reload()");
                        }
                        else {
                            process_result(res, "getParticipantData(lastViewBtn,$('#participant-list.modal')); getParticipantInvoices(lastInvoiceBtn);");
                        }
                        // $('##create-bed-type').modal('hide');
                    },
                    error: function(err) {
                        console.log(err);
                        showError(err);
                    }
                });
            }
            else {
                // When click Cancel button
            }
        }
    );
    e.preventDefault();
});
var typeTrigger = false;
$('#create-order.modal').on('show.bs.modal', function(event) {
    console.log($(this).css("display"));
    if (typeTrigger == false && $(this).css("display") == "none") {
        typeTrigger = true;
        changeMode("open_trip", "#" + $(this).attr("id"));
        console.log("work 3!", $(this).attr("id"));
        setTimeout(function(e) {
            typeTrigger = false
        }, 1500)
    }
});

$('#create-order.modal').on('hide.bs.modal', function(event) {});



$('#update-order.modal').on('show.bs.modal', function(event) {
    console.log("Trigger2");
    if (typeTrigger == false) {
        typeTrigger = true;
        var editBtn = $(event.relatedTarget);
        var modal = $(this);
        if (editBtn.attr("data-json") == null) {
            $.ajax({
                url: editBtn.closest("table").attr("data-detail-url"),
                type: "POST",
                data: editBtn.attr("data-form-detail"),
                success: function(data) {
                    var res = parseJSON(data);
                },
                error: function(err) {
                    showError(err);
                }
            });
        }
        else {
            var res = parseJSON(editBtn.attr("data-json"));
            res["order_detail"] = parseJSON(res["order_detail"]);
            console.log("Work", res["order_detail"]);
            for (ele in res) {
                if (ele == "product_id") {
                    if (res[ele] != null) {
                        modal.find('[name="product_type"]').val("open_trip");
                        modal.find('[name="order[' + ele + ']"]').val(res[ele]);
                        console.log("trigger change open trip id to", res[ele], "after change value is", modal.find('[name="order[' + ele + ']"]').val());
                        changeMode("open_trip", "#" + $(this).attr("id"));
                    }
                }
                else if (ele == "private_id") {
                    if (res[ele] != null) {
                        modal.find('[name="product_type"]').val("private_trip");
                        modal.find('[name="private_trip[id]"]').val(res[ele]);
                        console.log("trigger change private trip id to", res[ele], "after change value is", modal.find('[name="private_trip[id]"]').val());
                        changeMode("private_trip", "#" + $(this).attr("id"));
                    }
                }
                else if (ele == "order_detail") {
                    orderDetail = res[ele];
                    for (ageIndex = 0; ageIndex < orderDetail.length; ageIndex++) {
                        modal.find('[name="age[' + orderDetail[ageIndex]["age_group_id"] + '][val]"]').val(orderDetail[ageIndex]["order_detail_quantity"]);
                        modal.find('[name="age[' + orderDetail[ageIndex]["age_group_id"] + '][id]"]').val(orderDetail[ageIndex]["order_detail_id"]);
                    }
                }
                else {
                    console.log("Data Ele", ele, res[ele]);
                    modal.find('[name="order[' + ele + ']"]').val(res[ele]);
                    if (modal.find('[name="order[' + ele + ']"]').closest(".input-icon")) {
                        modal.find('[name="order[' + ele + ']"]').trigger("currency-change");
                    }
                }
            }
        }
        setTimeout(function(e) {
            typeTrigger = false
        }, 1500);
    }
});

$('#form-order-create').on('submit', function(e) {
    console.log("Trigger before submit");
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    spinnerShow(modalInput);
    show_message_confirm('Create Order', 'Are you sure? You are about to create an order',
        function(event, e) {
            console.log("run!");
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#create-order').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('#form-order-update').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    spinnerShow(modalInput);
    show_message_confirm('Edit Order', 'Are you sure? You are about to edit an order',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#update-order').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
});

$('.btn-order-remove').on('click', function(e) {
    form_btn = $(this);
    show_message_confirm('Delete Order', 'Are you sure? You are about to delete an order',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: $(form_btn).attr("data-action"),
                    type: $(form_btn).attr("data-method"),
                    data: $(form_btn).attr("data-form"),
                    success: function(data) {

                        var res = parseJSON(data);

                        process_result(res, "location.reload()");
                        // $('##create-bed-type').modal('hide');

                    },
                    error: function(err) {
                        showError(err);
                    }
                });
            }
            else {
                // When click Cancel button
            }
        }
    );
    e.preventDefault();
});

$('#update-private-trip.modal').on('show.bs.modal', function(event) {
    console.log("Trigger3");
    var editBtn = $(event.relatedTarget);
    var modal = $(this);
    if (editBtn.attr("data-json") == null) {
        $.ajax({
            url: editBtn.closest("table").attr("data-detail-url"),
            type: "POST",
            data: editBtn.attr("data-form-detail"),
            success: function(data) {
                var res = parseJSON(data);
            },
            error: function(err) {
                showError(err);
            }
        });
    }
    else {
        var res = parseJSON(editBtn.attr("data-json"));
        for (ele in res) {
            console.log("Data Ele", ele, res[ele]);
            if (ele == "period_json" && res[ele]) {
                modal.find(".period-row").remove();
                period_data = JSON.parse(res[ele]);
                for (index = 0; index < period_data.length; index++) {
                    eachData = period_data[index];
                    modal.find(".period-field").append(
                        '<div class="col-sm-12 nopadding period-row"> \
                            <div class="col-sm-3 period-margin"><input type="text" name="label[]" class="form-control" placeholder="" value="' + eachData.label + '" /></div> \
                            <div class="col-sm-3 period-margin"><input type="text" name="duedate[]" class="form-control datepick" placeholder="" value="' + eachData.duedate + '" /></div> \
                            <div class="col-sm-4 period-margin">    \
                            <div class="input-icon input-icon-left"> \
                                <i>IDR</i> \
                                <input type="text" name="price[]" class="form-control" placeholder="" value="' + CONVERT_TO_CURRENCY(eachData.price, false) + '" /> \
                            </div> \
                            </div> \
                            <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div> \
                        </div>'
                    );
                }
            } 
            
            if(ele == "period_json" && res[ele] === null){
                modal.find(".period-row").remove();
                modal.find(".period-field").append(
                    '<div class="col-sm-12 nopadding period-row"> \
                        <div class="col-sm-3 period-margin"><input type="text" name="label[]" class="form-control" placeholder="" value="" /></div> \
                        <div class="col-sm-3 period-margin"><input type="text" name="duedate[]" class="form-control datepick" placeholder="" value="" /></div> \
                        <div class="col-sm-4 period-margin">    \
                        <div class="input-icon input-icon-left"> \
                            <i>IDR</i> \
                            <input type="text" name="price[]" class="form-control" placeholder="" value="" /> \
                        </div> \
                        </div> \
                        <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div> \
                    </div>'
                );
            }

            if (ele == "period_id") {
                modal.find('[name="period[' + ele + ']"]').val(res[ele]);
            }
            if (ele == "order_date_start") {
                modal.find('[name="period[' + ele + ']"]').val(res[ele]);
            }
            if (ele == "order_date_end") {
                modal.find('[name="period[' + ele + ']"]').val(res[ele]);
            }
            if (ele == "down_payment") {
                modal.find('[name="period[' + ele + ']"]').val(res[ele]);
            }
            if (ele == "age_price") {
                var age_price = res[ele];
                for (index = 0; index < age_price.length; index++) {
                    modal.find('[name="age[' + age_price[index][1] + ']"]').val(age_price[index][2]).trigger("currency-change");
                }
            }
            else {
                modal.find('[name="' + ele + '"]').val(res[ele]);
            }
        }
    }
});

$('#form-private-trip-create').on('submit', function(e) {
    console.log("Trigger before submit");
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    spinnerShow(modalInput);
    show_message_confirm('Create Private Trip', 'Are you sure? You are about to create a private trip',
        function(event, e) {
            console.log("run!");
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#create-private-trip').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('#form-private-trip-update').on('submit', function(e) {
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    spinnerShow(modalInput);
    show_message_confirm('Edit Private Trip', 'Are you sure? You are about to edit a private trip',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#update-period').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('.btn-private-trip-remove').on('click', function(e) {
    form_btn = $(this);
    show_message_confirm('Delete Private Trip', 'Are you sure? You are about to delete a private trip ',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: $(form_btn).attr("data-action"),
                    type: $(form_btn).attr("data-method"),
                    data: $(form_btn).attr("data-form"),
                    success: function(data) {

                        var res = parseJSON(data);

                        process_result(res, "location.reload()");
                        // $('##create-bed-type').modal('hide');

                    },
                    error: function(err) {
                        showError(err);
                    }
                });
            }
            else {
                // When click Cancel button
            }
        }
    );
    e.preventDefault();
});

$('#update-period.modal').on('show.bs.modal', function(event) {
    console.log("Trigger5");
    var editBtn = $(event.relatedTarget);
    var modal = $(this);
    if (editBtn.attr("data-json") == null) {
        $.ajax({
            url: editBtn.closest("table").attr("data-detail-url"),
            type: "POST",
            data: editBtn.attr("data-form-detail"),
            success: function(data) {
                var res = parseJSON(data);
            },
            error: function(err) {
                showError(err);
            }
        });
    }
    else {
        var res = parseJSON(editBtn.attr("data-json"));
        console.log(res);
        for (ele in res) {
           
            if (ele == "period_json") {
                modal.find(".period-row").remove();
                period_data = JSON.parse(res[ele]);
                if(period_data.length>0){
                    if(period_data[0].duedate){
                        $('#interval').val(0).trigger("change")
                    }
                    else {
                        $('#interval').val(1).trigger("change")
                    };
                    toggleEnable($('#interval').val());
                }
                console.log(period_data);
                for (index = 0; index < period_data.length; index++) {
                    eachData = period_data[index];
                    
                    var due="";
                    var dy = "";
                    var type;
                    if(eachData.duedate){due=eachData.duedate;type=0};
                    if(eachData.days){dy=eachData.days;type=1};
                    modal.find(".period-field").append(
                        `<div class="col-sm-12 nopadding period-row"> 
                            <div class="col-sm-2 period-margin"><input type="text" name="label[]" class="form-control" placeholder="" value="${eachData.label}" /></div> 
                            <div class="col-sm-2 period-margin"><input type="text" name="duedate[]" class="form-control datepick due-date" placeholder="mm/dd/yyyy"  value="${due}" /></div> 
                            <div class="col-sm-2 period-margin"><input type="number" name="days[]" class="form-control days-input" min="0" datepick" placeholder="Days" value="${dy}" /></div> 
                            <div class="col-sm-2 period-margin">    
                                <div class="input-icon input-icon-left"> 
                                    <i>IDR</i> 
                                    <input type="text" name="price[]" class="form-control" placeholder="" value="${CONVERT_TO_CURRENCY(eachData.price, false)}" /> 
                                </div> 
                            </div> 
                            <div class="col-sm-2 period-margin"> 
                            <select name="interval[]" id="interval-${index}" class="form-control" value="${type}">
                                <option value="0" selected>Due Date</option>
                                <option value="1">Days</option>
                            </select></div>
                            <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div> 
                        </div>`
                    );
                    $(`#interval-${index}`).val(type).trigger("change");
                }
            }
            else {
                modal.find('[name="' + ele + '"]').val(res[ele]);
            }
        }
    }
});
$('#interval').change(function(){
    toggleEnable($(this).val());
})
function toggleEnable(val) {
    if(val==0){
        $('.due-date').removeAttr("disabled");
        $('.days-input').attr("disabled",true);
    }
    else{
        $('.days-input').removeAttr("disabled");
        $('.due-date').attr("disabled",true);
    }
}
$('#form-period-create').on('submit', function(e) {
    console.log("Trigger before submit");
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    spinnerShow(modalInput);
    show_message_confirm('Create Period', 'Are you sure? You are about to create a period',
        function(event, e) {
            console.log("run!");
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#create-period').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('#form-period-update').on('submit', function(e) {
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    spinnerShow(modalInput);
    show_message_confirm('Edit Period', 'Are you sure? You are about to edit a period',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#update-period').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('.btn-period-remove').on('click', function(e) {
    form_btn = $(this);
    show_message_confirm('Delete Period', 'Are you sure? You are about to delete a period',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: $(form_btn).attr("data-action"),
                    type: $(form_btn).attr("data-method"),
                    data: $(form_btn).attr("data-form"),
                    success: function(data) {

                        var res = parseJSON(data);

                        process_result(res, "location.reload()");
                        // $('##create-bed-type').modal('hide');

                    },
                    error: function(err) {
                        showError(err);
                    }
                });
            }
            else {
                // When click Cancel button
            }
        }
    );
    e.preventDefault();
});

$('#update-invoice.modal').on('show.bs.modal', function(event) {
    console.log("Trigger6");
    var editBtn = $(event.relatedTarget);
    var modal = $(this);
    if (editBtn.attr("data-json") == null) {
        $.ajax({
            url: editBtn.closest("table").attr("data-detail-url"),
            type: "POST",
            data: editBtn.attr("data-form-detail"),
            success: function(data) {
                var res = parseJSON(data);

            },
            error: function(err) {
                showError(err);
            }
        });
    }
    else {
        var res = parseJSON(editBtn.attr("data-json"));
        for (ele in res) {
            console.log("Data Ele", ele, res[ele]);
            modal.find('[name="' + ele + '"]').val(res[ele]);
            if (modal.find('[name="' + ele + '"]').closest(".input-icon")) {
                modal.find('[name="' + ele + '"]').trigger('currency-change');
            }
        }
    }
});

$('#form-invoice-create').on('submit', function(e) {
    console.log("Trigger before submit");
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');

    console.log("Trigger invoice create");
    spinnerShow(modalInput);
    show_message_confirm('Create Invoice', 'Are you sure? You are about to create an invoice',
        function(event, e) {
            console.log("run!");
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#create-invoice').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('#form-invoice-update').on('submit', function(e) {
    var form = $(this);
    removeCurrencyFormat(form);
    var formData = form.serialize();

    var modalInput = $(e.currentTarget).find('input[type=submit]');
    console.log("Trigger invoice create");
    spinnerShow(modalInput);
    show_message_confirm('Edit Invoice', 'Are you sure? You are about to edit an invoice',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function(data) {
                        var res = parseJSON(data);

                        process_result(res, "$('#update-invoice').modal('hide'); location.reload()", form[0]);
                        // $('##create-bed-type').modal('hide');

                        spinnerHide(modalInput);

                    },
                    error: function(err) {
                        showError(err);
                        spinnerHide(modalInput);
                    }
                });
            }
            else {
                // When click Cancel button
                spinnerHide(modalInput);
            }
        }
    );
    e.preventDefault();
});

$('.btn-invoice-remove').on('click', function(e) {
    form_btn = $(this);
    show_message_confirm('Delete Invoice', 'Are you sure? You are about to delete an invoice',
        function(event, e) {
            if ($(event.target).attr('name') == 'submit') {
                // When click Submit Button
                $.ajax({
                    url: $(form_btn).attr("data-action"),
                    type: $(form_btn).attr("data-method"),
                    data: $(form_btn).attr("data-form"),
                    success: function(data) {

                        var res = parseJSON(data);

                        process_result(res, "location.reload()");
                        // $('##create-bed-type').modal('hide');

                    },
                    error: function(err) {
                        showError(err);
                    }
                });
            }
            else {
                // When click Cancel button
            }
        }
    );
    e.preventDefault();
});

function process_result(result, react = null, reset_form = null) {
    // console.log("result json", result);
    if (result.status > 0) {
        title = "Success!";
        if (result.title != null && result.title != "") {
            title = result.title;
        }
        show_message_2(title, result.message, react);
        if (reset_form != null)
            reset_form.reset();
    }
    else {
        if (result.error_code == "E1002") {
            // console.log("working! E1002");
            $("#session-expired").modal("show");
        }
        else {
            title = "Fail!";
            if (result.title != null && result.title != "") {
                title = result.title;
            }
            show_message(title, result.message);

        }
    }
}

function show_message(title, message, react = null) {
    // react = " function() { " + react + " }";
    $("#message-title").text(title);
    $("#message-result").html(message);
    $("#message-button").attr("onclick", react);
    $("#message-modal").modal("show");
}

function show_message_2(title, message, react) {
    $("#message-title-confirmed").text(title);
    $("#message-result-confirmed").html(message);
    $('#message-confirmed').modal('show');
    setTimeout(function(e) {
        $('#message-confirmed').modal('hide');
    }, 2000);
    setTimeout(react, 2000);
}

var execEvent;
var closeEvent = function(e) {
    $("#message-confirm").modal("hide");
};
// function show_message_confirm(title, message, targetIdentifier, passEvent)
function show_message_confirm(title, message, runEvent) {

    $("#message-title-confirm").text(title);
    $("#message-result-confirm").text(message);
    // $("#message-button-confirm").attr('for', targetIdentifier);
    $('#message-confirm').modal('show');

    if (execEvent != null) {
        $('#message-button-confirm, #message-button-cancel').unbind('click', execEvent);
        $('#message-button-confirm').unbind('click', closeEvent);
    }
    execEvent = runEvent;
    $('#message-button-confirm, #message-button-cancel').bind('click', execEvent);
    $('#message-button-confirm').bind('click', closeEvent);

}

function spinnerHide(targetSubmit, submitValue = "Save") {
    targetSubmit.removeClass('spinner');
    targetSubmit.prop('disabled', false);
    targetSubmit.val(submitValue);
}

function spinnerShow(targetSubmit, submitValue = "") {
    targetSubmit.addClass('spinner');
    targetSubmit.prop('disabled', true);
    targetSubmit.val('');
}

function parseJSON(rawData) {
    try {
        json = JSON.parse(rawData);
        return json;
    }
    catch (err) {
        errorJson = {
            "status": 0,
            "type": 1,
            "title": "System Error",
            "message": "Please contact your developer to solve this problem.. <br>Error message: \"" + err + "\""
        }
        return errorJson;
    }
}

function showError(err = null) {
    errorJson = {
        "status": 0,
        "type": 1,
        "title": "System Error",
        "message": "Please contact your developer to solve this problem.. <br>Error message: \"" + err.statusText + "\" <br><br>" + err.responseText
    }
    console.log(err);
    process_result(errorJson);
}

function REMOVE_NULL(str) {
    if (str == null) {
        return "";
    }
    else {
        return str;
    }
}

function CONVERT_TO_CURRENCY(str, curr = true) {
    if (curr) {
        if (str != null) {
            return "IDR " + str.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
        else {
            return "IDR 0";
        }
    }
    else {
        if (str != null) {
            return "" + str.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
        }
        else {
            return "0";
        }
    }
}

$('#message-button').on('click', function(event) {
    console.log("work!");
    $('#message-modal').modal('hide');
    event.preventDefault();
    // $('#message-confirmed').modal('show');
})
function newForm() {
    var age = JSON.parse($('#productPrices').val());
    console.log(age);
    var title = "Data Pemesanan";
    var deskripsi = "";
    if($('#form-pemesanan .panel-default').size()<=0){
      title += " Leader"
      deskripsi += "Rincian di bawah ini wajib diisi lengkap untuk proses pemesanan, pengirim E-tiket, dan informasi lainnya. "
    }
    else{
      title += " #"+($('#form-pemesanan .panel-default').size()+1)
    }
    var element=`
          <div class="panel panel-default" >
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse-${$('#form-pemesanan .panel-default').size()}" >
            <div class="panel-heading">
              <h1 class="panel-title" style="padding:2%;font-size:16pt">
               
               <b>${title}</b>
              </h1>
            </div></a>
            <div id="collapse-${$('#form-pemesanan .panel-default').size()}" class="panel-collapse collapse in" style="padding:5%;padding-top:2%">
              <p>${deskripsi}</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullname">Nama Lengkap (Sesuai Paspor)</label>
                            <input type="text"  header_id="collapse-${$('#form-pemesanan .panel-default').size()}" labels="nama" autocomplete="off" class="form-control" id="fullname" name="fullname[]"
                                  placeholder="Ex: Amriyanto Wilinata"
                                  value=""
                            >
                            <label class="error-msg error-msg-nama"></label>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon / Whatsapp</label>
                            <input type="text" labels="nomer" header_id="collapse-${$('#form-pemesanan .panel-default').size()}"  autocomplete="off" class="form-control" id="phone" name="phone[]"
                                  placeholder="Ex: 08781234553"
                                  value=""
                            >
                            <label class="error-msg error-msg-nomer"></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                       ` 
                       if($('#form-pemesanan .panel-default').size()<=0){
                        element+=`<div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" labels="email" header_id="collapse-${$('#form-pemesanan .panel-default').size()}"  autocomplete="off" class="form-control" id="email" name="email[]"
                                placeholder="Ex: ampal@email.com"
                                value=""
                            >
                            <label class="error-msg error-msg-email"></label>
                        </div>`
                       }
                      
                        element+= `<p class="small-text" style="margin-bottom: 10px"><sup>&#42</sup>Mohon pastikan alamat email serta data yang anda masukan benar.
                            E-Voucher dan informasi lainnya akan dikirimkan ke alamat email tersebut</p>
                        <div class="form-group">
                            <label for="email">Package</label>
                            <select class="form-control" name="package" id="package[]">`
                            age.forEach(item => {
                                element+=` 
                                    <option value="${item.age_group_id}">${item.age_group_name}</option>
                                `
                            });
                            
                        element+=`</select></div>
                        <p class="small-text"><sup>&#42</sup>Mohon pastikan sudah menambahkan jumlah peserta dan package yang sesuai pada bagian kiri</p>
                    </div>
                  </div>
              </div>
            </div>
          </div>
  `
    $('#form-pemesanan').append(element);
  }
  function DeleteForm() {
    console.log($('#form-pemesanan .panel-default').size());  
    $('.panel-default')[$('#form-pemesanan .panel-default').size()-1].remove();
  }
  $('#peponiForm').submit(function(){
    var error = 0;
    $('#form-pemesanan .error-msg').html("");
    $('#form-pemesanan input').each(function(e){
        if($(this).val()==null||$(this).val()==""||$(this).val()==undefined){
          console.log("kosong");
          $("#"+$(this).attr("header_id")+" .error-msg-"+$(this).attr("labels")).html("Harap isi");
          error=1;
        }
    }); 
    if(error==1){
      return false;
    }
  })