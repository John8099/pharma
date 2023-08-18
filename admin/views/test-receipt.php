<div class="card xrt" style="font-family: FS_MCF;">
  <!-- print border  -->
  <div class="card-body main-template" id="labelBodys">
    <div class="bor">
      <!-- print header section  -->
      <div class="print_head">
        <div class="print_head_content">
          <div class="logoimg" id="mylogo" style="grid-area: 1 / 1 / 1 / 3;"><img src="./assets/img/logo-placeholder.jpg"></div>
          <div class="business" style="margin: 0px auto;"><label class="Bbusiness">Pharmacy</label></div>
        </div>
      </div>
      <!-- print address section  -->
      <p class="space"></p>
      <div class="print_address">
        <table class="addresstable" style="text-align: center;">
          <tbody>
            <tr>
              <td><span class="forSaddress">Address Here</span></td>
            </tr>
            <tr>
              <td><span class="forScity">City Here</span></td>
            </tr>
            <tr>
              <td class="Teltelephone">Telephone Here</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p></p>
      <!-- print reg section  -->
      <div class="print_reg">
        <table>
          <tbody>
            <tr>
              <td><span>REG#</span><span class="forDreg">85</span></td>
              <td><span>TRAN#</span><span class="forDtrn">7009</span></td>
              <td><span>CSHR#</span><span class="forDcshr">960548</span></td>
              <td><span>STR#</span><span class="forDstr">2573</span></td>
            </tr>
            <tr>
              <td colspan="4">
                <p class="space"></p>
              </td>
            </tr>
            <tr>
              <td colspan="2">HELPED BY:</td>
              <td colspan="2" class="forShelpedby">Joshps</td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- print pay section  -->
      <div class="print_pay">
        <!-- base pay  -->
        <table class="print_pay_item">
          <tr class="item1">
            <td class="Numqty1">1</td>
            <td class="forSitemname1">item name</td>
            <td><span class="forSeach1">EACH</span></td>
            <td>
              <sapn class="currency">$</sapn><span class="Numitemval1">0.00</span>&nbsp;&nbsp;<span id="taxiable1">N</span>
            </td>
          </tr>
        </table>
        <!-- display subtotal,itemcount and tax -->
        <table class="print_pay_subtotal">
          <tbody>
            <tr class="items">
              <td class="itemcount">1</td>
              <td class="">ITEM</td>
              <td></td>
            </tr>
            <tr style="height:5.5px;">
              <td colspan="3"></td>
            </tr>
            <tr>
              <td></td>
              <td>Subtotal</td>
              <td><span class="currency">$</span><span class="subtotalval">0.00</span></td>
            </tr>
          </tbody>
        </table>
        <table class="print_pay_tax">
          <tr class="tax1">
            <td></td>
            <td class="forStaxname1">Tax</td>
            <td><span class="currency">$</span><span class="taxval1">0.00</span></td>
          </tr>
        </table>
        <!-- pay total and charge -->
        <table class="print_pay_total">
          <tbody>
            <tr>
              <td></td>
              <td>TOTAL</td>
              <td><span class="currency">$</span><span class="totalval">0.00</span></td>
            </tr>
            <tr>
              <td></td>
              <td><span id="debitcash">CHARGE</span></td>
              <td><span class="currency">$</span><span id="dval" class="totalval">0.00</span><span class="Numdebitorcashval" style="display:none;">0.00</span></td>
            </tr>
          </tbody>
        </table>
        <!-- print pay type -->
        <table class="print_paytype">
          <tbody>
            <tr>
              <td colspan="3" style="text-align:center;"><span class="pay_type">VISA</span>&nbsp;&nbsp;&nbsp;<span class="firstnumber">XXXXXXXXXXXX</span>&nbsp;&nbsp;&nbsp;<span class="forDlastnumber">XXXX</span></td>
            </tr>
          </tbody>
        </table>
        <table>
          <tbody>
            <tr class="tendered" style="display: table-row;">
              <td></td>
              <td colspan="2" style="text-align: left;">CARD PRESENT</td>
            </tr>
            <tr>
              <td colspan="3">
                <p class="space"></p>
              </td>
            </tr>
            <tr class="tendered" style="display: table-row;">
              <td></td>
              <td style="text-align: right;">Tendered</td>
              <td><span class="currency">$</span><span class="tenderedval debit_visa">0.00</span></td>
            </tr>
            <tr>
              <td></td>
              <td style="text-align: right;">Change</td>
              <td><span class="currency">$</span><span class="changeval">0.00</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- print pay end  -->
      <div class="thank" style="padding-left:3%;">
        <pre class="message" style="text-align: center; display: inline-block;">THANKS FOR SHOPPING WITH US</pre>
      </div>
      <div class="print_barcode" style="display: none;">
        <svg id="barcode"></svg>
        <span class="barvalue"></span>
      </div>
      <div class="print_time">
        <table style="text-align: center;">
          <tbody>
            <tr>
              <td><span class="nowdate">08/18/2023</span>&nbsp;&nbsp;&nbsp;<span class="intime">01:56 PM</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <p></p>
  <div class="control_button">
    <div class="col-md-12">
      <center>
        <input type="hidden" value="receipt-drug" name="filename" id="filename">
        <div class="alert-box success">
          <p>Max Limit for This Year : 0. Used So far : 0</p>
          <span class="success">Post download, use <a href="https://expensesreceipt.com/consolidate-receipts.html">resize receipts function <br> to look like the original.</a></span>
        </div>
      </center>
      <center>
        <input type="button" id="btnSave" class="btn btn-primary right-col submit-button margin-btm-10" value="DOWNLOAD RECEIPT">
        <input type="button" id="btnEmail" class="btn btn-primary right-col submit-button margin-btm-10" value="Email RECEIPT" disabled="disabled">
        <input type="button" id="btnPdfCom" class="btn btn-primary right-col submit-button margin-btm-10" value="High Resolution Print PDF">
      </center>
      <br>
      <center>
        <input type="button" id="btnCopy" class="btn btn-primary right-col submit-button margin-btm-10" value="Copy RECEIPT" disabled="disabled">
        <input type="button" id="btnPaste" class="btn btn-primary right-col submit-button margin-btm-10" value="Paste RECEIPT" disabled="" style="cursor: not-allowed;">
      </center>
    </div>
  </div>
</div>