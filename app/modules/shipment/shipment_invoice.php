<?php







$shipment_id=$_POST['shipment_order_id'];

$customer_id=$_POST['customer_id'];



include dirname(realpath('..')) . '/rapper/class.rapper.php';

include '../customer/class.script.php'; 

include '../po_entry/class.script.php';

include 'class.script.php';



	

        

        $shipment = new shipment();

        $op=$shipment->getPODetails(array("id"=>$_POST['shipment_order_id']));

        $po_details = json_decode($op);

        $po_details=$po_details->rsData;

        $customer_id=$po_details->customer_id;

        $po_details=$po_details->po_details;

        

        $customer = new customer();

        $postArr=array("id"=>$customer_id);

        $op=$customer->getSingleView($postArr);

        $data = json_decode($op);

        $data=$data->rsData;

        

        $getCustomerDeliveryMaster=$customer->getCustomerDeliveryMaster($customer_id);

        

        

        $getSingleViewOnlyOrder=$shipment->getSingleViewOnlyOrder($shipment_id);

        

        $date=date("d/m/y"); 

        $time = date('h:i:s');

      

        

        

?>



<div class="content-wrapper">

<section class="content">

<div class="container-fluid">

  <!-- Exportable Table -->

  <div class="row clearfix">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

      <div class="card col-lg-12">

        <div class="header">

          <h2> INVOICE & PACKAGE LIST </h2>

          

        </div>

          <div class="col-lg-12">

              <form action="app/modules/shipment/report_pdf1.php" method="post" id="packagelist_pdf" class="m-t-15" >

                  <input type="hidden" name="action" value="view" />

                  <input type="hidden" name="customer_id" value="<?php echo $customer_id ?>" />

                    <input type="hidden" name="shipment_order_id" value="<?php echo $shipment_id ?>" />
                    
              </form>
                    
                    
                    
                      <form action="app/modules/shipment/report_pdf.php" method="post" id="export_invoice_pdf" class="m-t-15" >

                  <input type="hidden" name="action" value="view" />

                  <input type="hidden" name="customer_id" value="<?php echo $customer_id ?>" />

                    <input type="hidden" name="shipment_order_id" value="<?php echo $shipment_id ?>" />


                 

                    

                <div class="col-lg-1 pull-right">

								<button type="button" class="btn btn-warning m-t-15 waves-effect closeDialog ">CANCEL</button>

                                                                    </div>

                  <div class="col-lg-1 pull-right">

              <button type="button" class="btn btn-primary m-t-15 waves-effect shipment-save " onClick="CreateUpdateShipmentInvoicePackSave()">Save</button>

                  </div>

                   

              <button type="button" class="btn btn-primary m-t-15 waves-effect shipment-save " onClick="CreateInvoicePDF()">Download Invoice PDF</button>
              <button type="button" class="btn btn-primary m-t-15 waves-effect shipment-save " onClick="CreatePackageListPDF()">Download Pakinglist PDF</button>

                 

              </form>

          	

          </div>

        <div class="body col-lg-12">

            <div class="col-lg-12">



  <!-- Nav tabs -->

  <ul class="nav nav-tabs" role="tablist">

    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">InvoiceFormat</a></li>

    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">PackingList</a></li>

    

  </ul>

<form id="update_shipment_order" >

  <!-- Tab panes -->

  <div class="tab-content">

      <div role="tabpanel" class="tab-pane active" id="home">

          <div id="InvoiceFormatDiv" class="tabcontent pull-left">

                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                      <div class="header">

