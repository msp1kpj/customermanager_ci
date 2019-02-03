function actionRender(data, type, full) {
    if (type === 'display') {
        var URLString = '<div class="btn-group" role="group"><a href="/account/get/{customerId}" title="Click to edit this customer." class="btn btn-secondary" data-toggle="tooltip"><i class="far fa-edit"></i></a><button title="Click to remove this service information." class="btn btn-secondary delete" data-customerId="{customerId}" data-toggle="tooltip"><i class="fas fa-trash"></i></button><button title="Click to view this service information." class="btn btn-secondary view-detail" data-customerId="{customerId}" data-toggle="tooltip"><i class="far fa-eye"></i></button></div>';
        URLString = URLString.replace(/\{customerId\}/g, data);
        return URLString;
    }
    return data;
}

function phoneRender(data, type, full) {
    if (type === 'display') {
        var val = String(data).replace(/[^0-9]/g, '');
        return phoneFormat(val);
    }
    return data;
}

function phoneFormat(val) {
    val = String(val);
    if (val.length === 7) {
        return String(val).replace(/(\d{3})(\d{4})/, '$1-$2');
    } else if (val.length === 10) {
        return String(val).replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    } else if (val.length > 10) {
        return String(val).replace(/(\d{3})(\d{3})(\d{4})(\d+)/, '($1) $2-$3 x$4');
    }
    return val
}

function viewColumnOption() {
    var self = $(this)
    $('#modal-view-column')
        .modal({ backdrop: 'static', keyboard: true });
}



