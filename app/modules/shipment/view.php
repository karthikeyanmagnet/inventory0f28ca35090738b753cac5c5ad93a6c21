
<?php 
$shipment_id=$_POST['shipment_id'];  // its  
?>

<div class="content-wrapper">
<section class="content">
        <div class="container-fluid">            
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                SHIPMENT DETAILS
                            </h2>
							<div class="actionsBar-1">
								<button id="CreateInvoiceShipmentView" type="button" class="btn btn-primary m-t-15 waves-effect shipment-invoice" onClick="CreateInvoiceShipmentView(<?php echo $shipment_id; ?>)">Create Packing List & Invoice</button>
                                <button id="CreateUpdateShipmentSave" type="button" class="btn btn-primary m-t-15 waves-effect shipment-save" onClick="CreateUpdateShipmentSave()">Create Packing List & Invoice</button>
								<button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog">CANCEL</button>
							</div>
                        </div>
                        <div class="body">
                         <form role="form" id="frmPOEntryMaster">
                    <input type="hidden" name="hid_id" id="hid_id" />
<!--                    <input type="hidden" name="customer_id" id="customer_id" />-->
							<div class="col-md-12 no-padding">
								<div class="col-md-3">		
									Customer
								</div>
								<div class="col-md-3">		
									 <select class="form-control show-tick" id="customer_id" name="customer_id" onChange="loadCustomerShipment()">
                        </select>
									
								</div>
								
							</div>
                            <table class="table table-bordered table-striped table-hover dataTable js-basic-example" id="customFieldsPOEntry">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>PO#</th>
                                        <th>Ship Date</th>
										<th>PO Amount</th>
										<th>Warehouse</th>
										<th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                </tfoot>
                                <tbody>
                                                   
									
                                                                                 
                                </tbody>
                            </table>
							
							<div class="col-md-12 no-padding">
								
								
							</div>
							</form>
                      </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
        </div>
    </section>
    </div>
    
     <div class="AddEditForm modalDiag" id="viewPageModal">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2 class="modal-title">
                   Shipment CREATION
                </h2>   
                <div class="actionsBar-1">
                    <button type="button" class="btn btn-primary m-t-15 waves-effect saveCategory" onclick="saveShipmentInvoiceDetails();">SUBMIT</button>
                    <button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog" onclick="closeCategoryDiaglog();">CANCEL</button>
                </div>               
            </div>
            <div class="body">
                
            </div>
        </div>
	</div>
</div>  