<!--                        <h2> EXPORT INVOICE </h2>-->

                      </div>

                      <div  >

                          

                               <input type="hidden" name="shipment_order_id" value="<?php echo $shipment_id ?>" />

                        <table width="100%">

                          <tr>

                            <td width="60%" valign="top"><table class="table table-bordered table-striped table-hover">

                                <tr>

                                  <td colspan="2" bgcolor="#FFFFFF" ><img src="images/3pl_2logo.png" /> </td>

                                </tr>

                                <tr>

                                  <td colspan="2">(Export under Letter of Undertaking without payment of IGST)</td>

                                </tr>

                                <tr>

                                  <td colspan="2"><h4>PSMITH PROCUREMENT PRIVATE LIMITED</h4></td>

                                </tr>

                                <tr>

                                  <td colspan="2">EMERALD TOWERS, No.5, 50 FEET ROAD, KRISHNASAMY NAGAR,<br>

                                    RAMANATHAPURAM, COIMBATORE - 641045, INDIA.</td>

                                </tr>

                                <tr>

                                  <td>Contact No : +0422-4204070</td>

                                  <td>Email ID : PPugalenthi@sigmaco.com</td>

                                </tr>

                                <tr>

                                  <td>GSTIN: 33AAICP6784H1Z1</td>

                                  <td>State: Tamilnadu</td>

                                </tr>

                                <tr>

                                  <td colspan="2">IE CODE: 3216911055</td>

                                </tr>

                                <tr>

                                  <td width="50%"><strong>CONSIGNEE (BILL TO)</strong></td>

                                  <td width="50%"><strong>BUYER OTHER THAN CONSIGNEE (DELIVERED TO)</strong></td>

                                </tr>

                                <tr>

                                  <td>

                                    <?php echo $data->attention_bill_addr; ?><br />

                                    <?php echo $data->warehouse_bill_addr; ?><br />

                                    <?php echo $data->address_bill_addr ?><br />

                                    <?php echo $data->city_bill_addr; ?><br />

                                    <?php echo $data->zipcode_bill_addr; ?><br />

                                    <?php echo $data->country_bill_addr; ?><br />

                                    <?php echo $data->company_phone_bill_addr; ?><br />

                                    <?php echo $data->company_email_bill_addr; ?><br />

                                    

                                    

                                  </td>

                                  <td>

                                     <?php echo $getCustomerDeliveryMaster[0]['attention_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['warehouse_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['address_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['city_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['state_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['zipcode_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['country_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['company_phone_mail_addr'] ?><br />

                                     <?php echo $getCustomerDeliveryMaster[0]['company_email_mail_addr'] ?><br />

                                    

                                </tr>

                                <tr>

                                  <td colspan="2">Buyer Order and Date: M104744 Dtd. : 23/05/2017   GPO# G19877   Dated 04/08/2017</td>

                                </tr>

                                <tr>

                                  <td>Other References if any</td>

                                  <td><input type="text" class="form-control" name="other_reference" id="other_reference" value="<?php echo $getSingleViewOnlyOrder['other_reference'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Country Of Origin: India</td>

                                  <td>Country Of Final Destination: USA</td>

                                </tr>

                                <tr>

                                  <td>Terms of Delivery & payment: C&F HARRISON</td>

                                  <td>Payment Terms: At Direct Payment Basis</td>

                                </tr>

                              </table></td>

                            <td width="40%" valign="top"><table class="table table-bordered table-striped table-hover">

                                <tr>

                                  <td colspan="2"><div class="demo-radio-button"  >

                                          <input name="original_consignee" type="checkbox" class="with-gap" value="1" 

                                                 id="original_consignee" <?php if($getSingleViewOnlyOrder['original_consignee']==1) { ?> checked="checked" <?php } ?> >

                                      <label for="original_consignee">&nbsp;ORIGINAL FOR CONSIGNEE</label>

                                    </div></td>

                                </tr>

                                <tr>

                                  <td colspan="2"><div class="demo-radio-button" >

                                      <input name="duplicate_transporter" type="checkbox" class="with-gap" value="1" id="duplicate_transporter"

                                             id="original_consignee" <?php if($getSingleViewOnlyOrder['duplicate_transporter']==1) { ?> checked="checked" <?php } ?> >

                                      <label for="duplicate_transporter">&nbsp;DUPLICATE FOR TRANSPORTER</label>

                                    </div></td>

                                </tr>

                                <tr>

                                  <td colspan="2"><div class="demo-radio-button" >

                                      <input name="triplicate_file" type="checkbox" class="with-gap" value="1" id="triplicate_file" 

                                             id="original_consignee" <?php if($getSingleViewOnlyOrder['triplicate_file']==1) { ?> checked="checked" <?php } ?> >

                                      <label for="triplicate_file">&nbsp;TRIPLICATE FOR FILE</label>

                                    </div></td>

                                </tr>

                                <tr>

                                  <td>Invoice No</td>

                                  <td>3PL/1718/<?php echo $shipment_id ?></td>

                                </tr>

                                <tr>

                                  <td>Invoice Date</td>

                                  <td> <?php echo $date  ?> </td>

                                </tr>

                                <tr>

                                  <td>Time of Invoice</td>

                                  <td> <?php  echo $time ?> </td>

                                </tr>

                                <tr>

                                  <td>Reverse Charge</td>

                                  <td><input name="reverse_charge" id="reverse_charge" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['reverse_charge'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Pre Carriage Mode</td>

                                  <td><input name="precarriage_mode" id="precarriage_mode" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['precarriage_mode'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Vehicle Number</td>

                                  <td><input name="vehicle_no" id="vehicle_no" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['vehicle_no'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Date of Supply</td>

                                  <td><input name="supply_date" id="supply_date" type="date" class="form-control" value="<?php echo $getSingleViewOnlyOrder['supply_date'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Place of Supply</td>

                                  <td><input name="supply_place" id="supply_place" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['supply_place'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Vessel / Flight No.</td>

                                  <td><input name="vessel_flight_no" id="vessel_flight_no" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['vessel_flight_no'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Port of Loading</td>

                                  <td><input name="port_load" id="port_load" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['port_load'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Port of Discharge</td>

                                  <td><input name="port_discharge" id="port_discharge" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['port_discharge'] ?>" /></td>

                                </tr>

                                <tr>

                                  <td>Final Destination</td>

                                  <td><input name="final_destination" id="final_destination" type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['final_destination'] ?>" /></td>

                                </tr>

                              </table></td>

                          </tr>

                        </table>

                          

                        <table class="table table-bordered table-striped table-hover">

                          <thead>

                            <tr>

                              <th rowspan="2">S.No</th>

                              <th rowspan="2">Description</th>

                              <th rowspan="2">HSN CODE</th>

                              <th rowspan="2">UOM</th>

                              <th rowspan="2">QUANTITY</th>

                              <th rowspan="2">RATE in USD</th>

                              <th rowspan="2">VALUE</th>

                              <th rowspan="2">DISCOUNT</th>

                              <th rowspan="2">NET AMOUNT</th>

                              <th colspan="2">IGST</th>

                              <th rowspan="2">AMOUNT AFTER TAX</th>

                            </tr>

                            <tr>

                              <th>%</th>

                              <th>VALUE</th>

                            </tr>

                          </thead>

                          <tbody>

                                <?php 

                                $k=1;

                                $total_qty=0;

                                $total_rate=0;

                                $total_weight=0;

                                for($i=0;$i<=count($po_details);$i++) {

                                  

                                        foreach($po_details[$i]->itmgrpdet_details as $po_detail_s ) {

                                            $total_weight=$po_detail_s->weight_line+$total_weight;

                                     

                                  ?> 

                            <tr>

                              <td> <?php echo $k ?> </td>
                              

                              <td><span id="item_desc_rw">  <?php echo $po_detail_s->item_desc;  ?>  </span></td>
                              

                              <td><input  id="hsn_code_rw" type="text" class="form-control" value="8413.91.9080" /></td>
                              

                              <td><input  id="uom_unit_rw" type="text" class="form-control" value=" Nos " /></td>
                              

                              <td><span id="item_qty_rw"><?php echo $po_detail_s->ordered_qty;  

                              $total_qty=$po_detail_s->ordered_qty+$total_qty;

                              ?></span></td>
                              

                              <td><span id="item_rate_rw"></span></td>
                              

                              <td><span id="item_amt_rw"><?php echo $po_detail_s->unit_cost;  

                               $total_rate=$po_detail_s->unit_cost+$total_rate;

                              ?></span></td>
                              

                              <td>

                                  10 %

                                  

                              </td>
                              

                              <td>

                                  <?php  $net_amount=$po_detail_s->unit_cost-($po_detail_s->unit_cost/100*10);

                                  echo $net_amount;

                                  ?>

                                  

                              </td>
                              

                              <td> 18% </td>

                              <td> 

                              <?php $gst_amount= $net_amount/100*18; echo $gst_amount; ?>

                              </td>
                              

                              <td> 

                                  <?php

                                        $amount_after_tax=$net_amount+$gst_amount;

                                        echo $amount_after_tax;

                                    ?>

                                  

                               </td>
                               

                            </tr>

                                <?php 

                                $k=$k+1;

                                        } 

                                

                                }

                                ?>

                          </tbody>

                          <tfoot>

                            <tr>

                              <th>&nbsp;</th>

                              <th colspan="5">Freight Charges</th>

                              <th>3658.40</th>

                              <th colspan="5">&nbsp;</th>

                            </tr>

                            <tr>

                              <th>&nbsp;</th>

                              <th colspan="3">Total</th>

                              <th> <?php echo $total_qty ?> </th>

                              <th>&nbsp;</th>

                               <th> <?php echo $total_rate ?> </th>

                              <th colspan="5">&nbsp;</th>

                            </tr>

                          </tfoot>

                        </table>

                        <table class="table table-bordered">

                          <tr>

                            <td width="40%" rowspan="2">Declarations:

                              1) I/We declare that this invoice shows actual price of the

                              goods and/ or services described and that all particulars 

                              are true and correct

                              2) Subject to the jurisdiction of courts in Mumbai</td>

                            <td width="60%">Amount Chargeable in Words / Currency: US Dollar Five Thousand Three Hundred and Eighty Five and cents Ninety only</td>

                          </tr>

                          <tr>

                            <td><table width="100%">

                                <tr>

                                  <td>BANK</td>

                                  <td>AXIS BANK</td>

                                </tr>

                                <tr>

                                  <td>ACCOUNT</td>

                                  <td>916020042297998</td>

                                </tr>

                                <tr>

                                  <td>IFSC CODE</td>

                                  <td>UTIB0002811</td>

                                </tr>

                              </table></td>

                          </tr>

                          <tr>

                            <td>Place : Coimbatore</td>

                            <td>Ceritified that the particulars given above are true and correct</td>

                          </tr>

                          <tr>

                            <td> Date  : <?php echo $date  ?></td>

                            <td><h5>for Psmith Procurement Private Limited</h5></td>

                          </tr>

                          <tr>

                            <td>&nbsp;</td>

                            <td rowspan="3">&nbsp;</td>

                          </tr>

                          <tr>

                            <td>&nbsp;</td>

                          </tr>

                          <tr>

                            <td>TOTAL NETT WEIGHT: <?php echo $total_weight; ?> Kgs</td>

                          </tr>

                          <tr>

                            <td>TOTAL GROSS WEIGHT: 680.00 Kgs</td>

                            <td><h5>Authorised Signatory</h5></td>

                          </tr>

                          <tr>

                            <td colspan="2"><h5>Works: PSMITH PROCUREMENT PRIVATE LIMITED, Plot no. 34, Block B, Industrial Park, Menakuru SEZ, NAIDUPET - 524421, Nellore District, Andhra Pradesh. TIN# 37805792891 Dated 01/08/2016 GSTIN: 37AAICP6784H1ZT</h5></td>

                          </tr>

                          <tr>

                            <td colspan="2" align="center"><h5>CIN NO : U74999TZ2016FTC027717</h5></td>

                          </tr>

                        </table>

                      </div>

                    </div>

                  </div>

                </div>

      </div>

      <div role="tabpanel" class="tab-pane" id="profile">

          <div id="PackingListDiv" class="tabcontent pull-left">

                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="card">

                      <div class="header">

                        <h2> PACKING LIST </h2>

                      </div>

                      <div>

                        <table class="table table-bordered table-striped table-hover">

                          <tr>

                            <td width="50%">Exporter</td>

                            <td width="25%">Invoice No & Date</td>

                            <td width="25%">Exporter Ref.</td>

                          </tr>

                          <tr>

                            <td rowspan="5">PSMITH PROCUREMENT PRIVATE LIMITED,<br />

                              EMERALD TOWERS, No.5, 50 FEET ROAD, <br />

                              KRISHNASAMY NAGAR, RAMANATHAPURAM, <br />

                              COIMBATORE - 641045, INDIA. <br />

                              Contact Number: 0422-4204070 <br />

                              TIN / CST: 33366404099 Dtd.: 13/08/2016</td>

                            <td>EXP- 009/16-17 Dtd.: 14/12/2016</td>

                            <td>IE CODE: 3216911055</td>

                          </tr>

                          <tr>

                            <td colspan="2">Buyer's Order No. & Date</td>

                          </tr>

                          <tr>

                            <td colspan="2">&nbsp;</td>

                          </tr>

                          <tr>

                            <td colspan="2">Other reference(s)</td>

                          </tr>

                          <tr>

                            <td colspan="2">&nbsp;</td>

                          </tr>

                        </table>

                        <table class="table table-bordered table-striped table-hover">

                          <tr>

                            <td width="50%">Consignee</td>

                            <td width="50%">BUYER (If other than Consignee): DELIVER TO:</td>

                          </tr>

                          <tr>

                            <td>

                            

                            <?php echo $data->attention_bill_addr; ?><br />

                                    <?php echo $data->warehouse_bill_addr; ?><br />

                                    <?php echo $data->address_bill_addr ?><br />

                                    <?php echo $data->city_bill_addr; ?><br />

                                    <?php echo $data->zipcode_bill_addr; ?><br />

                                    <?php echo $data->country_bill_addr; ?><br />

                                    <?php echo $data->company_phone_bill_addr; ?><br />

                                    <?php echo $data->company_email_bill_addr; ?><br />

                            

                            </td>

                            <td>&nbsp;</td>

                          </tr>

                        </table>

                        <table class="table table-bordered table-striped table-hover">

                          <tr>

                            <td width="25%">Pre-Carriage by</td>

                            <td width="25%">Place of Receipt by pre-carrier</td>

                            <td width="25%">Country of origin of goods</td>

                            <td width="25%">Country of final Destination</td>

                          </tr>

                          <tr>

                            <td ><input type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['pre_carriage_by']  ?>" name="pre_carriage_by"  /></td>

                            <td ><input type="text" class="form-control" value="<?php echo $getSingleViewOnlyOrder['vessel_flight_no_p']  ?>" name="vessel_flight_no_p" /></td>

                            <td >INDIA</td>

                            <td >USA</td>

                          </tr>

                          <tr>

                            <td >Vessel / Flight No.</td>

                            <td >Port of Loading</td>

                            <td colspan="2">Terms of Delivery & payment: FOB INDIA</td>

                          </tr>

                          <tr>

                            <td ><input type="text" class="form-control"  name="place_of_receipt" value="<?php echo $getSingleViewOnlyOrder['place_of_receipt'];  ?>" /></td>

                            <td >&nbsp;</td>

                            <td colspan="2" rowspan="3"> GOODS OF NO COMMERCIAL VALUE INVOLVED<br />

                              CUSTOMS PURPOSE ONLY<br />

                              Payment Terms: At Direct Payment Basis </td>

                          </tr>

                          <tr>

                            <td >Port of Discharge</td>

                            <td >Final Destination</td>

                          </tr>

                          <tr>

                            <td >&nbsp;</td>

                            <td >NEW JERSEY - 08514</td>

                          </tr>

                        </table>

                        <table class="table table-bordered table-striped table-hover">

                          <thead>

                            <tr>

                              <th>Marks & Numbers. Container No.</th>

                              <th>No. & kind of Packages</th>

                              <th>Description of Goods</th>

                              <th>Quantity in Set</th>

                              <th>Nett Weight per Crate in Kgs.</th>

                              <th>Gross Weight per Crate in Kgs.</th>

                              <th>Crate Sizes in MM L X W X H</th>

                            </tr>

                          </thead>

                          <tbody>

                             <?php 

                                $k=1;

                                $total_qty=0;

                                $total_weight=0;

                                for($i=0;$i<=count($po_details);$i++) {

                                  

                                        foreach($po_details[$i]->itmgrpdet_details as $po_detail_s ) {

                                            

                                     

                                  ?> 

                            <tr>

                              <td><input type="text" class="form-control" value="" id="pack_container_no" /></td>

                              <td><input type="text" class="form-control" value=""  id="pack_container_info" /></td>

                              <td>

                                  <?php echo $po_detail_s->item_desc;  ?>

<!--                                  <input type="text" class="form-control" value='' name="pack_description[]" id="pack_description_1" />-->

<!--                                <br />

                                <input type="text" class="form-control" value='' name="pack_description[]" id="pack_description_2"/>

                                <br />

                                <input type="text" class="form-control" value='' name="pack_description[]" id="pack_description_3"/>-->

                              </td>

                              <td>

                                  <?php echo $po_detail_s->ordered_qty;

                                  $total_qty=$po_detail_s->ordered_qty+$total_qty;

                                  ?>

<!--                                  <input type="text" class="form-control" value="" name="pack_quantity_set[]" id="pack_quantity_set_1" />-->

<!--                                <br />

                                <input type="text" class="form-control" value="" name="pack_quantity_set[]" id="pack_quantity_set_2"/>

                                <br />

                                <input type="text" class="form-control" value="" name="pack_quantity_set[]" id="pack_quantity_set_3"/>-->

                              </td>

                              <td>

                                  <?php 

                                  echo $po_detail_s->weight_line; 

                                  $total_weight=$po_detail_s->weight_line+$total_weight;

                                  ?>

<!--                                  <input type="text" class="form-control" value="" name="pack_net_weight[]" id="pack_net_weight_1"/>-->

<!--                                <br />

                                <input type="text" class="form-control" value="" name="pack_net_weight[]" id="pack_net_weight_2" />

                                <br />

                                <input type="text" class="form-control" value="" name="pack_net_weight[]" id="pack_net_weight_3"/>-->

                              </td>

                              <td><input type="text" class="form-control" value=""  id="pack_gross_weight" /></td>

                              <td><input type="text" class="form-control" value=""  id="pack_size" /></td>

                            </tr>

                            <?php

                            }

                              }

                            ?>

                          </tbody>

                          <tfoot>

                            <tr>

                              <td colspan="3" align="right">Total</td>

                              <td> <?php echo $total_qty ?>  </td>

                              <td> <?php echo $total_weight ?> </td>

                              <td>4.00</td>

                              <td>&nbsp;</td>

                            </tr>

                          </tfoot>

                        </table>

                        <table class="" width="100%">

                          <tr>

                            <td width="70%">TOTAL NETT WEIGHT:  <?php echo $total_weight ?> Kgs</td>

                            <td width="30%">&nbsp;</td>

                          </tr>

                          <tr>

                            <td >TOTAL GROSS WEIGHT: 4.00 Kgs</td>

                            <td rowspan="3" ></td>

                          </tr>

                          <tr>

                            <td >&nbsp;</td>

                          </tr>

                          <tr>

                            <td >Declaration</td>

                          </tr>

                          <tr>

                            <td >We declare that invoice shows the actual price of goods</td>

                            <td >Signature / Date / Co stamp.</td>

                          </tr>

                          <tr>

                            <td>described and that all particulars are true & correct.</td>

                            <td >14/12/2016</td>

                          </tr>

                        </table>

                      </div>

                    </div>

                  </div>

                </div>

      </div>

    

  </div>

  </form>



</div>

            

<!--          <form role="form" id="frmShipmentInvoice">

            <input type="hidden" name="hid_id" id="hid_id" />

            <div class="form-group form-float">

              <div class="tab">

                <button class="tablinks" onclick="openTabPurchaseSUpPAssign(event, 'InvoiceFormatDiv')" type="button" id="defaultOpen">InvoiceFormat</button>

                <button class="tablinks divBtnTabSuppAssig" onclick="openTabPurchaseSUpPAssign(event, 'PackingListDiv')" type="button" >PackingList</button>

              </div>

              <div class="form-group form-float ">

                

                

              </div>

            </div>

          </form>-->

        </div>

      </div>

    </div>

  </div>

</div>

</section>

</div>

    