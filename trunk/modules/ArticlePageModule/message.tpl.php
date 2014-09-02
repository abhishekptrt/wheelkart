<div style="display: none;" class="EmailMessage" id="EmailMessage">
			  <form onsubmit="return main_validateSubmit();" id="EmailMessageForm" method="post" action="">
            	<a class="closebtn" onclick="hideMessage();" href="javascript:void(0);">Close Button</a>
            	<div class="titleBg">Share Via Email</div>
                <div id="ShareLink" class="ShareLink">Share this link with your friends</div>
                <div id="EmailMessage" class="InputBlock">
					<div id="msg" class="error">
                    </div>
                	<div class="InputBox blk">
                    	<span class="lc">Your Name :</span>
                        <div class="inputBlok lc">
                        	<input type="text" value="" id="fromName" name="fromName">
                            <div class="error" id="errName"></div>
                        </div>
                    </div>
                    <div class="InputBox blk">
                    	<span class="lc">Your Email :</span>
                        <div class="inputBlok lc">
                        	<input type="text" value="" id="fromEmail" name="fromEmail">
                            <div class="error" id="errEmail"></div>
                        </div>
                    </div>
                    <div class="InputBox blk">
                    	<span class="lc">Your Friends Email :</span>
                        <div class="inputBlok lc">
                        	 <input type="text" value="" id="toEmail" name="toEmail">
                            <div class="error" id="errFrEmail"></div>
                        </div>
                    </div>
                    <div class="InputBox blk">
                    	<span class="lc">Message :</span>
						
                        <div class="inputBlok lc">
                        	<textarea rows="3" id="message" name="message"></textarea>
                            <div class="error" id="errMsg"></div>
                        </div>		
                    </div>
                    
                    <div class="Buttons">
					    <input type="hidden" value="<?php echo $article['id'] ?>" name="article_id">
                    	<button type="submit" class="btn" value="Send">Send</button>
					    <button onclick="hideMessage();" class="btn disabled" type="button">Cancel</button>
                    </div>
                </div>
				</form>
            </div>