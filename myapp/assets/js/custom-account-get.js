function actionRender(data, type, full, meta) {
    if (type === 'display') {
        if (full.technician && full.technician.toLowerCase() === "service call") {
            return '<div class="btn-group"><button title="Click to edit this service information." class="btn btn-secondary edit" data-toggle="tooltip" data-row="' + meta.row + '" data-customerId="' + $("#customerId").val() +'"><i class="fa fa-edit"></i></button></div>';
        }
        return '<div class="btn-group"><button title="Click to edit this service information." class="btn btn-secondary edit" data-toggle="tooltip" data-row="' + meta.row + '" data-customerId="' + $("#customerId").val() +'"><i class="fa fa-edit"></i></button><button title = "Click to remove this service information." class="btn btn-secondary delete" data-serviceId="' + data + '" data-customerId="' + $("#customerId").val() +'" data-toggle="tooltip" ><i class="fa fa-trash"></i></button></div>';
    }
    return data;

}

function extractor(query) {
    var result = /([^,]+)$/.exec(query);
    if (result && result[1]) return result[1].trim();
    return '';
}

$(document).ready(function(){
    var account = $("#frmAccount"),
        aoColumnDefs = [];

    var nEditing = function () { return $("#frmAccount").data("editing"); }
    

    $('[data-toggle="tooltip"]').tooltip();
    $(".phoneMask").mask("(999) 999-9999? x99999");

    account.on("keyup", function (e) {
        var self = $(this),
            bntSubmit = $("[type='submit']")
        ;

        if (self.data("current") !== self.serialize()) {
            bntSubmit.removeAttr("disabled");
            account.data("editing", true);
        } else {
            bntSubmit.attr("disabled", "true");
            account.data("editing", false);
        }


    })
    .data("current", account.serialize())
        .validate({
            onkeyup: false, 
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("invalid-feedback");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).addClass("is-valid").removeClass("is-invalid");
            } });

    $("#delete-account").on('click', function(e){
        var self = $(this)
            , customerId = self.data('customerid')
            ;

        $('#modal-remove-account')
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true });
    });

    $('#modal-remove-account')
        .on('show.bs.modal', function () {
            var self = $(this)
                , customerId = self.data('customerId')
                , removeBtn = self.find('.btn-danger')
                , href = removeBtn.attr('href')
                ;
            removeBtn.attr('href', href.replace(/\d+/g, customerId));
        })
        .on('hidden.bs.modal', function () {
            var self = $(this)
                , removeBtn = self.find('.btn-danger')
                , href = removeBtn.attr('href')
                ;
            removeBtn.attr('href', href.replace(/\d+/g, 0));
        })
    ;


    aoColumnDefs.push({ "targets": [0], "searchable": true, "sortable": false, "visible": true, "width": "70px", "render": actionRender, "data": "serviceId" });
    aoColumnDefs.push({ "targets": [1], "searchable": true, "sortable": true, "visible": true, "width": "84px", "type": "date", "data": "dateOfService" });
    aoColumnDefs.push({ "targets": [2], "searchable": true, "sortable": true, "visible": true, "width": "70px", "type": "currency", "data": "amount" });
    aoColumnDefs.push({ "targets": [3], "searchable": true, "sortable": true, "visible": true, "data": "description" });
    aoColumnDefs.push({ "targets": [4], "searchable": true, "sortable": true, "visible": true, "data": "technician" });
    aoColumnDefs.push({ "targets": [5], "searchable": true, "sortable": true, "visible": true, "data": "note" });

    dataTableOption = {
        "order": [[1, 'desc']],
        "language": {
            "search": "Search all columns:",
            "lengthMenu": "Display _MENU_ records per page",
        },
        "columnDefs": aoColumnDefs,
    };

    var serviceTable = $("#tblServiceList");

    var $oTable = serviceTable.DataTable(dataTableOption)
    $oTable.on('draw', function () {
            $('tbody button[data-toggle="tooltip"]').tooltip();
        })
        .draw()
    ;

    serviceTable
        .delegate(".delete", "click", removeService)
        .delegate(".edit", "click", editService)
        .delegate(".add-service-call", 'click', addServiceCall);

    $("thead button.add").on("click", addService);

    $("#confirmCloseEdit").on('click', function (e) {
        account.data("editing", false);
        $("#modal-confirm-edit-close").modal('hide');
        addService();
    });

    $("#frmServiceEdit").on("keyup", function (e) {
        var self = $(this),
            bntSubmit = $("[type='submit']")
            ;

        if (self.data("current") !== self.serialize()) {
            bntSubmit.removeAttr("disabled");
            self.data("editing", true);
        } else {
            bntSubmit.attr("disabled", "true");
            self.data("editing", false);
        }


    }).data("current", $("#frmServiceEdit").serialize());



    function addService() {
        if (nEditing()) {
            $('#modal-confirm-edit-close').modal({ show: true });
            return
        }
        var self = $(this)
            , serviceId = self.data('serviceid')
            , customerId = self.data('customerid')
            , frm = $("#frmServiceEdit")
            ;

        $("#frmServiceEdit input[name='serviceId']").val('0');
        $("#frmServiceEdit input[name='dateOfService']").val('');
        $("#frmServiceEdit input[name='amount']").val('');
        $("#frmServiceEdit textarea[name='description']").val('');
        $("#frmServiceEdit input[name='technician']").val('');
        $("#frmServiceEdit textarea[name='notes']").val('');
        $('#dateOfService').datepicker('update', new Date());
        frm.data("current", frm.serialize());
        frm.trigger('keyup');

        $('#modal-edit-service')
            .data('serviceId', 0)
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true });
    }

    $('#modal-edit-service').on('show.bs.modal', function () {
            $("#frmServiceEdit").data("editing", false).trigger('service-show');
        })
        .on('hide.bs.modal', function (e) {
            var editing = $("#frmServiceEdit").data("editing");

            if (editing !== undefined && editing) {
                $('#modal-confirm-service-edit-close').modal({ show: true });
                e.preventDefault();
            } else {
                $("#frmServiceEdit").validate().resetForm();
                $("#frmServiceEdit").find('.form-group :input').each(function () { $(this).removeClass('is-valid').removeClass('is-invalid'); });
                $("#frmServiceEdit").find("input[name^='description']").each(function (index, ele){
                    var self = $(ele);
                    self.val('');
                    self.closest("input[name^='amount']").val();
                    if(index){
                        self.parent().parent().remove();
                    }
                });
            }

        })

    $('#confirmServiceCloseEdit').on("click", function(){
        $("#frmServiceEdit").data("editing", false);
        $('#modal-confirm-service-edit-close').modal('hide');
        $('#modal-edit-service').modal('hide');
    });

    $('.typeahead').typeahead({
				source: function(query, process) {
					return $.ajax({
							url: $($(this)[0].$element[0]).data('link'),
							type: 'get',
							data: {q: query},
							dataType: 'json',
							success: function(json) {
								return typeof json.options == 'undefined' ? false : process(json.options);
							}
						});
					}
				, updater: function (item) {
					$("#frmAccount").trigger("keyup");
					return item
				}
			});

    
    $("#technician").typeahead(
        {
            source: function (query, process) {
                var searchItems = query.split(",");
                return $.ajax({
                    url: $($(this)[0].$element[0]).data('link'),
                    type: 'get',
                    data: { q: searchItems[searchItems.length-1] },
                    dataType: 'json',
                    success: function (json) {
                        return typeof json.options == 'undefined' ? false : process(json.options);
                    }
                });
            }
            , updater: function (item) {
                $("#frmServiceEdit").trigger("keyup");
                return this.$element.val().replace(/[^,]*$/, '') + item + ',';
            }
            , matcher: function (item) {
                var tquery = extractor(this.query);
                if (!tquery) return false;
                return ~item.toLowerCase().indexOf(tquery.toLowerCase())
            }
            , highlighter: function (item) {
                var query = extractor(this.query).replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
                return item.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
                    return '<strong>' + match + '</strong>'
                });
            }
        }
    );

    $("#frmServiceEdit").bind('service-show', function(){
        $(this).find("input[name^='description']").on("keyup", function (e) {
            var self = $(this)
                , nextSibling = self.parent().parent().next()
                , nextDescription = nextSibling.find("input[name^='description']")
                , inputs = $("input[name^='description']")
                ;
            
            if (self.val() == '' && nextDescription.length){
                if (nextSibling.find("input[name^='description']").first().val() == ''){
                    nextSibling.remove();
                } 
            } else if (self.val() != '' && !nextSibling.length){
                var newRow = self.closest(".form-row").clone(),
                    newDescription = newRow.find("input[name^='description']"),
                    newAmount = newRow.find("input[name^='amount']")
                ;
                newRow.find("label").remove();
                newRow.find("em").remove();
                newDescription.removeClass("is-invalid").removeClass("is-valid")
                newAmount.removeClass("is-invalid").removeClass("is-valid")

                newDescription.val('').prop('id', newDescription.prop('id').replace(/\d/, inputs.length));
                newAmount.val('').prop('id', newAmount.prop('id').replace(/\d/, inputs.length));

                self.parent().parent().parent().append(newRow);

                $('input[name^="description"]').each(function () {
                    $(this).rules("add",
                        {
                            required: function (element) {
                                return $(element).parent().next().find('input[name^="amount"]').val().length;
                            },
                            messages: {
                                required: "Description is required",
                            }
                        });
                });
                
                $("#frmServiceEdit").trigger('service-show');
            }

        }).typeahead(
            {
                source: function (query, process) {
                    var searchItems = query.split(",");
                    return $.ajax({
                        url: $($(this)[0].$element[0]).data('link'),
                        type: 'get',
                        data: { q: searchItems[searchItems.length - 1] },
                        dataType: 'json',
                        success: function (json) {
                            return typeof json.options == 'undefined' ? false : process(json.options);
                        }
                    });
                }
                , updater: function (item) {
                    $("#frmServiceEdit").trigger("keyup");
                    return this.$element.val().replace(/[^,]*$/, '') + item + ',';
                }
                , matcher: function (item) {
                    var tquery = extractor(this.query);
                    if (!tquery) return false;
                    return ~item.toLowerCase().indexOf(tquery.toLowerCase())
                }
                , highlighter: function (item) {
                    var query = extractor(this.query).replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
                    return item.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
                        return '<strong>' + match + '</strong>'
                    });
                }
            }
        );
    });

    

    $("#frmServiceEdit").validate({
        onkeyup: false,
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }


    });
    $('input[name^="description"]').each(function () {
        $(this).rules("add",
            {
                required: function (element) {
                    return $(element).parent().next().find('input[name^="amount"]').val().length;
                },
                messages: {
                    required: "Description is required",
                }
            });
    });


    function removeService() {
        if (nEditing()) {
            $('#modal-confirm-edit-close').modal({ show: true });
            return
        }
        var self = $(this)
            , serviceId = self.data('serviceid')
            , customerId = self.data('customerid')
            ;

        $('#modal-remove-service')
            .data('serviceId', serviceId)
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true });
    }

    $('#modal-remove-service')
        .on('show.bs.modal', function (e) {
            var self = $(this)
                , serviceId = self.data('serviceId')
                , customerId = self.data('customerId')
                , removeBtn = self.find('.btn-danger')
                , href = removeBtn.attr('href')
                ;
                
            removeBtn.attr('href', "/service/remove/" + serviceId);
        })
        .on('shown.bs.modal', function () {
            $(this).find("button.btn-primary:first").focus();
        })
        .on('hidden.bs.modal', function () {
            var self = $(this)
                , removeBtn = self.find('.btn-danger')
                , href = removeBtn.attr('href')
                ;
            removeBtn.attr('href', "/account/removeService/0");
        });


    function addServiceCall() {
        var self = $(this)
            , serviceId = self.data('serviceid')
            , customerId = self.data('customerid')
            , frm = $("#frmServiceCallNew")
            , serviceCallDate = new Date()
            ;
        serviceCallDate = new Date(serviceCallDate.setFullYear(serviceCallDate.getFullYear() + 2));

        $("#frmServiceCallNew input[name='serviceId']").val('0');
        $("#frmServiceCallNew input[name='dateOfService1']").val('');
        $("#frmServiceCallNew input[name='technician']").val('Service Call');
        $("#frmServiceCallNew textarea[name='notes']").val('');
        $('#dateOfServiceCall').datepicker('update', serviceCallDate);

        $('#modal-add-service-call')
            .data('serviceId', 0)
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true });
        return;
    }

    function editService() {
        var self = $(this);
        var data = $oTable.row(self.data("row")).data();

        if (nEditing()) {
            $('#modal-confirm-edit-close').modal({ show: true });
            return
        }
        var serviceId = data.serviceId
            , customerId = self.data('customerid')
            , frm = $("#frmServiceEdit")
            , serviceCallDate = new Date(data.dateOfService)
            ;
        if (data.technician === "Service Call"){
            $("#frmServiceCallNew input[name='serviceId']").val(serviceId);
            $("#frmServiceCallNew input[name='technician']").val('Service Call');
            $("#frmServiceCallNew textarea[name='notes']").val(data.note);
            $('#dateOfServiceCall').datepicker('update', serviceCallDate);
            $('#modal-add-service-call')
                .data('serviceId', serviceId)
                .data('customerId', customerId)
                .modal({ backdrop: 'static', keyboard: true });
        } else {
            $("#frmServiceEdit input[name='serviceId']").val(serviceId);
            $("#frmServiceEdit input[name^='amount']").val(String(data.amount).replace(/[^0-9\.,]+/g, ''));
            $("#frmServiceEdit input[name^='description']").val(data.description);
            $("#frmServiceEdit input[name='technician']").val(data.technician);
            $("#frmServiceEdit textarea[name='notes']").val(data.note);
            $('#dateOfService').datepicker('update', serviceCallDate);
            frm.data("current", frm.serialize());
            frm.trigger('keyup');

            $('#modal-edit-service')
                .data('serviceId', serviceId)
                .data('customerId', customerId)
                .modal({ backdrop: 'static', keyboard: true });
        }
        return;
    }

    
    


});
