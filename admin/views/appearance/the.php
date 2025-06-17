{% include 'header.twig' %}
<div class="wrapper-content">
    <div class="wrapper-content__header">
          </div>
    <div class="wrapper-content__body">
      <!-- Main variables *content* -->
      <div id="block_93">
    <div class="addfunds-block ">
        <div class="bg"></div>
        <div class="divider-top"></div>
        <div class="divider-bottom"></div>
        <div class="container">
            <div class="row addfunds-form">
                <div class="col-lg-8 offset-lg-2">
                   <div class="component_form_group component_card component_radio_button">
                      <div class="card ">
    <div class="col-md-12">
        <div class="well ">
        {{ contentText }}
      </div>
      
     
        {% if error %}
          <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ errorText }}
          </div>
        {% endif %}
        {% if success %}
          <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            {{ successText }}
          </div>
		  
{#<meta http-equiv="refresh" content="0; URL=/" />#}
        {% endif %}
      </div>

 
    <div class="col-md-12>

        {% if paymentsList %}
          <div class="well">
       
        
  
              <form method="post" action="addfunds">

                <div class="form-group">
                  <label for="method" class="control-label">{{ lang['addfunds.method'] }}</label>
                  <select class="form-control" id="payment_method" name="payment_type">
                   {% for payment in paymentsList %}
                      <option value="{{ payment['id'] }}" {% if data['payment_type'] == payment['id'] %} selected {% endif %} >{{ payment['method_name'] }}</option>
                    {% endfor %}
                  </select>
                </div>

                  <div class="form-group">
                      <label for="method" class="control-label">{{ lang['addfunds.amount'] }}</label>
                      <input class="form-control" name="payment_amount" value="{{ data['payment_amount'] }}">
                  </div>

                <button type="submit" class="btn btn-block btn-big-primary">{{ lang['addfunds.button'] }}</button>
              </form>
    
          </div>
        {% endif %}
      
        </div>
      <br>
      <div class="card">
{% if PaytmQR == 2 %}
        <div id="tab-14" class="col-md-12">
           <div class="well">
            <div class="form-group">
                  <label for="method" class="control-label">Method</label>
                  <select class="form-control" id="payment_method" name="payment_type">                      <option value="12" >Paytm Business 
</option>                  </select>
                </div>

              <div class="panel-body">
                <form method="post" action="addfunds">
                    <center><img width="50%" src="{{ PaytmQRimage }}"></center>
                    <div class="form-group col-md-12">
                        <label for="method" class="control-label">Amount</label>
                        <input class="form-control" name="payment_amount" value="{{ data['payment_amount'] }}">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="method" class="control-label">Order ID</label>
                        <p>Enter Transaction/Order ID without spaces. Example - 202210122210100058</p>
                        <input class="form-control" name="paytmqr_orderid" value="{{ data['paytmqr_orderid'] }}">
                    </div>
                    <input type="hidden" class="form-control" name="payment_type" value="14">
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-block btn-big-primary">Submit</button>
                    </div>
                </form>
              </div>
            </div>  
          </div>
        </div>
      {% endif %}
</div>
     <br>
      <div class="">
              <div class="table-responsive custom-table">
            <table class="table table-striped ">
              <thead>
            <tr>
              <th>ID</th>
              <th>Amount</th>
              <th class="width-40">Method</th>
              <th>Date</th>
              <th class="nowrap">Transaction ID</th>
            </tr>
          </thead>
          <tbody>
<td colspan="9">
            {% for transaction in transactions %}
              <tr>
                <td>{{ transaction['payment_id'] }}</td>

                
<td>
               

              {{ (transaction['payment_amount']*currency['value']) }} {{currency["symbol"]}}     
              </td>  
                <td class="width-40">{{ transaction['method_name'] }}</td>
                <td>{{ transaction['payment_create_date'] }}</td>
                <td class="nowrap">{{ transaction['payment_extra'] }}</td>
                
              </tr>
            {% endfor %}
</td>
          </tbody>
        </table>
      </div>
 

 </div>



            </div>


        </div>
      </div>
    </div>
</div>
{% include 'footer.twig' %}
