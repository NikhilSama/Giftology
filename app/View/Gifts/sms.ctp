     <script type="text/javascript">

      $(document).ready(function(){
            $(".submit").click(function (){
            	if($("#mobile_number").val().length <10 || $("#mobile_number").val().length > 10)
            	{
            		alert("Plese enter 10 digit mobile number");
            		return false;
            	}
            	if($("#mobile_number").val().length ==10 )
            	{
            		 
            		x = confirm("Please verify that your mobile # " +mobile_number.value +" is correct? SMS can be sent only once.");   
if (x == true)  
{  
 return true;  
}  
else  
{  
return false; }   
            		
            	}
                
            });
           
        });
      
      </script>
      
 <div style="margin-top:100px">
 	<center>
           <?php 
            $pin;
			$originalDate = $gift['Gift']['expiry_date'];
			$newDate = date("d-m-Y", strtotime($originalDate));
            $generated_otp = rand(10000,99999);
               // $link = FULL_BASE_URL.'/reminders/mail_activation?email='.$email_to_update.'&key='.$generatedKey ;
              $message= "Giftology account verification code".' '.$generated_otp.' '."Visit giftology/reminders/sms to complete verification";

           if(!($sms)){
            echo $this->Form->create('gifts', array('action' => 'send_sms'));
            
             echo $this->Form->hidden("id" ,array('label' => false,'div' => false,'value'=>$gift['Gift']['encrypted_gift_id']))?>
             <div class="input email">
              <br><br>
                
                <div class="input email" ><label for="email">Mobile Number   </label><?php echo $this->Form->input("mobile_number" ,array('id' => 'mobile_number', 'label' => false,'div' => false,'class'=>"umstyle5", 'placeholder' => "10 Digit Mobile Number"))?></div>
                <div class="error_message" id="error_mobile" style="display:none; margin-left:120px;">
                    <h5 style="color:#FF0000">*please enter the 10 digit Mobile Number</h5>
                </div>
            </div>
            <div class="input email" ><?php echo $this->Form->hidden("message" ,array('label' => false,'div' => false,'value'=>$message ))?></div>
            <div class="input email" ><?php echo $this->Form->hidden("generated_otp" ,array('label' => false,'div' => false,'value'=>$generated_otp ))?></div>
            <br><br>
            <div class="parent_submit">
            <?php echo $this->Form->Submit(__('Send sms'), array('id'=>'form_shipping')); ?>
               
            </div> </center>
            </div>
            
<?php
           }
