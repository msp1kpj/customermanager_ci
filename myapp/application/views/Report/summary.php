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

<div class="row">
    <div class="col-12 col-md-6">
        <div class="border-bottom mb-2">
            <h3>Call Volume</h3>
        </div>
        <div id="call-chart">
			<canvas id="myChart" width="100%"></canvas>
		</div>
    </div>
    <div class="col-12 col-md-6">
        <div class="border-bottom mb-2">
            <div class="float-right">
                <select class="form-control" id="month-list-dropdown">
                </select>
            </div>
            <h3>Monthly Call Reports</h3>

        </div>
        <div class="report-body">
            <table cellpadding="0" cellspacing="0" class="table table-striped table-bordered" id="monthlistitem">
				<thead>
					<tr>
						<th>Month</th>
						<th>Call Volume</th>
						<th class="d-none d-md-table-cell">Called</th>
						<th class="d-none d-sm-table-cell">Not Called</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th colspan="5">Loading ....</th>
					</tr>
				</tbody>
			</table>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="border-bottom mb-2">
            <div class="float-right">
                <a class="btn btn-secondary" href="/report/nophone"><i class="fas fa-list"></i></a>
            </div>
            <h3>Customers With No Phone (Top <span id="nophone-count">0</span> of <span id="nophone-total">0</span>)</h3>

        </div>
        <div class="report-body">
            <table class="table table-striped table-bordered" id="tblCustomerNoPhone">
				<thead>
					<tr>
						<th>Customer</th>
						<th nowrap class="d-none d-sm-table-cell">Last Service Date</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="3">Congratulation No records Found</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
				</tfoot>
            </table>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="border-bottom mb-2">
            <div class="float-right">
                <a class="btn btn-secondary" href="/report/noservicecall"><i class="fas fa-list"></i></a>
            </div>
            <h3>Customers With No Future Service Call (Top <span id="noservice-count">0</span> of <span id="noservice-total">0</span>)</h3>

        </div>
        <div class="report-body">
            <table class="table table-striped table-bordered" id="tblCustomerNoService">
				<thead>
					<tr>
						<th>Customer</th>
						<th nowrap class="d-none d-sm-table-cell">Last Service Date</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="3">Congratulation No records Found</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
				</tfoot>
            </table>
        </div>
    </div>
</div>

<script type="text/x-custom-template" data-template="monthlistitem">
    <tr>
		<td>${YYYY-MMMM}</td>
		<td><a title="Customers with service calls for ${YYYY-MM-DD}" href="/report/calllog?month=${YYYY-MM-DD}">${totalCall}</a></td>
		<td class="d-none d-md-table-cell"><a title="Customers with future service call dates" href="/report/calllog?month=${YYYY-MM-DD}&type=called">${totalCalled}</a></td>
		<td class="d-none d-sm-table-cell"><a title="Customers with no furture serivce call date" href="/report/calllog?month=${YYYY-MM-DD}&type=nocall">${totalNotCalled}</a></td>
		<td width="75px">
			<div class="btn-group pull-right">
				<a class="btn btn-secondary" href="/report/calllog?month=${YYYY-MM-DD}"><i class="fas fa-list"></i></a>
				<a class="btn btn-secondary" href="/report/calllogprint?month=${YYYY-MM-DD}"><i class="fas fa-print"></i></a>
			</div>
		</td>
	</tr>
</script>

<script type="text/x-custom-template" data-template="customeritem">
	<tr>
		<td><a href="/account/get/${customerId}">${lastName}</a></td>
		<td width="135px" class="d-none d-sm-table-cell">${YYYY-MMMM}</td>
		<td width="45px"><a class="btn btn-secondary" href="/account/get/${customerId}"><i class="far fa-edit"></i></a></td>
	</tr>
</script>