$(document).ready(function () {
    var $oTable = {},
        aoColumnDefs = [],
        customerTable = $("#tblCustomerList"),
        frmAccountEdit = $('#frmAccountEdit')
    ;

    $(".phoneMask").mask("(999) 999-9999? x99999");

    aoColumnDefs.push({ "targets": [0], "searchable": false, "sortable": false, "visible": true, "width": "75px", "render": actionRender, "data": "customerId", "name": "c1.customerId" });
    aoColumnDefs.push({ "targets": [1], "searchable": false, "sortable": false, "visible": false, "class": "hide", "type": "numeric", "data": "customerId", "name": "c1.customerId" });
    aoColumnDefs.push({ "targets": [2], "searchable": true, "sortable": true, "visible": true, "data": "lastName", "name": "c1.lastName" });
    aoColumnDefs.push({ "targets": [3], "searchable": true, "sortable": true, "visible": true, "data": "firstName", "name": "c1.firstName" });
    aoColumnDefs.push({ "targets": [4], "searchable": true, "sortable": true, "visible": true, "data": "address", "name": "c1.address" });
    aoColumnDefs.push({ "targets": [5], "searchable": true, "sortable": true, "visible": true, "data": "city", "name": "c1.city" });
    aoColumnDefs.push({ "targets": [6], "searchable": true, "sortable": true, "visible": false, "data": "postalCode", "name": "c1.postalCode" });
    aoColumnDefs.push({ "targets": [7], "searchable": true, "sortable": true, "visible": true, "render": phoneRender, "data": "phone", "name": "c1.phone" });
    

    dataTableOption = {
        "order": [[2, 'asc'], [3, 'asc']],
        "language": {
            "search": "Search all columns:",
            "lengthMenu": "Display _MENU_ records per page",
        },
        "columnDefs": aoColumnDefs,
        "ajax": {
            "url": "/main/accountlist",
            "deferRender": true
        },
        "processing": true,
        "serverSide": true
    };

    customerTable.on('preXhr.dt', function (e, settings, data) {
        data.columns[7].search.value = data.columns[7].search.value.replace(/[^0-9\.]+/g, '');
    });

    $oTable['tblCustomerList'] = customerTable.DataTable(dataTableOption);

    $oTable['tblCustomerList'].on('draw', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    
    $("th.filter :input").on('keyup change', function () {
        var self = $(this),
            searchIndex = self.data("index")
        ;

        if (typeof (Storage) !== "undefined") {
            if (!sessionStorage[self.prop("name")]) { sessionStorage[self.prop("name")] = ""; }
            sessionStorage[self.prop("name")] = self.val();
        }
        
        $oTable['tblCustomerList']
            .column(searchIndex)
            .search(this.value)
            .draw();
    });


    customerTable.find(":input[name='search_lastName']").val($oTable['tblCustomerList'].column(2).search());
    customerTable.find(":input[name='search_firstName']").val($oTable['tblCustomerList'].column(3).search());
    customerTable.find(":input[name='search_address']").val($oTable['tblCustomerList'].column(4).search());
    customerTable.find(":input[name='search_city']").val($oTable['tblCustomerList'].column(5).search());
    customerTable.find(":input[name='search_postalCode']").val($oTable['tblCustomerList'].column(6).search());
    customerTable.find(":input[name='search_telephone']").val($oTable['tblCustomerList'].column(7).search());

    function removeAccount() {
        var self = $(this)
            , customerId = self.data('customerid')
            ;

        $('#modal-remove-account')
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true });
    }

    function viewAccountDetail() {
        var self = $(this)
            , customerId = self.data('customerid')
            ;

        $('#modal-account-detail')
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true, width: '50%' });
    }

    function addAccount() {
        var self = $(this)
            , customerId = self.data('customerid')
            ;

        frmAccountEdit.find(":input[name='firstName']").val("");
        frmAccountEdit.find(":input[name='lastName']").val("");
        frmAccountEdit.find(":input[name='phone']").val("");
        frmAccountEdit.find(":input[name='sourceCode']").val("");
        frmAccountEdit.find(":input[name='address']").val("");
        frmAccountEdit.find(":input[name='fulladdress']").val("");
        frmAccountEdit.find(":input[name='city']").val("");
        frmAccountEdit.find(":input[name='state']").val("");
        frmAccountEdit.find(":input[name='postalCode']").val("");
        frmAccountEdit.find(":input[name='latitude']").val("");
        frmAccountEdit.find(":input[name='longitude']").val("");
        frmAccountEdit.find(":input[name='county']").val("");


        frmAccountEdit.data("current", frmAccountEdit.serialize());
        frmAccountEdit.trigger('keyup');
        frmAccountEdit.validate({ 
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

        $('#modal-edit-account')
            .data('customerId', customerId)
            .modal({ backdrop: 'static', keyboard: true });
    }

    customerTable
        .delegate(".add", "click", addAccount)
        .delegate(".delete", "click", removeAccount)
        .delegate(".view-detail", "click", viewAccountDetail)
        .delegate(".view", "click", viewColumnOption)
        .delegate('.clear', 'click', function (event) {
            $oTable['tblCustomerList'].search('').columns().search('');

            customerTable.find(":input[name='search_lastName']").val($oTable['tblCustomerList'].column(2).search());
            customerTable.find(":input[name='search_firstName']").val($oTable['tblCustomerList'].column(3).search());
            customerTable.find(":input[name='search_address']").val($oTable['tblCustomerList'].column(4).search());
            customerTable.find(":input[name='search_city']").val($oTable['tblCustomerList'].column(5).search());
            customerTable.find(":input[name='search_postalCode']").val($oTable['tblCustomerList'].column(6).search());
            customerTable.find(":input[name='search_telephone']").val($oTable['tblCustomerList'].column(7).search());
            $oTable['tblCustomerList'].draw();
        })
    ;

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

    $('#modal-account-detail')
        .on('show.bs.modal', function () {
            var self = $(this)
                , customerId = self.data('customerId')
                ;

            $.ajax({
                url: "/main/account/" + customerId,
                type: 'get',
                dataType: 'json',
                success: function (json) {

                    var customerAddress = $("#customerAddress")
                        , tblAccountServices = $("#tblAccountServices").find('tbody').first()
                        , addressString = "<strong>{name}</strong><br/>{address}<br/>{city}, {state} {postalCode}<br/><abbr title='Phone'>P:</abbr>&nbsp;{phone}"
                        , name = json.account.data.lastName + (json.account.data.firstName.length ? ", " + json.account.data.firstName : "")
                        , address = json.account.data.address.length ? json.account.data.address : "[No Address on File]"
                        ;

                    addressString = addressString.replace(/{name}/g, name)
                        .replace(/{address}/g, address)
                        .replace(/{address}/g, address)
                        .replace(/{city}/g, json.account.data.city)
                        .replace(/{state}/g, json.account.data.state)
                        .replace(/{postalCode}/g, json.account.data.postalCode)
                        .replace(/{phone}/g, phoneFormat(json.account.data.phone))
                        ;

                    customerAddress.html(addressString);
                    $("#customerNote").text((json.account.data.notes && json.account.data.notes.length) ? json.account.data.notes : "[No Account Notes]");
                    tblAccountServices.html("");                    

                    for (var service in json.account.info) {
                        service = json.account.info[service];
                        var dateofservice = service.dateOfService.replace("{ts '", "").replace("'}", "").replace(" 00:00:00", "")
                            , amount = accounting.formatMoney(service.amount) // 
                            , description = service.description
                            , technician = service.technician
                            , note = service.note
                        ;
                        tblAccountServices.append("<tr><td>" + dateofservice + "</td><td class='text-right'>" + amount + "</td><td>" + description + "</td><td>" + technician + "</td><td>" + note + "</td></tr>");
                    }
                    return typeof json.options == 'undefined' ? false : process(json.options);
                }
            });
        })
        .on("shown.bs.hiddeen", function () {
            $("#customerNote").text('');
            $("#tblAccountServices").find('tbody').first().html("");
            $("#customerAddress").html("");
        });

    $('#modal-view-column')
        .on('show.bs.modal', function () {
            var list = $('#modal-view-column').find(".modal-body:last"),
                listColumn = $oTable['tblCustomerList'].columns(),
                checkTemplate = $("#col-view-checkbox");
                ;

            
            if(!list.children().length){
                $.each(listColumn.eq("0"), function (index, value) {
                    var chkBox;
                    if (index > 1) {
                        var workingCheck = $(checkTemplate.clone().html());

                        workingCheck.find("label")
                            .prop("for", "col-" + $oTable['tblCustomerList'].column(value).dataSrc())
                            .text($($oTable['tblCustomerList'].column(value).header()).text())
                        ;

                        workingCheck.find(":input")
                            .prop("id", "col-" + $oTable['tblCustomerList'].column(value).dataSrc())
                            .prop("name", "col-" + $oTable['tblCustomerList'].column(value).dataSrc())
                            .prop("checked", $oTable['tblCustomerList'].column(value).visible())
                            .val(value);
                        ;
                        list.append(workingCheck)
                        //list.append(chkBox);
                    };
                });
                list.find(":input").on("change", function(){
                    var self = $(this)
                        , objInput = $('*[data-index="' + self.val() + '"]')
                    ;

                    if (objInput.length) {
                        objInput.first().val('');
                    }

                    fnShowHide(self.val())
                });
            }
        });

    $('#modal-edit-account').on('show.bs.modal', function () {
        frmAccountEdit.data("editing", false);
        $(".error").removeClass("error");
        $(".popover").remove();
    }).on('hide.bs.modal', function (e) {
        
        var editing = frmAccountEdit.data("editing");

        if (editing !== undefined && editing) {
            $('#modal-confirm-edit-close').modal({ show: true });
            e.preventDefault();
        }
    }).on('hidden.bs.modal', function (e) {
        $("#modal-confirm-edit-close").modal('hide');
    });

    frmAccountEdit.on("keyup change", function (e) {
        var self = $(this)
            , bntSubmit = $("#serviceEditSave")
            ;
        if (self.data("current") !== self.serialize()) {
            bntSubmit.removeAttr("disabled");
            frmAccountEdit.data("editing", true);
        } else {
            bntSubmit.attr("disabled", "true");
            frmAccountEdit.data("editing", false);
        }
    }).data("current", frmAccountEdit.serialize());

    $("#serviceEditSave").on('click', function (e) {
        frmAccountEdit.submit();
    });

    $("#confirmCloseEdit").on('click', function(e){
        frmAccountEdit.data("editing", false);
        $("#modal-edit-account").modal('hide');
    });

    function fnShowHide(iCol) {
        /* Get the DataTables object again - this is not a recreation, just a get of the object */
        var oTable = $oTable['tblCustomerList'],
            column = oTable.column(iCol)
        ;

        var bVis = column.visible();
        column.visible(bVis ? false : true);
        //oTable.columns(iCol).searchable(oTable.columns(iCol).visible());
        column.search("");

        $("th.filter :input").unbind('keyup change').on('keyup change', function () {
            var self = $(this),
                searchIndex = self.data("index");

            if (typeof (Storage) !== "undefined") {
                if (!sessionStorage[self.prop("name")]) { sessionStorage[self.prop("name")] = ""; }
                sessionStorage[self.prop("name")] = self.val();
            }

            $oTable['tblCustomerList']
                .column(searchIndex)
                .search(this.value)
                .draw();
        });
    }

    if (typeof (Storage) !== "undefined") {
        if (!sessionStorage.search_lastName) { sessionStorage.search_lastName = ""; }
        if (!sessionStorage.search_firstName) { sessionStorage.search_firstName = ""; }
        if (!sessionStorage.search_address) { sessionStorage.search_address = ""; }
        if (!sessionStorage.search_city) { sessionStorage.search_city = ""; }
        if (!sessionStorage.search_postalCode) { sessionStorage.search_postalCode = ""; }
        if (!sessionStorage.search_telephone) { sessionStorage.search_telephone = ""; }

        $("input[name=search_lastName]").val(sessionStorage.search_lastName).trigger("change");
        $("input[name=search_firstName]").val(sessionStorage.search_firstName).trigger("change");
        $("input[name=search_address]").val(sessionStorage.search_address).trigger("change");
        $("input[name=search_city]").val(sessionStorage.search_city).trigger("change");
        $("input[name=search_postalCode]").val(sessionStorage.search_postalCode).trigger("change");
        $("input[name=search_telephone]").val(sessionStorage.search_telephone).trigger("change");

    } else {
        // Sorry! No Web Storage support..
    }



});