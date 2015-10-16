<div class="modal inmodal fade" id="modal-sendmail-following" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Send Email to <span id="following_name"></span></h4>
			</div>
			<div class="modal-body">
				<p>
					<div class="row">
						<div class="col-lg-1"></div>
						<div class="col-lg-10">
							<input type="text" name="subject" placeholder="Subject: " class="form-control">
							<textarea name="message" placeholder="Message: " class="form-control" rows="5"></textarea>
						</div>
						<div class="col-lg-1"></div>
					</div>
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" name="send_email">
					Send Email
				</button>
				<button type="button" class="btn btn-white" data-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>
