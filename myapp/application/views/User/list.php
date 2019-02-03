<div class="row">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/main">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reports</li>
            </ol>
        </nav><!-- /breadcrumb -->
    </div><!-- /span12 -->
</div><!-- /row-fluid -->

<!-- HElper Message Include -->
<?php $this->load->view('Main/Elements/message');?>

<div class="page-header">
    <h4>
        <div class="row">
            <div class="col"><?php echo($pageTitle) ?></div>
        </div>
    </h4>
</div>

<div class="row-fluid">
	<div class="col">
		<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-bordered" id="tblUserList">
            <thead>
                <tr>
                    <th width="75px">
                        <button title="Click to add user." class="btn btn-secondary add" data-customerid="0" data-toggle="tooltip"><i class="fas fa-plus"></i></button>
                    </th>
                    <th>Name</th>
                    <th>E-Mail Address</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $key => $user) {
                    $date = date_create($user->dateLastModified);
                    ?>
                    <tr>
                        <td >
                            <div class="btn-group">
                                <button title="Click to edit user information." class="btn btn-secondary edit" data-toggle="tooltip" data-userId="<?php echo($user->pkid) ?>"><i class="fa fa-edit"></i></button>
                                <?php if($currentUser->pkid != $user->pkid) {?>
                                <button title="Click to remove user." class="btn btn-secondary delete" data-userId="<?php echo($user->pkid) ?>" data-toggle="tooltip" ><i class="fa fa-trash"></i></button>
                                <?php } ?>
                                <button title="Click to reset user password" class="btn btn-secondary password" data-userId="<?php echo($user->pkid) ?>" data-toggle="tooltip"><i class="fa fa-lock"></i></button>
                            </div>
                        </td>
                        <td><?php echo($user->getName()) ?></td>
                        <td><?php echo($user->emailAddress) ?></td>
                        <td><?php echo(date_format($date, 'm/d/Y')) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
</div>

<div id="modal-remove-user" class="modal fade" tabindex="-1" role="dialog" data-focus-on=":input:not(input[type=button],input[type=submit],button):visible:first">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete user Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p>You are about to delete this user record, this procedure is irreversible.</p>
                <p>Do you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <a href="/users/delete/0" class="btn btn-danger">Yes</a>
            </div>
        </div>
    </div>
</div>


<div id="modal-edit-user" class="modal hide fade" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],input[type=reset],button,[readonly='readonly']):visible:enabled:first" data-backdrop="static" autocomplete="off">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <form id="frmUser" class="form-horizontal" action="/users/save" method="post" data-editing="false">
            <div class="modal-body">
                <input type="hidden" name="userid" id="userId" value="" />
                <div class="form-group">
                    <label class="control-label" for="firstName"><span class="required">*</span>First Name</label>
                    <input id="firstName" name="firstName" class="form-control" size="16" type="text" value="" placeholder="First Name" required="true"/>
                </div>
                <div class="form-group">
                    <label class="control-label" for="lastName"><span class="required">*</span>Last Name</label>
                    <input id="lastName" name="lastName" class="form-control" size="16" type="text" value="" placeholder="Last Name" required="true"/>
                </div>
                <div class="form-group">
                    <label class="control-label" for="emailAddress"><span class="required">*</span>E-Mail Address</label>
                    <input id="emailAddress" name="emailAddress" class="form-control" type="email" value="" placeholder="Email Address" required="true"/>
                </div>
                <div id="frm-group-password">
                    <div class="form-group">
                        <label class="control-label" for="userPassword1"><span class="required">*</span>Password</label>
                        <input id="userPassword1" name="userPassword" class="form-control" type="password" value="" placeholder="Password" required="true" />
                        <small id="nameHelp" class="form-text text-muted">Leave password blank to keep current password.</small>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="userConfirmPassword1"><span class="required">*</span>Password Confirm</label>
                        <input id="userConfirmPassword1" name="userConfirmPassword" class="form-control" type="password" value="" placeholder="Confirm Password" required="true" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button type="submit" id="serviceCallNewSave" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div id="modal-edit-user-password" class="modal hide fade" tabindex="-1" data-focus-on=":input:not(input[type=button],input[type=submit],input[type=reset],button,[readonly='readonly']):visible:enabled:first" data-backdrop="static" autocomplete="off">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <form id="frmUserPassword" class="form-horizontal" action="/users/savepassword" method="post" data-editing="false">
            <div class="modal-body">
                <input type="hidden" name="userid" value="" />
                <div id="frm-group-password">
                    <div class="form-group">
                        <label class="control-label" for="userPassword"><span class="required">*</span>Password</label>
                        <input id="userPassword" name="userPassword" class="form-control" type="password" value="" placeholder="Password" required="true" />
                        <small id="nameHelp" class="form-text text-muted">Leave password blank to keep current password.</small>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="userConfirmPassword"><span class="required">*</span>Password Confirm</label>
                        <input id="userConfirmPassword" name="userConfirmPassword" class="form-control" type="password" value="" data-rule-equalTo="#userPassword"  placeholder="Confirm Password" required="true" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>