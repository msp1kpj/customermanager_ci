    
<div class="row">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Home</li>
            </ol>
        </nav><!-- /breadcrumb -->
    </div><!-- /span12 -->
</div><!-- /row-fluid -->

<!-- HElper Message Include -->
<?php $this->load->view('Main/Elements/message'); ?>
<div class="row-fluid">
		<div class="col">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table table-striped table-bordered table-sm" id="tblCustomerList">
				<thead>
					<tr>
						<th colspan="2">
                            <div class="btn-group pull-right">
                                <button title="Click to view or hide search columns." class="btn btn-secondary clear" data-customerid="0"  data-index="1" data-toggle="tooltip">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                                <button title="Click to view or hide search columns." class="btn btn-secondary view" data-customerid="0"  data-index="1" data-toggle="tooltip">
                                    <i class="far fa-eye-slash"></i>
                                </button>
                            </div>
                        </th>
						<th class="filter"><input type="text" name="search_lastName" value="" class="form-control" placeholder="Search Last Name" data-search="c1.lastName" data-index="2"/></th>
						<th class="filter"><input type="text" name="search_firstName" value="" class="form-control" placeholder="Search First Name" data-search="c1.firstName"  data-index="3"/></th>
						<th class="filter"><input type="text" name="search_address" value="" class="form-control" placeholder="Search Street Address" data-search="c1.address" data-index="4"/></th>
						<th class="filter"><input type="text" name="search_city" value="" class="form-control" placeholder="Search City" data-search="c1.city" data-index="5"/></th>
						<th class="filter"><input type="text" name="search_postalCode" value="" class="form-control" placeholder="Search Postal Code" data-search="c1.postalCode"  data-index="6"/></th>
						<th class="filter"><input type="text" name="search_telephone" value="" class="form-control phoneMask"  placeholder="Search Telephone" data-search="c1.phone" data-index="7"/></th>
					</tr>
					<tr>
						<th>
                            <button title="Click to add service information." class="btn btn-secondary add" data-customerid="0" data-toggle="tooltip"><i class="fas fa-plus"></i></button>
                        </th>
						<th>Service Account Id</th>
  						<th>Company/Last Name</th>
  						<th>First Name</th>
  						<th>Street Address</th>
  						<th>City</th>
  						<th>Postal Code</th>
  						<th>Telephone</th>
  					</tr>
  				</thead>
				<tfoot>
					<tr>
						<th colspan="8">&nbsp;</th>
					</tr>
				</tfoot>
			</table>
		</div><!-- /span12 -->
    </div><!-- /row-fluid -->
    
    <!-- Modals -->
    <div id="modal-edit-account" class="modal hide fade" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <form id="frmAccountEdit" class="form-horizontal" action="/account/put" method="post" autocomplete="off" data-editing="false">
                <div class="modal-body">
                    <input type="hidden" name="customerId" id="customerId" value="0" />
                    <input type="hidden" id="latitude" name="latitude" placeholder="Latitude" value="" data-placement="left" data-geo="lat"/>
                    <input type="hidden" id="longitude" name="longitude" placeholder="Longitude" value=""  data-placement="left" data-geo="lng"/>
                    <input type="hidden" id="county" name="county" placeholder="County" value="" data-placement="left" data-geo="administrative_area_level_2"/>
                    <div class="form-group">
                        <label class="control-label" for="lastName"><span class="required">*</span>Last Name / Company Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name / Company Name" value="" required="true" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="firstName">First Name (optional)</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" value="" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="phone">Telephone (optional)</label>
                        <input type="tel" class="form-control phoneMask" id="phone" name="phone" placeholder="(___) ___-____ x_____" value="" required="true" pattern="^[\\(]{0,1}([0-9]){3}[\\)]{0,1}[ ]?([^0-1]){1}([0-9]){2}[ ]?[-]?[ ]?([0-9]){4}[ ]*((x){0,1}([0-9_]){1,5}){0,1}$"/>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="sourceCode">Source Code (optional)</label>
                        <input type="text" class="form-control typeahead" id="sourceCode" name="sourceCode" placeholder="Source Code e.g.(Yellow Pages, Internet)" value="" autocomplete="off" data-link="/account/getSourceCodeList" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="address"><span class="required">*</span>Address</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="" required="true" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="city"><span class="required">*</span>City</label>
                        <input type="text" class="form-control typeahead" id="city" name="city" placeholder="City" value="" required="true" autocomplete="off" data-link="/account/getCityList" />
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="state"><span class="required">*</span>State</label>
                        <select class="form-control" id="state" name="state" required="true">
                            <option value="">Select a State</option>
                            <option value="MN" >MN - Minnesota</option>
                            <option value="WI" >WI - Wisconsin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="postalCode"><span class="required">*</span>Postal Code</label>
                        <input class="form-control zipcodeUS" type="text" id="postalCode" name="postalCode" placeholder="Postal Code" value="#rc.account.info.postalCode#"  required="true"/>
                    </div>
                    <div id="map_canvas" style=" height: 150px; width: 500px;">
                    <div id="map_canvas1" style=" height: 100%;"></div>
                    </div>
                </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button type="button" id="serviceEditSave" class="btn btn-primary" disabled>Save changes</button>
                </div>
            </div>
        </div>
	</div>
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
    <div id="modal-account-detail" class="modal fade" tabindex="-1" role="dialog" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Account Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <address id="customerAddress">
                        <strong>Twitter, Inc.</strong><br>
                        795 Folsom Ave, Suite 600<br>
                        San Francisco, CA 94107<br>
                        <abbr title="Phone">P:</abbr> (123) 456-7890
                    </address>
                    <h2>Notes:</h2>
                    <p class="customerNote" id="customerNote"></p>
                    <table class="table table-striped table-bordered table-sm" id="tblAccountServices">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Tech / Call</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr><td colspan="5">&nbsp;</td></tr>
                        </tfoot>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-view-column" class="modal fade" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View or Remove Columns</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
                    <p>You have started entering in data for a new account. You will lose all data entered if you cancel now.</p>
                    <p>Do you want to proceed?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmCloseEdit">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modals -->

<template id="col-view-checkbox">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="" />
        <label class="form-check-label" for="">
        </label>
    </div>
</template>