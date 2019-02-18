<div class="row">
    <div class="col">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/main">Home</a></li>
                <li class="breadcrumb-item"><a href="/report/">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo($pageTitle) ?></li>
            </ol>
        </nav><!-- /breadcrumb -->
    </div><!-- /span12 -->
</div><!-- /row-fluid -->

<!-- HElper Message Include -->
<?php $this->load->view('Main/Elements/message');?>
<style type="text/css">
.subheader1 { background-color: #CCCCCC;text-align: left; color: #003399; font-family: verdana, arial; font-size: medium; font-weight: bold; border-top: 0px; border-left: 0px; border-right: 0px; border-bottom: 3px; border-color: #000000; border-style: solid; }
.content1{color: #000000; font-family: verdana, arial; font-size: small; font-weight: bold;}
.content2{color: #000000; font-family: verdana, arial; font-size: xx-small; font-weight: normal;}
.phone{color: #FF0033;}

@media print {
    .table-sm td, .table-sm th {
        padding: .0rem;
    }

    .noprint {
        display:none;
    }
}

</style>
    <div class="page-header">
        <h4>
            <div class="row">
                <div class="col"><?php echo($pageTitle) ?></div>
                <div class="col text-right"><?php echo(count($customers)) ?></div>
            </div>
        </h4>
    </div>
    <table id="calllog" class="table table-striped table-bordered table-sm mb-0">
        <tr class="subheader1">
            <th width="200px" style="" >Name</th>
            <th>Address</th>
            <th width="165px">City</th>
            <th width="130px">Phone</th>
        </tr>
        <?php foreach ($customers as $key => $customer) { ?>
        <tr class="content1" >
            <th rowspan="2">
                <a href="/account/<?php echo($customer["customerId"]) ?>" target="_blank"><?php echo($customer["lastName"] . ", " . $customer["firstName"]) ?></a>
            </th>
                <th><a href="/account/<?php echo($customer["customerId"]) ?>" target="_blank"><?php echo($customer["address"]) ?></a></th>
            <th><?php echo($customer["city"]) ?></th>
            <th class="text-right"><?php echo(formatPhoneNumber($customer["phone"])); ?></th>
        </tr>
        <tr>
            <td colspan="3">
                <table class="table table-striped table-bordered table-sm mb-0">
                    <tr class="noprint">
                        <th width="125px">Date</th>
                        <th width="30%">Service</th>
                        <th width="75px">Amount</th>
                        <th width="150px">Tech</th>
                        <th>Notes</th>
                    </tr>
                    <?php foreach ($customer["services"] as $key => $service) { ?>
                    <tr class="content2">
                        <td><?php echo($service["dateOfService"]) ?></td>
                        <td><?php echo($service["description"]) ?></td>
                        <td><?php echo($service["amount"]) ?></td>
                        <td><?php echo($service["technician"]) ?></td>
                        <td><?php echo($service["note"]) ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
        <?php } ?>
    </table>

