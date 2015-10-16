<script src="/js/jquery.raty.js"></script>

<div class="sidebar">
	<div class="gadget">
		<h2 class="star">Contact Info</h2>
		{{$info->first_name}} {{$info->last_name}}
		<br>
		<br>
		Tel: {{$info->phone_number}}
		<br>
		E-mail: {{$info->email}}
		<br>
	</div>
	<br>
	<div class="gadget">
		<div align="right">
			Share link via: &nbsp;&nbsp;&nbsp;
			<a href="https://www.facebook.com/dialog/feed?app_id={{Config::get('facebook.app_id')}}&picture={{$picture}}&redirect_uri={{$redirect_url}}&link={{$share_link}}&caption={{$caption}}&description={{$description}}" target="_blank" style="text-decoration: none">
				<img src="/images/share-facebook.png" style="width:20px">
			</a>
			<a href="https://plus.google.com/share?url={{$share_link}}" onclick="javascript:window.open(this.href, 'share-google', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" style="text-decoration: none">
				<img src="/images/share-google.png" style="width:20px">
			</a>
		</div>
		<div class="portlet">
			<div class="portlet_wrapper">
				<div class="portlet_thumb" style="height:120px">
					<img itemprop="image" src="{{$info->thumbnail}}" style="width:100%">
				</div>
				<div style="width:100%;float:left;margin:10px auto">
					<div class="portlet_mark">
						<div class="rating-mark" data-score="{{$info->review}}"></div>
					</div>
					<div class="portlet_follow">
						<span style="border-radius: 4px;padding:0 5px;color:#a94442;border: 1px solid #ebccd1;background-color: #f2dede;border-color: #ebccd1;">{{$info->follow_count}} followers</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="gadget">
		<h2 class="star">Project Events</h2>
		<ul class="ex_menu" id="events">
			<?php foreach($events as $one) :?>
				<li>
					<a href="javascript:void(0)" data-id="{{$one->id}}" title="{{$one->title}}"><b>{{$one->title}}</b></a>
				</li>
			<?php endforeach;?>
		</ul>
	</div>
	<!--<br>
	<div class="gadget">
		<div>
			<form name="frmSendHug" action="" method="post">
				<table width="100%" cellpadding="2px" cellspacing="2px" style="border:0px; border-collapse: collapse">
					<tr>
						<td width="50%">
						<input type="text" name="hug_name" class="hidden" placeholder="Name: ">
						</td>
					</tr>
					<tr>
						<td width="50%">
						<input type="text" name="hug_email" class="hidden" placeholder="Email: ">
						</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="hug_text" class="hidden" style="height:100px;resize: none;" placeholder="Comment:"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><a href="javascript:void(0)" class="button gray" name="send_hug" style="margin-top:10px;float:right">Send Hug</a></td>
					</tr>
				</table>
				<input type="hidden" name="action" value="hug">
			</form>
		</div>
	</div>-->
</div>
<div id="modal_event" class="lightbox">
	<div style="background: #e5e5e5;width:100%;color:#5f5f5f;border-bottom: 1px solid #d5d5d5;padding:7px 0px" align="center">
		<div class="modal_title">Event Detail</div>
	</div>
	<div style="padding: 10px">
		<div class="row">
			<div class="col-md-4">
				<div class="portlet" id="event_thumb"></div>
			</div>
			<div class="col-md-8">
				<table width="100%" border="1" bordercolor="#d0d0d0" style="border-collapse:collapse">
					<tr><td height="40px" style="background:#f0f0f0">&nbsp;&nbsp;<b>Event Title</b>: </td><td><h2><div id="event_title"></div></h2></td></tr>
					<tr><td height="40px" style="background:#f0f0f0">&nbsp;&nbsp;<b>Location</b>: </td><td><span id="event_location"></span></td></tr>
					<tr><td height="40px" style="background:#f0f0f0">&nbsp;&nbsp;<b>Cost</b>: </td><td><span id="event_cost"></span></td></tr>
					<tr><td height="40px" colspan="2"><p id="event_description" style="padding:10px;"></p></td></tr>
					<tr><td height="40px" colspan="2" style="background:#f0f0f0">&nbsp;&nbsp;<b>Contact details for more information:</b></td></tr>
					<tr><td colspan="2"><p id="event_contact_details" style="padding:10px;"></p></td></tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="modal_donation" class="lightbox" style="width:600px">
	<div style="background: #e5e5e5;width:100%;color:#5f5f5f;border-bottom: 1px solid #d5d5d5;padding:7px 0px" align="center">
		<div class="modal_title">Donation!</div>
	</div>
	<form name="frmDonation" method="POST">
		<div style="padding: 10px">
			<div style="color:#73b40e;font-size:20px;"><b>Thank you for your support.</b></div>
			<br>
			<div class="row" style="width:100%;margin:0">
				<div class="col-md-6">
					<input type="text" name="donator_name" id="donator_name" placeholder="Name:" style="width:95%;padding:7px 0px;outline:none;border:0px;border-bottom: 1px solid #a0a0a0">
				</div>
				<div class="col-md-6">
					<input type="text" name="donator_email" id="donator_email" placeholder="Email:" style="width:95%;padding:7px 0px;outline:none;border:0px;border-bottom: 1px solid #a0a0a0">
				</div>
			</div>
			<br><br>
			<div class="row" style="width:100%;margin:0">
				<div class="communication-content" style="padding:0;margin:0">
					<div style="padding: 10px">
						<span style="font-size:15px;"><b>How much are you going to donate?</b></span>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" name="amount" id="amount" style="background:transparent;width:50px;padding:7px 0px;outline:none;border:0px;border-bottom: 1px solid #a0a0a0"> $
					</div>
				</div>
			</div>
			<br><br>
			<div class="row" align="center" style="width:100%;margin:0">
				<img src="/images/paypal-checkout-orange.png" onclick="process()" style="width:45%;cursor:pointer" id="lnk_donation">
			</div>
		</div>
	</form>
