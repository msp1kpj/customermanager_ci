<div class="row">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/main">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Customer Account</li>
            </ol>
        </nav><!-- /breadcrumb -->
    </div><!-- /span12 -->
</div><!-- /row-fluid -->

<!-- HElper Message Include -->
<?php $this->load->view('Main/Elements/message');
    if(IsSet($account)){
        $form_action = "/account/post";
        if(!$account->customerId){
            $form_action = "/account/put";
        }
?>

	<form id="frmAccount" class="form mb-2" action="<?php echo $form_action;?>" method="post" data-editing="false" autocomplete="off">
		<input type="hidden" value="<?php echo $account->customerId;?>" name="customerId" id="customerId" />
		<fieldset>
			<legend>Account Edit</legend>
            <h4>Essential information is marked with an asterisk (<span class="required">*</span>)</h4>
            <div class="row">
                <div class="col col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="lastName"><span class="required">*</span>Last Name / Company Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" value="<?php echo $account->lastName;?>" required="true" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="firstName">First Name (optional)</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" value="<?php echo $account->firstName;?>" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="phone">Telephone (optional)</label>
                        <input type="tel" class="form-control phoneMask" id="phone" name="phone" placeholder="(___) ___-____ x_____" value="<?php echo $account->phone;?>" pattern="^[\\(]{0,1}([0-9]){3}[\\)]{0,1}[ ]?([^0-1]){1}([0-9]){2}[ ]?[-]?[ ]?([0-9]){4}[ ]*((x){0,1}([0-9_]){1,5}){0,1}$"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="sourceCode">Source Code (optional)</label>
                        <input type="text" class="form-control typeahead" id="sourceCode" name="sourceCode" placeholder="Source Code e.g.(Yellow Pages, Internet)" value="<?php echo $account->sourceCode;?>" autocomplete="off" data-link="/account/getSourceCodeList" />
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="address"><span class="required">*</span>Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo $account->address;?>" required="true" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="city"><span class="required">*</span>City</label>
                        <input type="text" class="form-control typeahead" id="city" name="city" placeholder="City" value="<?php echo $account->city;?>" required="true" autocomplete="off" data-link="/account/getCityList" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="state"><span class="required">*</span>State</label>
                        <select class="form-control" id="state" name="state" required="true">
                            <option value="">Select a State</option>
                            <option value="MN" <?php if($account->state == "MN"){ ?> selected="true"<?php } ?> >MN - Minnesota</option>
                            <option value="WI" <?php if($account->state == "WI"){ ?> selected="true"<?php } ?> >WI - Wisconsin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="postalCode"><span class="required">*</span>Postal Code</label>
                        <input class="form-control zipcodeUS" type="text" id="postalCode" name="postalCode" placeholder="Postal Code" value="<?php echo $account->postalCode;?>"  required="true"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="notes">Note (optional)</label>
                        <textarea class="form-control" id="notes" name="notes" placeholder="Note" rows="5" style="resize: none;"><?php echo $account->notes;?></textarea>
                    </div>
                </div>
				<div class="col-md-12">
					<div class="form-actions">
						<button type="submit" class="btn btn-primary" disabled>Save changes</button>
						<button type="button" class="btn btn-danger float-right" id="delete-account" data-customerid="<?php echo $account->customerId;?>">Delete Account</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
    <?php } ?>
	<div class="row">
		<div class="col">
			<table class="table table-striped table-bordered table-sm" id="tblServiceList">
				<thead>
					<tr>
						<th>
                            <div class="btn-group">
                                <button title="Click to create a new service call." class="btn btn-secondary add-service-call" data-toggle="tooltip"><i class="fas fa-calendar-plus"></i></button>
                                <button title="Click to add service information." class="btn btn-secondary add " data-toggle="tooltip"><i class="fa fa-plus"></i></button>
                            </div>
                        </th>
                        <th class="col-xs-1">Date</th>
						<th class="col-xs-1">Amount</th>
						<th class="col-xs-1">Description</th>
						<th class="col-xs-1">Technician/Service Call</th>
						<th class="col-xs-1">Note</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th colspan="6">&nbsp;</th>
					</tr>
                </tfoot>
                <?php if (isset($services)) { ?>
					<tbody>
                        <?php foreach ($services as $row){?>
							<tr>
                                <td><?php echo $row['serviceId'];?></td>
								<td class="text-right"><?php echo date_format(date_create($row['dateOfService']),"m/d/Y");?></td>
								<td class="text-right"><?php echo "$" . number_format($row['amount'],2);?></td>
								<td><?php echo $row['description'];?></td>
								<td><?php echo $row['technician'];?></td>
								<td><?php echo $row['note'];?></td>
							</tr>
						<?php } ?>
					</tbody>
                <?php } ?>
			</table>
		</div><!-- /col -->
    </div><!-- /row-fluid -->
    
    <div id="modal-remove-account" class="modal fade" tabindex="-1" role="dialog" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Account Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete this account record, this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    <a href="/account/delete/0" class="btn btn-danger">Yes</a>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirm-edit-close" class="modal fade confirm" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Close Customer New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You have started entering in data for a this account. You will lose all data entered if you cancel now.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmCloseEdit">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-confirm-service-edit-close" class="modal fade confirm" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Close Customer New</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You have started entering in data for a this service. You will lose all data entered if you cancel now.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmServiceCloseEdit">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-edit-service" class="modal hide fade" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],input[type=reset],button,[readonly='readonly']):visible:enabled:first" data-backdrop="static" autocomplete="off">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Service Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <form id="frmServiceEdit" class="form-horizontal" action="/service/save" method="post" enctype="multipart/form-data" data-editing="false">
                <div class="modal-body">
                    <input type="hidden" name="serviceId" id="serviceId" value="" />
                    <input type="hidden" value="<?php echo $account->customerId;?>" name="customerId" id="customerId">
                    <div class="form-group">
                        <label class="control-label" for="dateOfService"><span class="required">*</span>Date</label>
                        <div class="input-group date input-group-lg" id="dp3" data-date-format="mm/dd/yyyy">
                            <input id="dateOfService" name="dateOfService" class="form-control" size="16" type="text" value="" placeholder="Date" data-date-autoclose="true" readonly="true"  required="true"/>
                            <label class="input-group-append" for="dateOfService">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="technician" data-toggle="tooltip" title="Use &ldquo;Service Call&rdquo; to indicate the date of a service call" data-placement="bottom"><span class="required">*</span>Technician</label>
                        <input type="text" class="form-control form-control-lg" id="technician" name="technician" placeholder="Technician" value=""  autocomplete="off" data-link="/account/getTechnicianList" required="true"/>
                    </div><!-- /form-group -->
                    <div class="form-group">
                        <label class="control-label" for="notes">Notes</label>
                        <textarea id="notes" class="form-control form-control-lg" name="notes" placeholder="Notes" rows="4" ></textarea>
                    </div><!-- /form-group -->
                    <div id="service_descriptions">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for=""><span class="required">*</span>Service(s)</label>
                                <input type="text" class="form-control form-control-lg service-group" id="description[0]" name="description[]" placeholder="Description" value=""  autocomplete="off" data-link="/account/getServiceList" />
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="">&nbsp;</label>
                                <div class="input-group date input-group-lg">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">$</div>
                                    </div>
                                    <input type="text" class="form-control form-control-lg" id="amount[0]" name="amount[]" placeholder="_,___.__" value="" data-a-sign=""/>
                                </div>
                            </div><!-- /form-group -->
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button type="submit" id="serviceEditSave" class="btn btn-primary" disabled>Save changes</button>
                </div>
                </form>
            </div>
        </div>
	</div>
    <div id="modal-remove-service" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-remove-service" aria-hidden="true">
		<div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Service Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to delete one service record, this procedure is irreversible.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    <a href="/account/0/removeService/0" class="btn btn-danger">Yes</a>
                </div>
            </div>
        </div>
	</div>

    <div id="modal-add-service-call" class="modal hide fade" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],input[type=reset],button,[readonly='readonly']):visible:enabled:first" data-backdrop="static" autocomplete="off">
		<div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Service Call Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <form id="frmServiceCallNew" class="form-horizontal" action="/service/save" method="post" data-editing="false">
                <div class="modal-body">
                    <input type="hidden" name="serviceId" id="serviceId" value="" />
                    <input type="hidden" value="<?php echo $account->customerId;?>" name="customerId" id="customerId">
                    <input type="hidden" id="technician" name="technician" value="Service Call"/>
                    <div class="form-group">
                        <label class="control-label" for="dateOfService"><span class="required">*</span>Date</label>
                        <div class="input-group date input-group-lg" id="dp3" data-date-format="mm/dd/yyyy">
                            <input id="dateOfServiceCall" name="dateOfService" class="form-control" size="16" type="text" value="" placeholder="Date" data-date-autoclose="true" readonly="true"  required="true"/>
                            <label class="input-group-append" for="dateOfServiceCall">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="notes">Notes</label>
                        <textarea id="notes" class="form-control form-control-lg" name="notes" placeholder="Notes" rows="4" ></textarea>
                    </div><!-- /form-group -->
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button type="submit" id="serviceCallNewSave" class="btn btn-primary">Save changes</button>
                </div>
	            </form>
            </div>
        </div>
	</div>