</div>
<script>
	function process() {
		if($("form[name='frmDonation'] #donator_name").val() == "") {
			$("form[name='frmDonation'] #donator_name").focus();
			return;
		}
		if($("form[name='frmDonation'] #donator_email").val() == "" || !validateEmail($("form[name='frmDonation'] #donator_email").val())) {
			$("form[name='frmDonation'] #donator_email").focus();
			$("form[name='frmDonation'] #donator_email").select();
			return;
		}
		if($("form[name='frmDonation'] #amount").val() == "" || $("form[name='frmDonation'] #amount").val() == 0 || isNaN($("form[name='frmDonation'] #amount").val())) {
			$("form[name='frmDonation'] #amount").focus();
			$("form[name='frmDonation'] #amount").select();
			return;
		}
		
		$("form[name='frmDonation']").attr("action", "/project/view/{{$type}}/{{$info->id}}/donate").submit();
	}
	
	$(document).ready(function() {
		$("#events a").click(function() {
			var $id = $(this).attr("data-id");

			$.get("/ajax/project/{{$type}}/events/get/"+$id, {}, function(response) {
				if(response.success) {
					info = response.info;
					console.log(info);
					$("#modal_event #event_title").html(info.title);
					$("#modal_event #event_location").html(info.address + ", " + info.city + "<br>" + info.state + " " + info.zip_code + ", " + info.country);
					$("#modal_event #event_date").html(info.event_date);
					$("#modal_event #event_description").html(info.description);
					$("#modal_event #event_cost").html("$" + info.cost);
					$("#modal_event #event_contact_details").html(info.contact_details.replace(/(?:\r\n|\r|\n)/g, "<br>"));
					
					if(info.thumbnail != null && info.thumbnail != "") {
						$("#modal_event #event_thumb").html("<img src='" + info.thumbnail + "' width='100%'>");
					}
					
					$("#modal_event").lightbox_me();
					/*
					$.pgwModal({
					    target: '#modal_event',
					    title: 'Event detail',
					    maxWidth: 700
					});
					*/
				}
			}, "json");
			
		});

		$(".rating-mark").each(function() {
			score = $(this).attr("data-score");
			$(this).raty({
				readOnly : true,
				score : score
			});
		});
		
		$("a[name='send_hug']").click(function() {
			if ($("input[name='hug_name']").val() == "") {
				$("input[name='hug_name']").focus();
				return;
			}
			if ($("input[name='hug_email']").val() == "") {
				$("input[name='hug_email']").focus();
				return;
			}
			if (!validateEmail($("input[name='hug_email']").val())) {
				$("input[name='hug_email']").focus();
				$("input[name='hug_email']").select();
				return;
			}
			if ($("textarea[name='hug_text']").val() == "") {
				$("textarea[name='hug_text']").focus();
				return;
			}

			$("form[name='frmSendHug']").submit();
		});
		
		$("a[name='donation']").click(function() {
			$("#modal_donation").lightbox_me();
		});
	}); 
</script>